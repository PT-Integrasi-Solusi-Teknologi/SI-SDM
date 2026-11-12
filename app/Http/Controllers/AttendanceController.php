<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        // Cek jika pada tanggal ini tidak ada catatan sama sekali, cari tanggal terdekat yang ada data
        if (! AttendanceRecord::where('date', $date)->exists()) {
            $latestWithData = AttendanceRecord::max('date');
            if ($latestWithData) {
                $date = $latestWithData;
            }
        }

        $query = AttendanceRecord::with(['employee.department', 'employee.jobPosition'])
            ->where('date', $date);

        if ($deptId = $request->input('department_id')) {
            $query->whereHas('employee', fn ($q) => $q->where('department_id', $deptId));
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $records = $query->paginate(15)->withQueryString();

        $stats = [
            'hadir' => AttendanceRecord::where('date', $date)->where('status', 'hadir')->count(),
            'terlambat' => AttendanceRecord::where('date', $date)->where('status', 'terlambat')->count(),
            'izin' => AttendanceRecord::where('date', $date)->whereIn('status', ['izin', 'sakit'])->count(),
            'dinas_luar' => AttendanceRecord::where('date', $date)->where('status', 'dinas_luar')->count(),
        ];

        $departments = Department::orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('attendance.index', compact('records', 'stats', 'date', 'departments', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'clock_in' => ['nullable', 'date_format:H:i'],
            'clock_out' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:hadir,terlambat,izin,sakit,dinas_luar,alpa'],
            'notes' => ['nullable', 'string'],
        ]);

        $hours = 8.0;
        if (! empty($validated['clock_in']) && ! empty($validated['clock_out'])) {
            $in = Carbon::createFromFormat('H:i', $validated['clock_in']);
            $out = Carbon::createFromFormat('H:i', $validated['clock_out']);
            $hours = round($in->diffInMinutes($out) / 60, 2);
        } elseif ($validated['status'] === 'terlambat') {
            $hours = 7.5;
        } elseif (in_array($validated['status'], ['izin', 'sakit', 'alpa'])) {
            $hours = 0.0;
        }

        AttendanceRecord::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date' => $validated['date'],
            ],
            [
                'clock_in' => $validated['clock_in'] ? $validated['clock_in'].':00' : null,
                'clock_out' => $validated['clock_out'] ? $validated['clock_out'].':00' : null,
                'work_hours' => $hours,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]
        );

        $emp = Employee::find($validated['employee_id']);
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'UPDATE',
            'module' => 'Kehadiran',
            'description' => "Mencatat absensi {$emp->name} tanggal {$validated['date']} (Status: {$validated['status']}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Catatan kehadiran untuk {$emp->name} berhasil disimpan!");
    }
}
