<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Certification;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeCompetency;
use App\Models\EmploymentType;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount([
            'employees',
            'employees as asn_count' => fn ($q) => $q->whereHas('employmentType', fn ($t) => $t->where('code', 'ASN')),
            'employees as pppk_count' => fn ($q) => $q->whereHas('employmentType', fn ($t) => $t->where('code', 'PPPK')),
            'employees as kontrak_count' => fn ($q) => $q->whereHas('employmentType', fn ($t) => $t->where('code', 'KONTRAK')),
            'employees as outsourcing_count' => fn ($q) => $q->whereHas('employmentType', fn ($t) => $t->where('code', 'OUTSOURCING')),
        ])->get();

        $employmentTypes = EmploymentType::withCount('employees')->get();

        $leaveReport = LeaveRequest::with('leaveType')
            ->selectRaw('leave_type_id, count(*) as count, sum(duration_days) as total_days')
            ->where('status', 'approved')
            ->groupBy('leave_type_id')
            ->get();

        $certificationsCount = Certification::count();
        $competenciesCount = EmployeeCompetency::count();

        $attendanceStats = [
            'hadir' => AttendanceRecord::where('status', 'hadir')->count(),
            'terlambat' => AttendanceRecord::where('status', 'terlambat')->count(),
            'izin' => AttendanceRecord::where('status', 'izin')->count(),
            'sakit' => AttendanceRecord::where('status', 'sakit')->count(),
            'dinas_luar' => AttendanceRecord::where('status', 'dinas_luar')->count(),
        ];

        return view('reports.index', compact(
            'departments',
            'employmentTypes',
            'leaveReport',
            'certificationsCount',
            'competenciesCount',
            'attendanceStats'
        ));
    }

    public function printView(Request $request): View
    {
        $reportType = $request->input('type', 'rekap_sdm');

        $query = Employee::with(['department', 'jobPosition', 'employmentType', 'employmentStatus']);

        if ($empId = $request->input('employee_id')) {
            $query->where('id', $empId);
        }

        $employees = $query->orderBy('department_id')->get();

        $departments = Department::withCount('employees')->get();

        return view('reports.print', compact('employees', 'departments', 'reportType'));
    }
}
