<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function getStats()
    {
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

        $lateCount = $todayLogs->filter(function($log) {
            $time = Carbon::parse($log->punch_in)->format('H:i:s');
            return $time >= '08:11:00' && $time <= '12:00:00';
        })->count();

        $absentCount = $activeEmployees - $punchedInCount;
        $notPunchedInCount = $absentCount; // Same logic as absent usually

        return response()->json([
            'total' => $totalEmployees,
            'active' => $activeEmployees,
            'inactive' => $inactiveEmployees,
            'punched_in' => $punchedInCount,
            'punched_out' => $punchedOutCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'not_punched_in' => $notPunchedInCount
        ]);
    }

    public function getChartData()
    {
        // Monthly Attendance (Last 6 months)
        $monthlyAttendance = AttendanceLog::select(
                DB::raw("DATE_FORMAT(timestamp, '%b %Y') as month"),
                DB::raw("count(DISTINCT userid, DATE(timestamp)) as count")
            )
            ->where('timestamp', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('timestamp')
            ->get();

        // Late Employees Trend (Last 30 days)
        $lateTrend = DB::table('attendance_logs')
            ->select(DB::raw("DATE(timestamp) as date"), DB::raw("count(*) as count"))
            ->whereRaw("TIME(timestamp) >= '08:11:00' AND TIME(timestamp) <= '12:00:00'")
            ->where('timestamp', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Department-wise Distribution
        $deptDistribution = Employee::select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->whereNotNull('department')
            ->get();

        return response()->json([
            'monthly' => [
                'labels' => $monthlyAttendance->pluck('month'),
                'data' => $monthlyAttendance->pluck('count')
            ],
            'late' => [
                'labels' => $lateTrend->pluck('date'),
                'data' => $lateTrend->pluck('count')
            ],
            'dept' => [
                'labels' => $deptDistribution->pluck('department'),
                'data' => $deptDistribution->pluck('count')
            ]
        ]);
    }

    public function getNotifications()
    {
        $notifications = auth()->user()->unreadNotifications;
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json($notifications);
    }
}
