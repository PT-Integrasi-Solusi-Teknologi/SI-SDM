@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ modalUpload: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Pusat Arsip Dokumen Kepegawaian</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pengelolaan berkas digital SK, perjanjian kerja, ijazah, dan sertifikat kompetensi terlindungi.</p>
        </div>
        <button @click="modalUpload = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
            <i data-lucide="upload" class="w-4 h-4"></i>
            <span>Unggah Dokumen Baru</span>
        </button>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs">
        <form action="{{ route('documents.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Cari Dokumen</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, nomor berkas, nama pegawai..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Jenis Dokumen</label>
                <select name="document_type" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    <option value="">Semua Kategori</option>
                    @foreach($documentTypes as $dt)
                        <option value="{{ $dt }}" {{ request('document_type') == $dt ? 'selected' : '' }}>{{ $dt }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Pilih Pegawai</label>
                <select name="employee_id" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    <option value="">Semua Pegawai</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">Terapkan</button>
                <a href="{{ route('documents.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold">Reset</a>
            </div>
        </form>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($documents as $doc)
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                            {{ $doc->document_type }}
                        </span>
                    </div>

                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm leading-snug line-clamp-2">
                        {{ $doc->title }}
                    </h4>
                    
                    @if($doc->document_number)
                        <span class="text-[10px] font-mono font-bold text-emerald-800 bg-emerald-50/70 px-1.5 py-0.5 rounded inline-block mt-1.5">
                            {{ $doc->document_number }}
                        </span>
                    @endif

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex items-center justify-between">
                            <span>Pemilik:</span>
                            <a href="{{ route('employees.show', $doc->employee) }}" class="font-bold text-slate-800 hover:text-emerald-600 truncate max-w-[150px]">
                                {{ $doc->employee->name }}
                            </a>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Ukuran Berkas:</span>
                            <span class="font-medium text-slate-600">{{ $doc->file_size ?? '1.2 MB' }} (PDF)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Diunggah:</span>
                            <span class="text-slate-400">{{ $doc->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2">
                    <a href="{{ route('documents.download', $doc) }}" class="flex-1 py-2 text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-2xs">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span>Unduh Dokumen</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white p-12 rounded-3xl border border-slate-100 text-center text-slate-400">
                <i data-lucide="folder-search" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                <p class="text-xs">Tidak ada dokumen yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    @if($documents->hasPages())
        <div class="p-4 bg-white rounded-3xl border border-slate-100 flex justify-between items-center">
            {{ $documents->links() }}
        </div>
    @endif

    <!-- MODAL: UPLOAD DOKUMEN -->
    <div x-show="modalUpload" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalUpload = false">
            <h3 class="text-sm font-bold text-slate-900">Unggah Dokumen Kepegawaian</h3>
            <form action="{{ route('documents.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Pegawai Terkait</label>
                    <select name="employee_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Dokumen</label>
                    <select name="document_type" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($documentTypes as $dt)
                            <option value="{{ $dt }}">{{ $dt }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dokumen</label>
                    <input type="text" name="title" placeholder="Contoh: SK Pengangkatan PPPK 2026" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Surat / Berkas</label>
                    <input type="text" name="document_number" placeholder="Contoh: SK/UPT-AH/2026/045" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Simulasi File (PDF / Gambar)</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center bg-slate-50">
                        <i data-lucide="upload-cloud" class="w-6 h-6 mx-auto text-emerald-600 mb-1"></i>
                        <span class="text-xs text-slate-500 font-medium block">Klik atau seret file PDF / scan dokumen ke sini</span>
                        <span class="text-[10px] text-slate-400">Maksimal 5 MB &bull; Enkripsi otomatis</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalUpload = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

