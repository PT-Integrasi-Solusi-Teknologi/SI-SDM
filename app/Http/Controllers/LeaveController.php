<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(Request $request): View
    {
        $query = LeaveRequest::with(['employee.department', 'leaveType']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($typeId = $request->input('leave_type_id')) {
            $query->where('leave_type_id', $typeId);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            })->orWhere('ticket_number', 'like', "%{$search}%");
        }

        $leaveRequests = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => LeaveRequest::count(),
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
        ];

        $leaveTypes = LeaveType::withCount('leaveRequests')->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('leaves.index', compact('leaveRequests', 'stats', 'leaveTypes', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:5'],
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $duration = $start->diffInDays($end) + 1;

        $ticket = sprintf('LV-%s-%04d', date('Y'), LeaveRequest::count() + 1);

        $leave = LeaveRequest::create([
            'ticket_number' => $ticket,
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_days' => $duration,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'User',
            'action' => 'CREATE',
            'module' => 'Cuti & Izin',
            'description' => "Pengajuan cuti baru {$ticket} ({$leave->employee->name}, durasi {$duration} hari).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Pengajuan cuti {$ticket} berhasil dikirimkan!");
    }

    public function approve(Request $request, LeaveRequest $leave): RedirectResponse
    {
        $validated = $request->validate([
            'approver_notes' => ['nullable', 'string'],
        ]);

        $leave->update([
            'status' => 'approved',
            'approver_name' => Auth::user()?->name ?? 'Dra. Endang Wulandari',
            'action_at' => now(),
            'approver_notes' => $validated['approver_notes'] ?? 'Disetujui oleh atasan.',
        ]);

        // Potong sisa kuota cuti
        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', date('Y'))
            ->first();

        if ($balance) {
            $balance->used_days += $leave->duration_days;
            $balance->remaining_days = max(0, $balance->allocated_days - $balance->used_days);
            $balance->save();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Approver',
            'action' => 'APPROVE',
            'module' => 'Cuti & Izin',
            'description' => "Menyetujui cuti {$leave->ticket_number} untuk {$leave->employee->name}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Pengajuan cuti {$leave->ticket_number} berhasil disetujui!");
    }

    public function reject(Request $request, LeaveRequest $leave): RedirectResponse
    {
        $validated = $request->validate([
            'approver_notes' => ['required', 'string', 'min:3'],
        ]);

        $leave->update([
            'status' => 'rejected',
            'approver_name' => Auth::user()?->name ?? 'Dra. Endang Wulandari',
            'action_at' => now(),
            'approver_notes' => $validated['approver_notes'],
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Approver',
            'action' => 'REJECT',
            'module' => 'Cuti & Izin',
            'description' => "Menolak cuti {$leave->ticket_number} untuk {$leave->employee->name}. Alasan: {$validated['approver_notes']}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('info', "Pengajuan cuti {$leave->ticket_number} telah ditolak.");
    }
}
