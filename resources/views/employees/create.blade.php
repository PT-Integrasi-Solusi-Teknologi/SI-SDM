@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('employees.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-emerald-600 transition shadow-2xs">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <a href="{{ route('employees.index') }}" class="hover:text-emerald-600">Data Pegawai</a>
                <span>/</span>
                <span class="text-slate-600 font-semibold">Form Tambah Pegawai Baru</span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 mt-0.5">Tambah Pegawai Baru</h2>
        </div>
    </div>

    <!-- Form Container -->
    <form action="{{ route('employees.store') }}" method="POST" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xs space-y-8">
        @csrf

        <!-- Section 1: Identitas Pribadi -->
        <div>
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 mb-5">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">1</div>
                <h3 class="text-sm font-bold text-slate-900">Identitas Pribadi Pegawai</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Muhammad Firdaus, S.E."
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="gender" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" placeholder="16 digit NIK KTP..."
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="Kota kelahiran..."
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="pegawai@asramahaji.go.id"
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62 812-..."
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Foto Profil / Avatar URL (Opsional)</label>
                    <input type="url" name="avatar" value="{{ old('avatar') }}" placeholder="https://images.unsplash.com/... atau kosongkan untuk avatar default"
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap Domisili</label>
                    <textarea name="address" rows="2" placeholder="Nama jalan, RT/RW, kelurahan, kecamatan, kota..."
                              class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: Informasi Kepegawaian & Penempatan -->
        <div>
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 mb-5">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">2</div>
                <h3 class="text-sm font-bold text-slate-900">Informasi Kepegawaian & Penempatan</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Internal Pegawai</label>
                    <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="Otomatis (contoh: AH-2026-052)"
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    <span class="text-[10px] text-slate-400 mt-1 block">Biarkan kosong untuk penomoran otomatis sistem</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIP (Nomor Identitas Pegawai)</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="18 digit NIP resmi (jika ASN)..."
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Kerja / Departemen <span class="text-rose-500">*</span></label>
                    <select name="department_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan / Posisi <span class="text-rose-500">*</span></label>
                    <select name="job_position_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('job_position_id') == $pos->id ? 'selected' : '' }}>
                                {{ $pos->title }} ({{ $pos->department?->name ?? 'Umum' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Pegawai <span class="text-rose-500">*</span></label>
                    <select name="employment_type_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($employmentTypes as $type)
                            <option value="{{ $type->id }}" {{ old('employment_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Kepegawaian <span class="text-rose-500">*</span></label>
                    <select name="employment_status_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($employmentStatuses as $status)
                            <option value="{{ $status->id }}" {{ old('employment_status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Model Kerja <span class="text-rose-500">*</span></label>
                    <select name="work_model" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="WFO" {{ old('work_model') === 'WFO' ? 'selected' : '' }}>WFO (Work From Office)</option>
                        <option value="Hybrid" {{ old('work_model') === 'Hybrid' ? 'selected' : '' }}>Hybrid (Kantor & Mobile)</option>
                        <option value="Shift" {{ old('work_model') === 'Shift' ? 'selected' : '' }}>Shift Layanan (24 Jam Asrama)</option>
                        <option value="Remote" {{ old('work_model') === 'Remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai Bekerja <span class="text-rose-500">*</span></label>
                    <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Gaji Pokok (Rp)</label>
                    <input type="number" name="base_salary" value="{{ old('base_salary', 6500000) }}" min="0" step="50000"
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Akhir Kontrak (Jika Berlaku)</label>
                    <input type="date" name="contract_end_date" value="{{ old('contract_end_date') }}"
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Internal / Riwayat Awal</label>
                    <textarea name="notes" rows="2" placeholder="Catatan kualifikasi, riwayat seleksi, atau penugasan khusus..."
                              class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('employees.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition shadow-xs flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan Pegawai Baru</span>
            </button>
        </div>
    </form>
</div>
@endsection

