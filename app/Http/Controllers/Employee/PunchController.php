<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\TaskReport;
use App\Models\WfhRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PunchController extends Controller
{
    public function punchIn(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $today = Carbon::today()->format('Y-m-d');

        // Check if already punched in
        $hasPunched = AttendanceLog::where('userid', $employee->employee_id)
            ->whereDate('timestamp', $today)
            ->exists();

        if ($hasPunched) {
            return redirect()->back()->with('error', 'Already punched in today.');
        }

        AttendanceLog::create([
            'company_id' => $employee->company_id ?? 1,
            'userid' => $employee->employee_id,
            'timestamp' => Carbon::now(),
            'status' => 1,
            'log_status' => 'IN'
        ]);

        return redirect()->back()->with('success', 'Punched in successfully.');
    }

    public function punchOut(Request $request)
    {
        $request->validate([
            'tasks_completed' => 'required|string',
            'plan_tomorrow' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $employee = Auth::guard('employee')->user();
        $today = Carbon::today()->format('Y-m-d');

        // Verify if punch in exists and no punch out exists yet
        $logs = AttendanceLog::where('userid', $employee->employee_id)
            ->whereDate('timestamp', $today)
            ->orderBy('timestamp', 'asc')
            ->get();

        if ($logs->count() == 0) {
            return redirect()->back()->with('error', 'You have not punched in yet.');
        }
        if ($logs->count() >= 2) {
            return redirect()->back()->with('error', 'Already punched out today.');
        }

        // Save Task Report
        TaskReport::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'tasks_completed' => $request->tasks_completed,
            'plan_tomorrow' => $request->plan_tomorrow,
            'remarks' => $request->remarks
        ]);

        // Create punch out record
        AttendanceLog::create([
            'company_id' => $employee->company_id ?? 1,
            'userid' => $employee->employee_id,
            'timestamp' => Carbon::now(),
            'log_status' => 'OUT'
        ]);

        return redirect()->back()->with('success', 'Punched out successfully and tasks submitted.');
    }
}
