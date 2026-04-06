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
        $employee = auth('employee_api')->user();
        if (!$employee) return $this->error('Employee not found', 404);

        $today = Carbon::today()->toDateString();
        $attendance = AttendanceLog::where('userid', $employee->id)
            ->whereDate('log_date', $today)
            ->first();

        $stats = [
            'punched_in' => (bool)$attendance,
            'punched_out' => $attendance && $attendance->punch_out,
            'punch_in_time' => $attendance ? $attendance->punch_in : null,
            'punch_out_time' => $attendance ? $attendance->punch_out : null,
            'recent_leaves' => LeaveRequest::where('employee_id', $employee->id)->latest()->take(5)->get(),
            'pending_wfh' => WfhRequest::where('employee_id', $employee->id)->where('status', 'pending')->count(),
        ];

        return $this->success($stats);
    }

    /**
     * Punch In
     */
    public function punchIn(): JsonResponse
    {
        $employee = auth('employee_api')->user();
        $today = Carbon::today()->toDateString();

        $alreadyPunched = AttendanceLog::where('userid', $employee->id)
            ->whereDate('log_date', $today)
            ->exists();

        if ($alreadyPunched) {
            return $this->error('Already punched in today.', 400);
        }

        $log = AttendanceLog::create([
            'company_id' => $employee->company_id ?? 1,
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

        $employee = auth('employee_api')->user();
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
        $employee = auth('employee_api')->user();
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

        $employee = auth('employee_api')->user();
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
        $employee = auth('employee_api')->user();
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

        $employee = auth('employee_api')->user();
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
        $employee = auth('employee_api')->user();
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

        $employee = auth('employee_api')->user();
        $wfh = WfhRequest::create(array_merge($request->all(), [
            'employee_id' => $employee->id,
            'status' => 'pending'
        ]));

        return $this->success($wfh, 'WFH request submitted successfully', 201);
    }
}
