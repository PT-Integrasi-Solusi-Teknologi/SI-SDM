<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\JobPosition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(): View
    {
        $departments = Department::with(['parent', 'children', 'jobPositions'])
            ->withCount('employees')
            ->orderBy('sort_order')
            ->get();

        $positions = JobPosition::with('department')
            ->withCount('employees')
            ->orderBy('department_id')
            ->get();

        $rootDepartments = Department::whereNull('parent_id')
            ->with(['children.children', 'children.employees', 'children.jobPositions', 'employees'])
            ->get();

        return view('organization.index', compact('departments', 'positions', 'rootDepartments'));
    }

    public function storeDepartment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:departments,code'],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:departments,id'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $dept = Department::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'CREATE',
            'module' => 'Organisasi',
            'description' => "Menambahkan unit kerja baru: {$dept->name} ({$dept->code}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Unit kerja {$dept->name} berhasil ditambahkan!");
    }

    public function updateDepartment(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:departments,code,'.$department->id],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:departments,id', 'different:id'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $department->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'UPDATE',
            'module' => 'Organisasi',
            'description' => "Memperbarui unit kerja: {$department->name}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Unit kerja {$department->name} berhasil diperbarui!");
    }

    public function destroyDepartment(Department $department): RedirectResponse
    {
        if ($department->employees()->count() > 0) {
            return back()->with('error', "Unit kerja {$department->name} tidak dapat dihapus karena masih memiliki {$department->employees()->count()} pegawai aktif.");
        }

        $name = $department->name;
        $department->delete();

        return back()->with('success', "Unit kerja {$name} berhasil dihapus.");
    }

    public function storePosition(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:job_positions,code'],
            'title' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'level' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $pos = JobPosition::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'CREATE',
            'module' => 'Organisasi',
            'description' => "Menambahkan master jabatan: {$pos->title} ({$pos->code}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Jabatan {$pos->title} berhasil ditambahkan!");
    }

    public function updatePosition(Request $request, JobPosition $position): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:job_positions,code,'.$position->id],
            'title' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'level' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $position->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'UPDATE',
            'module' => 'Organisasi',
            'description' => "Memperbarui master jabatan: {$position->title}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Jabatan {$position->title} berhasil diperbarui!");
    }

    public function destroyPosition(Request $request, JobPosition $position): RedirectResponse
    {
        if ($position->employees()->count() > 0) {
            return back()->with('error', "Jabatan {$position->title} tidak dapat dihapus karena masih digunakan oleh {$position->employees()->count()} pegawai aktif.");
        }

        $title = $position->title;
        $position->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'DELETE',
            'module' => 'Organisasi',
            'description' => "Menghapus master jabatan: {$title}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Jabatan {$title} berhasil dihapus.");
    }
}
