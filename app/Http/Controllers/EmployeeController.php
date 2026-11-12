<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use App\Models\JobPosition;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with(['department', 'jobPosition', 'employmentType', 'employmentStatus']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($deptId = $request->input('department_id')) {
            $query->where('department_id', $deptId);
        }

        if ($typeId = $request->input('employment_type_id')) {
            $query->where('employment_type_id', $typeId);
        }

        if ($statusId = $request->input('employment_status_id')) {
            $query->where('employment_status_id', $statusId);
        }

        if ($model = $request->input('work_model')) {
            $query->where('work_model', $model);
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        if (in_array($sort, ['name', 'employee_code', 'join_date', 'created_at'])) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $employees = $query->paginate(12)->withQueryString();

        $departments = Department::orderBy('name')->get();
        $employmentTypes = EmploymentType::all();
        $employmentStatuses = EmploymentStatus::all();

        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('is_active', true)->count(),
            'asn' => Employee::whereHas('employmentType', fn ($q) => $q->where('code', 'ASN'))->count(),
            'pppk' => Employee::whereHas('employmentType', fn ($q) => $q->where('code', 'PPPK'))->count(),
            'kontrak' => Employee::whereHas('employmentType', fn ($q) => $q->whereIn('code', ['KONTRAK', 'OUTSOURCING']))->count(),
        ];

        return view('employees.index', compact(
            'employees',
            'departments',
            'employmentTypes',
            'employmentStatuses',
            'stats'
        ));
    }

    public function create(): View
    {
        $departments = Department::with('jobPositions')->orderBy('name')->get();
        $positions = JobPosition::orderBy('title')->get();
        $employmentTypes = EmploymentType::all();
        $employmentStatuses = EmploymentStatus::all();

        return view('employees.create', compact('departments', 'positions', 'employmentTypes', 'employmentStatuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:employees,employee_code'],
            'nik' => ['nullable', 'string', 'max:20'],
            'nip' => ['nullable', 'string', 'max:30'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'job_position_id' => ['required', 'exists:job_positions,id'],
            'employment_type_id' => ['required', 'exists:employment_types,id'],
            'employment_status_id' => ['required', 'exists:employment_statuses,id'],
            'work_model' => ['required', 'in:WFO,Hybrid,Remote,Shift'],
            'join_date' => ['required', 'date'],
            'contract_end_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'base_salary' => ['nullable', 'numeric', 'min:0'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
        ]);

        if (empty($validated['employee_code'])) {
            $latestId = Employee::max('id') ?? 0;
            $validated['employee_code'] = sprintf('AH-%s-%03d', date('Y'), $latestId + 1);
        }

        if (empty($validated['avatar'])) {
            $validated['avatar'] = $validated['gender'] === 'Perempuan'
                ? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&fit=crop&crop=faces'
                : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&fit=crop&crop=faces';
        }

        $validated['base_salary'] = $validated['base_salary'] ?? 5500000;
        $validated['is_active'] = true;

        $employee = Employee::create($validated);

        // Buat Catatan Penempatan Awal
        EmployeeAssignment::create([
            'employee_id' => $employee->id,
            'department_id' => $employee->department_id,
            'job_position_id' => $employee->job_position_id,
            'assignment_type' => 'Penempatan Awal',
            'start_date' => $employee->join_date,
            'sk_number' => 'SK/UPT-AH/'.date('Y').'/'.sprintf('%03d', $employee->id),
            'sk_date' => $employee->join_date,
            'notes' => 'Surat Keputusan Pengangkatan & Penempatan Pertama',
            'created_by' => Auth::user()?->name ?? 'Admin SDM',
        ]);

        // Buat Alokasi Kuota Cuti
        $leaveTypes = LeaveType::all();
        foreach ($leaveTypes as $lt) {
            LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type_id' => $lt->id,
                'year' => (int) date('Y'),
                'allocated_days' => $lt->default_quota,
                'used_days' => 0,
                'remaining_days' => $lt->default_quota,
            ]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'CREATE',
            'module' => 'Pegawai',
            'description' => "Menambahkan pegawai baru: {$employee->name} ({$employee->employee_code}).",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('employees.show', $employee)
            ->with('success', "Pegawai {$employee->name} berhasil ditambahkan!");
    }

    public function show(Employee $employee): View
    {
        $employee->load([
            'department',
            'jobPosition',
            'employmentType',
            'employmentStatus',
            'assignments.department',
            'assignments.jobPosition',
            'competencies.competency.category',
            'certifications',
            'documents',
            'leaveBalances.leaveType',
            'leaveRequests.leaveType',
            'attendanceRecords' => fn ($q) => $q->latest('date')->take(10),
            'performanceReviews.period',
        ]);

        // Hitung akumulasi cuti untuk widget circular gauges (mirip desain referensi)
        $allLeavesQuota = $employee->leaveBalances->sum('allocated_days') ?: 20;
        $allLeavesUsed = $employee->leaveBalances->sum('used_days') ?: 0;

        $annualBalance = $employee->leaveBalances->firstWhere('leaveType.code', 'TAHUNAN');
        $annualQuota = $annualBalance?->allocated_days ?: 12;
        $annualUsed = $annualBalance?->used_days ?: 0;

        $casualBalance = $employee->leaveBalances->firstWhere('leaveType.code', 'PENTING');
        $casualQuota = $casualBalance?->allocated_days ?: 10;
        $casualUsed = $casualBalance?->used_days ?: 0;

        $sickBalance = $employee->leaveBalances->firstWhere('leaveType.code', 'SAKIT');
        $sickQuota = $sickBalance?->allocated_days ?: 14;
        $sickUsed = $sickBalance?->used_days ?: 0;

        // Jam kerja 7 hari terakhir (Hours Logged)
        $recentAttendance = $employee->attendanceRecords->take(7);
        $totalHoursLogged = $recentAttendance->sum('work_hours') ?: 34.30;

        $departments = Department::orderBy('name')->get();
        $positions = JobPosition::orderBy('title')->get();

        return view('employees.show', compact(
            'employee',
            'allLeavesQuota',
            'allLeavesUsed',
            'annualQuota',
            'annualUsed',
            'casualQuota',
            'casualUsed',
            'sickQuota',
            'sickUsed',
            'totalHoursLogged',
            'recentAttendance',
            'departments',
            'positions'
        ));
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('name')->get();
        $positions = JobPosition::orderBy('title')->get();
        $employmentTypes = EmploymentType::all();
        $employmentStatuses = EmploymentStatus::all();

        return view('employees.edit', compact('employee', 'departments', 'positions', 'employmentTypes', 'employmentStatuses'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code,'.$employee->id],
            'nik' => ['nullable', 'string', 'max:20'],
            'nip' => ['nullable', 'string', 'max:30'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'job_position_id' => ['required', 'exists:job_positions,id'],
            'employment_type_id' => ['required', 'exists:employment_types,id'],
            'employment_status_id' => ['required', 'exists:employment_statuses,id'],
            'work_model' => ['required', 'in:WFO,Hybrid,Remote,Shift'],
            'join_date' => ['required', 'date'],
            'contract_end_date' => ['nullable', 'date'],
            'base_salary' => ['nullable', 'numeric', 'min:0'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $deptChanged = ($employee->department_id != $validated['department_id']);
        $posChanged = ($employee->job_position_id != $validated['job_position_id']);

        $employee->update($validated);

        // Jika terjadi mutasi atau promosi, buat record riwayat baru tanpa menghapus riwayat lama!
        if ($deptChanged || $posChanged) {
            EmployeeAssignment::create([
                'employee_id' => $employee->id,
                'department_id' => $employee->department_id,
                'job_position_id' => $employee->job_position_id,
                'assignment_type' => $deptChanged ? 'Mutasi Unit Kerja' : 'Perubahan Jabatan / Promosi',
                'start_date' => Carbon::today()->format('Y-m-d'),
                'sk_number' => 'SK/MUTASI/'.date('Y').'/'.sprintf('%03d', $employee->id),
                'sk_date' => Carbon::today()->format('Y-m-d'),
                'notes' => 'Pembaruan penempatan melalui sistem informasi kepegawaian',
                'created_by' => Auth::user()?->name ?? 'Admin SDM',
            ]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'UPDATE',
            'module' => 'Pegawai',
            'description' => "Memperbarui data pegawai: {$employee->name} ({$employee->employee_code}).",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('employees.show', $employee)
            ->with('success', "Data pegawai {$employee->name} berhasil diperbarui!");
    }

    public function destroy(Request $request, Employee $employee): RedirectResponse
    {
        $name = $employee->name;
        $employee->is_active = false;
        $employee->save();
        $employee->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'DELETE',
            'module' => 'Pegawai',
            'description' => "Mengarsipkan pegawai: {$name} ({$employee->employee_code}).",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('employees.index')
            ->with('success', "Pegawai {$name} berhasil diarsipkan (soft delete).");
    }

    public function updateNotes(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['required', 'string'],
        ]);

        $employee->update(['notes' => $validated['notes']]);

        return back()->with('success', 'Catatan internal pegawai berhasil diperbarui.');
    }

    public function storeAssignment(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'job_position_id' => ['required', 'exists:job_positions,id'],
            'assignment_type' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'sk_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['employee_id'] = $employee->id;
        $validated['created_by'] = Auth::user()?->name ?? 'Admin SDM';

        EmployeeAssignment::create($validated);

        // Update current placement
        $employee->update([
            'department_id' => $validated['department_id'],
            'job_position_id' => $validated['job_position_id'],
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'UPDATE',
            'module' => 'Penempatan',
            'description' => "Menambahkan riwayat penempatan baru ({$validated['assignment_type']}) untuk {$employee->name}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Riwayat penempatan pegawai berhasil ditambahkan!');
    }
}
