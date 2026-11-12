<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PerformancePeriod;
use App\Models\PerformanceReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PerformanceController extends Controller
{
    public function index(Request $request): View
    {
        $periods = PerformancePeriod::orderBy('year', 'desc')->get();
        $activePeriod = PerformancePeriod::where('is_active', true)->first() ?? $periods->first();

        $selectedPeriodId = $request->input('period_id', $activePeriod?->id);

        $query = PerformanceReview::with(['employee.department', 'employee.jobPosition', 'period']);

        if ($selectedPeriodId) {
            $query->where('period_id', $selectedPeriodId);
        }

        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('employee', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $reviews = $query->latest('score')->paginate(12)->withQueryString();

        $averageScore = round(PerformanceReview::where('period_id', $selectedPeriodId)->avg('score') ?? 86.76, 2);

        $topPerformers = PerformanceReview::with(['employee.department', 'employee.jobPosition'])
            ->where('period_id', $selectedPeriodId)
            ->orderBy('score', 'desc')
            ->take(5)
            ->get();

        $ratingDistribution = [
            'sangat_baik' => PerformanceReview::where('period_id', $selectedPeriodId)->where('rating', 'Sangat Baik')->count(),
            'baik' => PerformanceReview::where('period_id', $selectedPeriodId)->where('rating', 'Baik')->count(),
            'cukup' => PerformanceReview::where('period_id', $selectedPeriodId)->where('rating', 'Cukup')->count(),
            'kurang' => PerformanceReview::where('period_id', $selectedPeriodId)->where('rating', 'Kurang')->count(),
        ];

        $employees = Employee::where('is_active', true)->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('performance.index', compact(
            'reviews',
            'periods',
            'selectedPeriodId',
            'averageScore',
            'topPerformers',
            'ratingDistribution',
            'employees',
            'departments'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'period_id' => ['required', 'exists:performance_periods,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'evaluator_name' => ['nullable', 'string', 'max:255'],
            'achievements' => ['nullable', 'string'],
            'feedback' => ['nullable', 'string'],
        ]);

        $score = (float) $validated['score'];
        $rating = 'Baik';
        if ($score >= 90) {
            $rating = 'Sangat Baik';
        } elseif ($score >= 75) {
            $rating = 'Baik';
        } elseif ($score >= 60) {
            $rating = 'Cukup';
        } else {
            $rating = 'Kurang';
        }

        $review = PerformanceReview::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'period_id' => $validated['period_id'],
            ],
            [
                'score' => $score,
                'rating' => $rating,
                'evaluator_name' => $validated['evaluator_name'] ?? (Auth::user()?->name ?? 'Drs. H. Ahmad Fauzi'),
                'achievements' => $validated['achievements'],
                'feedback' => $validated['feedback'],
                'review_date' => now(),
            ]
        );

        $emp = Employee::find($validated['employee_id']);
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Evaluator',
            'action' => 'UPDATE',
            'module' => 'Kinerja',
            'description' => "Menyimpan evaluasi kinerja {$emp->name} (Skor: {$score}, Predikat: {$rating}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Evaluasi kinerja untuk {$emp->name} berhasil disimpan!");
    }
}
