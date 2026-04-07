<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Api\ApiController;
use App\Models\AttendanceLog;
use App\Models\TaskReport;
use App\Models\WfhRequest;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class EmployeePortalApiController extends ApiController
{
    /**
     * Get Employee Dashboard Data
     */
    public function dashboard(): JsonResponse
    {
        $user = auth('api')->user();
        if (!$user) return $this->error('Unauthorized', 401);
        
        $employee = $user->employee;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $employee->load('department', 'company', 'designation');

        $today = Carbon::today()->toDateString();
        
        // Attendance stats for today
        $attendance = AttendanceLog::where('userid', $employee->employee_id)
            ->whereDate('log_date', $today)
            ->select('punch_in', 'punch_out')
            ->first();

        // 30-day attendance history
        $from = Carbon::now()->subDays(30)->startOfDay();
        $to = Carbon::now()->endOfDay();
        $attendanceHistory = AttendanceLog::where('userid', $employee->employee_id)
            ->whereBetween('log_date', [$from, $to])
            ->select('log_date', 'punch_in', 'punch_out')
            ->orderByDesc('log_date')
            ->get();

        // Leave stats
        $totalLeavesTaken = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->sum('duration_days');

        $leaveBalance = $employee->total_leaves_allocated - $totalLeavesTaken;

        // Punch Access Logic
        $canPunch = false;
        
        // 1. Check default designation punch access
        if ($employee->designation && $employee->designation->default_punch_access) {
            $canPunch = true;
        }

        // 2. Specific designation checks
        if (!$canPunch && ($employee->designation && in_array($employee->designation->name, ['Delivery Man', 'Salesperson']))) {
            $canPunch = true;
        }

        // 3. Check for approved WFH request today
        if (!$canPunch) {
            $canPunch = WfhRequest::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->where('status', 'Approved')
                ->exists();
        }

        return $this->success([
            'employee' => $employee,
            'today_attendance' => [
                'punched_in' => (bool)$attendance,
                'punched_out' => $attendance && $attendance->punch_out,
                'punch_in_time' => $attendance ? $attendance->punch_in : null,
                'punch_out_time' => $attendance ? $attendance->punch_out : null,
            ],
            'leave_stats' => [
                'total_taken' => (float)$totalLeavesTaken,
                'balance' => (float)$leaveBalance,
                'allocated' => (float)$employee->total_leaves_allocated,
            ],
            'attendance_history' => $attendanceHistory,
            'can_punch' => $canPunch,
            'pending_wfh_count' => WfhRequest::where('employee_id', $employee->id)->where('status', 'pending')->count(),
            'recent_leaves' => LeaveRequest::where('employee_id', $employee->id)->latest()->take(5)->get(),
        ]);
    }

    /**
     * Punch In
     */
    public function punchIn(): JsonResponse
    {
        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $today = Carbon::today()->toDateString();

        $alreadyPunched = AttendanceLog::where('userid', $employee->id)
            ->whereDate('log_date', $today)
            ->exists();

        if ($alreadyPunched) {
            return $this->error('Already punched in today.', 400);
        }

        $log = AttendanceLog::create([
            'company_id' => $user->company_id ?? 1,
            'userid' => $employee->id,
            'log_date' => $today,
            'punch_in' => Carbon::now(),
            'status' => 1,
            'log_status' => 'IN'
        ]);

        return $this->success($log, 'Punched in successfully.', 201);
    }

    /**
     * Punch Out
     */
    public function punchOut(Request $request): JsonResponse
    {
        $request->validate([
            'tasks_completed' => 'required|string',
            'plan_tomorrow' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $today = Carbon::today()->toDateString();

        $log = AttendanceLog::where('userid', $employee->id)
            ->whereDate('log_date', $today)
            ->first();

        if (!$log) return $this->error('You have not punched in yet.', 400);
        if ($log->punch_out) return $this->error('Already punched out today.', 400);

        TaskReport::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'tasks_completed' => $request->tasks_completed,
            'plan_tomorrow' => $request->plan_tomorrow,
            'remarks' => $request->remarks
        ]);

        $log->update([
            'punch_out' => Carbon::now(),
            'log_status' => 'OUT'
        ]);

        return $this->success($log, 'Punched out successfully and tasks submitted.');
    }

    /**
     * Leaves
     */
    public function leaves(): JsonResponse
    {
        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $leaves = LeaveRequest::where('employee_id', $employee->id)->latest()->get();
        return $this->success($leaves);
    }

    public function storeLeave(Request $request): JsonResponse
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string'
        ]);

        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $leave = LeaveRequest::create(array_merge($request->all(), [
            'employee_id' => $employee->id,
            'status' => 'pending'
        ]));

        return $this->success($leave, 'Leave request submitted successfully', 201);
    }

    /**
     * Task Reports
     */
    public function taskReports(): JsonResponse
    {
        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $reports = TaskReport::where('employee_id', $employee->id)->latest()->get();
        return $this->success($reports);
    }

    public function storeTaskReport(Request $request): JsonResponse
    {
        $request->validate([
            'tasks_completed' => 'required|string',
            'plan_tomorrow' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $report = TaskReport::create(array_merge($request->all(), [
            'employee_id' => $employee->id,
            'date' => Carbon::now()
        ]));

        return $this->success($report, 'Task report submitted successfully', 201);
    }

    /**
     * WFH Requests
     */
    public function wfhRequests(): JsonResponse
    {
        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $requests = WfhRequest::where('employee_id', $employee->id)->latest()->get();
        return $this->success($requests);
    }

    public function storeWfhRequest(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $user = auth('api')->user();
        $employee = $user ? $user->employee : null;
        if (!$employee) return $this->error('Employee profile not found', 404);

        $wfh = WfhRequest::create(array_merge($request->all(), [
            'employee_id' => $employee->id,
            'status' => 'pending'
        ]));

        return $this->success($wfh, 'WFH request submitted successfully', 201);
    }
}
