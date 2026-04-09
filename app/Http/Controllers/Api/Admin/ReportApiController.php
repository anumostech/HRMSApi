<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportApiController extends ApiController
{
    /**
     * Attendance Report Listing
     */
    public function attendanceReport(Request $request): JsonResponse
    {
        $dateRange = $request->get('date_range', 'today');
        $employeeId = $request->get('employee_id'); 
        $departmentId = $request->get('department_id');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        list($startDate, $endDate) = $this->getDateRange($dateRange, $request->get('from_date'), $request->get('to_date'));

        $employeesQuery = Employee::with(['user.company', 'user.department', 'user.designation'])
            ->where('status', 'active');

        if ($employeeId && $employeeId !== 'all') {
            $employeesQuery->where('employee_id', $employeeId);
        }

        if ($departmentId && $departmentId !== 'all') {
            $employeesQuery->whereHas('user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($search) {
            $employeesQuery->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $employees = $employeesQuery->get();
        $employeeIds = $employees->pluck('employee_id')->toArray();

        $allLogs = AttendanceLog::whereBetween('log_date', [$startDate, $endDate])
            ->whereIn('userid', $employeeIds)
            ->get()
            ->groupBy(['log_date', 'userid']);

        $reportData = [];
        $tempDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($tempDate <= $end) {
            $dateStr = $tempDate->toDateString();
            $dayLogs = $allLogs->get($dateStr, collect());

            foreach ($employees as $emp) {
                $empLogs = $dayLogs->get($emp->employee_id);
                $punchIn = $empLogs ? $empLogs->min('punch_in') : null;
                $punchOut = $empLogs ? $empLogs->max('punch_out') : null;
                
                $status = 'Absent';
                if ($punchIn) {
                    $time = Carbon::parse($punchIn)->format('H:i:s');
                    $status = ($time > '08:10:59' && $time <= '12:00:00') ? 'Late' : 'Present';
                }

                $reportData[] = [
                    'employee_id' => $emp->employee_id,
                    'name' => $emp->first_name . ' ' . $emp->last_name,
                    'department' => $emp->user->department->name ?? 'N/A',
                    'date' => $dateStr,
                    'punch_in' => $punchIn ? Carbon::parse($punchIn)->format('H:i') : '-',
                    'punch_out' => $punchOut ? Carbon::parse($punchOut)->format('H:i') : '-',
                    'status' => $status
                ];
            }
            $tempDate->addDay();
        }

        usort($reportData, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        $currentPage = request()->get('page', 1);
        $total = count($reportData);
        $paginatedItems = array_slice($reportData, ($currentPage - 1) * $perPage, $perPage);

        return $this->success([
            'data' => $paginatedItems,
            'meta' => [
                'total' => $total,
                'per_page' => (int)$perPage,
                'current_page' => (int)$currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ]);
    }

    /**
     * Leave Report Listing
     */
    public function leaveReport(Request $request): JsonResponse
    {
        $dateRange = $request->get('date_range', 'this_month');
        $employeeId = $request->get('employee_id');
        $departmentId = $request->get('department_id');
        $perPage = $request->get('per_page', 10);

        list($startDate, $endDate) = $this->getDateRange($dateRange, $request->get('from_date'), $request->get('to_date'));

        $query = LeaveRequest::with(['employee.user.department', 'leaveType'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
            });

        if ($employeeId && $employeeId !== 'all') {
            $query->whereHas('employee', function($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            });
        }

        if ($departmentId && $departmentId !== 'all') {
            $query->whereHas('employee.user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $leaves = $query->latest()->paginate($perPage);

        return $this->success($leaves);
    }

    /**
     * Employee Report Listing
     */
    public function employeeReport(Request $request): JsonResponse
    {
        $departmentId = $request->get('department_id');
        $companyId = $request->get('company_id');
        $perPage = $request->get('per_page', 10);

        $query = Employee::with(['user.department', 'user.designation', 'user.company']);

        if ($departmentId && $departmentId !== 'all') {
            $query->whereHas('user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($companyId && $companyId !== 'all') {
            $query->whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        $employees = $query->paginate($perPage);

        return $this->success($employees);
    }

    /**
     * Export Report
     */
    public function export(Request $request)
    {
        $reportType = $request->get('report_type'); 
        $dateRange = $request->get('date_range', 'today');
        $employeeId = $request->get('employee_id');
        $departmentId = $request->get('department_id');
        $format = strtolower($request->get('format', 'csv'));

        list($startDate, $endDate) = $this->getDateRange($dateRange, $request->get('from_date'), $request->get('to_date'));

        $data = [];
        $columns = [];
        
        switch ($reportType) {
            case 'attendance':
                $columns = ['Date', 'Employee ID', 'Name', 'Department', 'Punch In', 'Punch Out', 'Status'];
                $empQuery = Employee::where('status', 'active')->with(['user.department']);
                if ($employeeId && $employeeId !== 'all') $empQuery->where('employee_id', $employeeId);
                if ($departmentId && $departmentId !== 'all') $empQuery->whereHas('user', fn($q) => $q->where('department_id', $departmentId));
                $employees = $empQuery->get();
                $employeeIds = $employees->pluck('employee_id')->toArray();
                $allLogs = AttendanceLog::whereBetween('log_date', [$startDate, $endDate])->whereIn('userid', $employeeIds)->get()->groupBy(['log_date', 'userid']);
                $tempDate = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);
                while ($tempDate <= $end) {
                    $dateStr = $tempDate->toDateString();
                    $dayLogs = $allLogs->get($dateStr, collect());
                    foreach ($employees as $emp) {
                        $empLogs = $dayLogs->get($emp->employee_id);
                        $punchIn = $empLogs ? $empLogs->min('punch_in') : null;
                        $punchOut = $empLogs ? $empLogs->max('punch_out') : null;
                        $status = 'Absent';
                        if ($punchIn) {
                            $time = Carbon::parse($punchIn)->format('H:i:s');
                            $status = ($time > '08:10:59' && $time <= '12:00:00') ? 'Late' : 'Present';
                        }
                        $data[] = [$dateStr, $emp->employee_id, $emp->first_name . ' ' . $emp->last_name, $emp->user->department->name ?? 'N/A', $punchIn ? Carbon::parse($punchIn)->format('H:i') : '-', $punchOut ? Carbon::parse($punchOut)->format('H:i') : '-', $status];
                    }
                    $tempDate->addDay();
                }
                break;

            case 'leave':
                $columns = ['Employee ID', 'Name', 'Leave Type', 'From', 'To', 'Days', 'Status', 'Reason'];
                $leaveQuery = LeaveRequest::with(['employee', 'leaveType'])->where(function($q) use ($startDate, $endDate) { $q->whereBetween('start_date', [$startDate, $endDate])->orWhereBetween('end_date', [$startDate, $endDate]); });
                if ($employeeId && $employeeId !== 'all') $leaveQuery->whereHas('employee', fn($q) => $q->where('employee_id', $employeeId));
                if ($departmentId && $departmentId !== 'all') $leaveQuery->whereHas('employee.user', fn($q) => $q->where('department_id', $departmentId));
                $leaves = $leaveQuery->get();
                foreach ($leaves as $leave) {
                    $data[] = [$leave->employee->employee_id ?? 'N/A', ($leave->employee->first_name ?? '') . ' ' . ($leave->employee->last_name ?? ''), $leave->leaveType->name ?? 'N/A', $leave->start_date->toDateString(), $leave->end_date->toDateString(), $leave->duration_days, ucfirst($leave->status), $leave->reason];
                }
                break;

            case 'employee':
                $columns = ['Employee ID', 'Name', 'Company', 'Department', 'Designation', 'Joining Date', 'Status'];
                $empQuery = Employee::with(['user.company', 'user.department', 'user.designation']);
                if ($departmentId && $departmentId !== 'all') $empQuery->whereHas('user', fn($q) => $q->where('department_id', $departmentId));
                $employees = $empQuery->get();
                foreach ($employees as $emp) {
                    $data[] = [$emp->employee_id, $emp->first_name . ' ' . $emp->last_name, $emp->user->company->name ?? 'N/A', $emp->user->department->name ?? 'N/A', $emp->user->designation->name ?? 'N/A', $emp->joining_date, ucfirst($emp->status)];
                }
                break;
        }

        if ($format === 'excel') {
            return $this->downloadExcel("report_{$reportType}_" . now()->format('YmdHis') . ".xls", $columns, $data);
        }

        return $this->downloadCsv("report_{$reportType}_" . now()->format('YmdHis') . ".csv", $columns, $data);
    }

    /**
     * Helper: Get Date Range Group
     */
    private function getDateRange($preset, $from = null, $to = null)
    {
        $now = Carbon::now();
        switch ($preset) {
            case 'today': return [$now->toDateString(), $now->toDateString()];
            case 'yesterday': $y = $now->subDay()->toDateString(); return [$y, $y];
            case 'this_week': return [$now->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()];
            case 'this_month': return [$now->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()];
            case 'custom': if ($from && $to) { return [Carbon::parse($from)->toDateString(), Carbon::parse($to)->toDateString()]; } return [$now->toDateString(), $now->toDateString()];
            default: return [$now->toDateString(), $now->toDateString()];
        }
    }

    /**
     * Helper: Download CSV
     */
    private function downloadCsv($filename, $columns, $data)
    {
        $headers = ['Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename", 'Expires' => '0', 'Pragma' => 'public'];
        $callback = function() use ($columns, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($data as $row) { fputcsv($file, $row); }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper: Download Excel (HTML Fallback)
     */
    private function downloadExcel($filename, $columns, $data)
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=$filename",
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function() use ($columns, $data) {
            echo '<table border="1">';
            echo '<tr>';
            foreach ($columns as $column) { echo '<th style="background-color: #f2f2f2;">' . $column . '</th>'; }
            echo '</tr>';
            foreach ($data as $row) {
                echo '<tr>';
                foreach ($row as $cell) { echo '<td>' . $cell . '</td>'; }
                echo '</tr>';
            }
            echo '</table>';
        };

        return response()->stream($callback, 200, $headers);
    }
}
