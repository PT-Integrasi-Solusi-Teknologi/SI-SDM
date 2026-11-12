<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = EmployeeDocument::with(['employee.department']);

        if ($type = $request->input('document_type')) {
            $query->where('document_type', $type);
        }

        if ($empId = $request->input('employee_id')) {
            $query->where('employee_id', $empId);
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('document_number', 'like', "%{$search}%")
                ->orWhereHas('employee', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $documents = $query->latest()->paginate(12)->withQueryString();

        $documentTypes = [
            'SK Pengangkatan',
            'Kontrak Kerja',
            'Ijazah',
            'KTP',
            'Sertifikat',
            'Portofolio',
            'Evaluasi Kinerja',
        ];

        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('documents.index', compact('documents', 'documentTypes', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'document_type' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['file_path'] = 'documents/'.uniqid('doc_').'.pdf';
        $validated['file_size'] = rand(500, 2500).' KB';
        $validated['file_type'] = 'pdf';

        $doc = EmployeeDocument::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'Admin SDM',
            'action' => 'CREATE',
            'module' => 'Dokumen',
            'description' => "Mengunggah dokumen kepegawaian: {$doc->title} untuk {$doc->employee->name}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Dokumen {$doc->title} berhasil disimpan!");
    }

    public function download(EmployeeDocument $document): RedirectResponse
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'User',
            'action' => 'DOWNLOAD',
            'module' => 'Dokumen',
            'description' => "Mengunduh arsip dokumen: {$document->title}.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', "Memulai unduhan dokumen '{$document->title}' (Simulasi unduhan aman).");
    }
}
