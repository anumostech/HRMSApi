<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Company;
use App\Models\Employee;
use App\Services\AttendanceParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $parser;

    public function __construct(AttendanceParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Display a listing of attendance summary.
     */
    public function index(Request $request)
    {
        $companies = Company::all();
        
       $query = AttendanceLog::with(['company', 'user'])
                ->select(
                    'company_id',
                    'userid',
                    DB::raw("DATE(timestamp) as date"),

                    DB::raw("
                        CASE 
                            WHEN TIME(MIN(timestamp)) <= '12:00:00' 
                            THEN MIN(timestamp) 
                            ELSE NULL
                        END as punch_in
                    "),

                    DB::raw("
                        CASE 
                            WHEN TIME(MAX(timestamp)) >= '12:00:00'
                            THEN MAX(timestamp) 
                            ELSE NULL
                        END as punch_out
                    "),

                    DB::raw("
                        CASE 
                            WHEN TIME(MIN(timestamp)) >= '08:11:00' 
                                AND TIME(MIN(timestamp)) <= '12:00:00' 
                            THEN 'Late Comer' 
                            ELSE 'On Time' 
                        END as status
                    ")
                );

        // Filter by Company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by Employee Name (searching through the user relationship - now linked to Employee model)
        if ($request->filled('employee_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->employee_name . '%');
            });
        }

        // Filter by Date
        if ($request->filled('date_preset')) {
            $now = Carbon::now();
            switch ($request->date_preset) {
                case 'today':
                    $query->whereDate('timestamp', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('timestamp', $now->subDay()->toDateString());
                    break;
                case 'last_week':
                    $query->whereBetween('timestamp', [$now->subWeek()->startOfDay(), Carbon::now()->endOfDay()]);
                    break;
                case 'last_month':
                    $query->whereBetween('timestamp', [$now->subMonth()->startOfDay(), Carbon::now()->endOfDay()]);
                    break;
                case 'custom':
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('timestamp', [
                            Carbon::parse($request->from_date)->startOfDay(),
                            Carbon::parse($request->to_date)->endOfDay()
                        ]);
                    }
                    break;
            }
        }

        $attendance = $query->groupBy('company_id', 'userid', 'date')
            ->orderBy('date', 'desc')
            ->orderBy('punch_in', 'desc')
            ->paginate(25)
            ->withQueryString();

        // Stats for cards
        $today = Carbon::today()->toDateString();
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $inactiveEmployees = Employee::where('status', 'inactive')->count();
        
        $todayLogs = AttendanceLog::whereDate('timestamp', $today)
            ->select('userid', DB::raw('MIN(timestamp) as punch_in'), DB::raw('MAX(timestamp) as punch_out'))
            ->groupBy('userid')
            ->get();

        $punchedInCount = $todayLogs->filter(function($log) {
            return Carbon::parse($log->punch_in)->format('H:i:s') <= '12:00:00';
        })->count();

        $punchedOutCount = $todayLogs->filter(function($log) {
            return Carbon::parse($log->punch_out)->format('H:i:s') >= '12:00:00';
        })->count();

        $stats = [
            'total' => $totalEmployees,
            'active' => $activeEmployees,
            'inactive' => $inactiveEmployees,
            'punched_in' => $punchedInCount,
            'punched_out' => $punchedOutCount
        ];

        return view('attendance.index', compact('attendance', 'companies', 'stats'));
    }

    /**
     * Show the form for uploading attendance.
     */
    public function create()
    {
        $companies = Company::all();
        return view('attendance.upload', compact('companies'));
    }

    /**
     * Store uploaded attendance logs.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'file' => 'required|file',
        ]);

        $companyId = $request->company_id;
        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $extension = $file->getClientOriginalExtension();

        $parsedData = $this->parser->parse($content, $extension);
        
        // Group data by user and date to find first/last entries
        $grouped = collect($parsedData)->groupBy(function($data) {
            return $data['userid'] . '_' . Carbon::parse($data['timestamp'])->format('Y-m-d');
        });

        $processed = 0;
        $skipped = 0;

        foreach ($grouped as $logs) {
            $sorted = $logs->sortBy('timestamp');
            $first = $sorted->first();
            $last = $sorted->last();

            try {
                // Store Punch In (First Entry)
                AttendanceLog::firstOrCreate([
                    'company_id' => $companyId,
                    'userid' => $first['userid'],
                    'timestamp' => $first['timestamp'],
                ], [
                    'status' => $first['status'],
                    'device_id' => $first['device_id'],
                ]);
                $processed++;

                // Store Punch Out (Last Entry if different)
                if ($first['timestamp'] != $last['timestamp']) {
                    AttendanceLog::firstOrCreate([
                        'company_id' => $companyId,
                        'userid' => $last['userid'],
                        'timestamp' => $last['timestamp'],
                    ], [
                        'status' => $last['status'],
                        'device_id' => $last['device_id'],
                    ]);
                    $processed++;
                }
            } catch (\Exception $e) {
                $skipped++;
            }
        }

        // Trigger Late/Absent Checks
        $this->notifyHRAboutLatecomers($companyId);
        $this->notifyHRAboutAbsentees($companyId);

        return redirect()->route('attendance.index')->with('success', "Processed $processed records from " . count($grouped) . " daily logs.");
    }

    private function notifyHRAboutLatecomers($companyId)
    {
        $hrUsers = \App\Models\User::all(); // Notify all admins
        $monthStart = Carbon::now()->startOfMonth();
        
        $lateEmployees = AttendanceLog::where('company_id', $companyId)
            ->whereBetween('timestamp', [$monthStart, Carbon::now()])
            ->whereRaw("TIME(timestamp) >= '08:11:00' AND TIME(timestamp) <= '12:00:00'")
            ->select('userid', DB::raw('count(*) as late_count'))
            ->groupBy('userid')
            ->having('late_count', '>=', 3)
            ->get();

        foreach ($lateEmployees as $lateRecord) {
            $employee = Employee::find($lateRecord->userid);
            if ($employee) {
                foreach ($hrUsers as $user) {
                    $user->notify(new \App\Notifications\LateWarningNotification($employee, $lateRecord->late_count));
                }
            }
        }
    }

    private function notifyHRAboutAbsentees($companyId)
    {
        $today = Carbon::today()->toDateString();
        $hrUsers = \App\Models\User::all();
        
        $activeEmployees = Employee::where('company_id', $companyId)
            ->where('status', 'active')
            ->get();

        foreach ($activeEmployees as $employee) {
            $punchedIn = AttendanceLog::where('userid', $employee->id)
                ->whereDate('timestamp', $today)
                ->exists();

            if (!$punchedIn) {
                foreach ($hrUsers as $user) {
                    $user->notify(new \App\Notifications\AbsentNotification($employee)); 
                }
            }
        }
    }
}
