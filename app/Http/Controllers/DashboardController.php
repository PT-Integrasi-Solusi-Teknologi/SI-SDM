<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentType;
use App\Models\LeaveRequest;
use App\Models\PerformanceReview;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('is_active', true)->count();
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();
        $averagePerformance = round(PerformanceReview::avg('score') ?? 86.76, 1);

        $departmentStats = Department::withCount('employees')
            ->orderBy('employees_count', 'desc')
            ->get();

        $employmentTypeStats = EmploymentType::withCount('employees')->get();

        $today = Carbon::today()->format('Y-m-d');
        $todayAttendance = [
            'hadir' => AttendanceRecord::where('date', $today)->where('status', 'hadir')->count(),
            'terlambat' => AttendanceRecord::where('date', $today)->where('status', 'terlambat')->count(),
            'izin' => AttendanceRecord::where('date', $today)->whereIn('status', ['izin', 'sakit'])->count(),
            'dinas_luar' => AttendanceRecord::where('date', $today)->where('status', 'dinas_luar')->count(),
        ];

        // Fallback jika hari ini libur, ambil dari tanggal terakhir record ada
        if (array_sum($todayAttendance) === 0) {
            $latestDate = AttendanceRecord::max('date') ?? $today;
            $todayAttendance = [
                'hadir' => AttendanceRecord::where('date', $latestDate)->where('status', 'hadir')->count(),
                'terlambat' => AttendanceRecord::where('date', $latestDate)->where('status', 'terlambat')->count(),
                'izin' => AttendanceRecord::where('date', $latestDate)->whereIn('status', ['izin', 'sakit'])->count(),
                'dinas_luar' => AttendanceRecord::where('date', $latestDate)->where('status', 'dinas_luar')->count(),
            ];
        }

        $pendingLeaveRequests = LeaveRequest::with(['employee.department', 'leaveType'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = AuditLog::latest()->take(6)->get();

        $topPerformers = Employee::with(['department', 'jobPosition', 'performanceReviews'])
            ->get()
            ->filter(fn ($emp) => $emp->latest_performance_score !== null)
            ->sortByDesc('latest_performance_score')
            ->take(5)
            ->values();

        $featuredEmployee = Employee::with([
            'department',
            'jobPosition',
            'employmentType',
            'employmentStatus',
            'leaveBalances.leaveType',
            'documents',
            'performanceReviews',
            'assignments',
        ])->first();

        return view('dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'pendingLeaves',
            'averagePerformance',
            'departmentStats',
            'employmentTypeStats',
            'todayAttendance',
            'pendingLeaveRequests',
            'recentActivities',
            'topPerformers',
            'featuredEmployee'
        ));
    }
}
