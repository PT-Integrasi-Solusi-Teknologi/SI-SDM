<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Certification;
use App\Models\Competency;
use App\Models\CompetencyCategory;
use App\Models\Employee;
use App\Models\EmployeeCompetency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CompetencyController extends Controller
{
    public function index(Request $request): View
    {
        $categories = CompetencyCategory::with(['competencies.employeeCompetencies'])->get();

        $competencies = Competency::with(['category', 'employeeCompetencies'])->orderBy('name')->get();

        $query = EmployeeCompetency::with(['employee.department', 'employee.jobPosition', 'competency.category']);

        if ($compId = $request->input('competency_id')) {
            $query->where('competency_id', $compId);
        }

        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('employee', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $employeeCompetencies = $query->latest('assessed_date')->paginate(12)->withQueryString();

        $certifications = Certification::with('employee.department')
            ->latest('issue_date')
            ->take(15)
            ->get();

        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total_competencies' => Competency::count(),
            'total_assessments' => EmployeeCompetency::count(),
            'total_certifications' => Certification::count(),
            'mahir_count' => EmployeeCompetency::where('level', 'Mahir')->count(),
        ];

        return view('competencies.index', compact(
            'categories',
            'competencies',
            'employeeCompetencies',
            'certifications',
            'employees',
            'stats'
        ));
    }

    public function storeCompetency(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:competency_categories,id'],
            'code' => ['required', 'string', 'max:20', 'unique:competencies,code'],
            'name' => ['required', 'string', 'max:255'],
            'target_level' => ['required', 'in:Dasar,Menengah,Mahir'],
            'description' => ['nullable', 'string'],
        ]);

        $comp = Competency::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'CREATE',
            'module' => 'Kompetensi',
            'description' => "Menambahkan master kompetensi baru: {$comp->name} ({$comp->code}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Master kompetensi {$comp->name} berhasil ditambahkan!");
    }

    public function assignCompetency(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'competency_id' => ['required', 'exists:competencies,id'],
            'level' => ['required', 'in:Dasar,Menengah,Mahir'],
            'assessed_date' => ['required', 'date'],
            'assessor_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        EmployeeCompetency::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'competency_id' => $validated['competency_id'],
            ],
            [
                'level' => $validated['level'],
                'assessed_date' => $validated['assessed_date'],
                'assessor_name' => $validated['assessor_name'] ?? (Auth::user()?->name ?? 'Tim Asesor Asrama Haji'),
                'notes' => $validated['notes'],
            ]
        );

        $emp = Employee::find($validated['employee_id']);
        $comp = Competency::find($validated['competency_id']);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'UPDATE',
            'module' => 'Kompetensi',
            'description' => "Memetakan kompetensi {$comp->name} (Level {$validated['level']}) ke pegawai {$emp->name}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Penilaian kompetensi untuk {$emp->name} berhasil diperbarui!");
    }

    public function storeCertification(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'name' => ['required', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:100'],
            'issuer' => ['required', 'string', 'max:255'],
            'issue_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date', 'after:issue_date'],
        ]);

        $validated['status'] = 'active';
        Certification::create($validated);

        return back()->with('success', 'Data sertifikasi pegawai berhasil disimpan!');
    }
}
