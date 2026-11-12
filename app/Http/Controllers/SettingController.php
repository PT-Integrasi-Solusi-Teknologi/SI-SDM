<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('employee')->orderBy('role')->get();

        $auditLogsQuery = AuditLog::latest();

        if ($module = $request->input('module')) {
            $auditLogsQuery->where('module', $module);
        }

        if ($action = $request->input('action')) {
            $auditLogsQuery->where('action', $action);
        }

        if ($search = $request->input('search')) {
            $auditLogsQuery->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $auditLogs = $auditLogsQuery->paginate(15)->withQueryString();

        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('settings.index', compact('users', 'auditLogs', 'employees'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:superadmin,admin_sdm,pimpinan,pegawai'],
            'employee_id' => ['nullable', 'exists:employees,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'employee_id' => $validated['employee_id'] ?? null,
            'is_active' => true,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin',
            'action' => 'CREATE',
            'module' => 'Pengaturan',
            'description' => "Menambahkan akun pengguna baru: {$user->name} ({$user->role}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Akun pengguna {$user->name} berhasil dibuat!");
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:superadmin,admin_sdm,pimpinan,pegawai'],
            'is_active' => ['boolean'],
        ]);

        $user->update($validated);

        return back()->with('success', "Pengaturan akun {$user->name} berhasil diperbarui.");
    }
}
