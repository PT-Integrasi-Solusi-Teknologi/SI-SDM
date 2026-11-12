@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ tab: 'mapping', modalMaster: false, modalAssign: false, modalCert: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Kompetensi, Skill & Sertifikasi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pemetaan standar keahlian pegawai asrama haji dan sertifikasi profesi terakreditasi.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="modalAssign = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="award" class="w-4 h-4"></i>
                <span>Petakan Asesmen Pegawai</span>
            </button>
            <button @click="modalMaster = true" class="px-3.5 py-2 bg-white border border-slate-200 hover:border-emerald-300 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Master Kompetensi</span>
            </button>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase">Standar Kompetensi</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_competencies'] }}</p>
            <span class="text-[10px] text-slate-400">Dalam 5 Kategori Utama</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-emerald-600 uppercase">Asesmen Terdata</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-800 mt-1">{{ $stats['total_assessments'] }}</p>
            <span class="text-[10px] text-emerald-600">Hasil uji kompetensi BKN/Kemenag</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-blue-600 uppercase">Level Mahir (Expert)</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-blue-800 mt-1">{{ $stats['mahir_count'] }}</p>
            <span class="text-[10px] text-blue-600">Kualifikasi instruktur & koordinator</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-teal-600 uppercase">Sertifikat Resmi</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-teal-800 mt-1">{{ $stats['total_certifications'] }}</p>
            <span class="text-[10px] text-teal-600">BNSP / Kemenaker / LKPP</span>
        </div>
    </div>

    <!-- Tab Selector -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
        <button @click="tab = 'mapping'" :class="tab === 'mapping' ? 'bg-emerald-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 font-medium'" class="px-4 py-2 text-xs rounded-xl transition shadow-2xs">
            Pemetaan Kompetensi Pegawai
        </button>
        <button @click="tab = 'master'" :class="tab === 'master' ? 'bg-emerald-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 font-medium'" class="px-4 py-2 text-xs rounded-xl transition shadow-2xs">
            Kamus / Master Kompetensi ({{ $competencies->count() }})
        </button>
        <button @click="tab = 'certifications'" :class="tab === 'certifications' ? 'bg-emerald-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 font-medium'" class="px-4 py-2 text-xs rounded-xl transition shadow-2xs">
            Daftar Sertifikasi Resmi ({{ $certifications->count() }})
        </button>
    </div>

    <!-- TAB 1: PEMETAAN KOMPETENSI PEGAWAI -->
    <div x-show="tab === 'mapping'" class="space-y-4">
        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs">
            <form action="{{ route('competencies.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Cari Pegawai</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama pegawai..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Kompetensi</label>
                    <select name="competency_id" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="">Semua Kompetensi</option>
                        @foreach($competencies as $c)
                            <option value="{{ $c->id }}" {{ request('competency_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Level Kemampuan</label>
                    <select name="level" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="">Semua Level</option>
                        <option value="Mahir" {{ request('level') === 'Mahir' ? 'selected' : '' }}>Mahir (Expert)</option>
                        <option value="Menengah" {{ request('level') === 'Menengah' ? 'selected' : '' }}>Menengah (Proficient)</option>
                        <option value="Dasar" {{ request('level') === 'Dasar' ? 'selected' : '' }}>Dasar (Basic)</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">Filter</button>
                    <a href="{{ route('competencies.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table Mapping -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                            <th class="py-3 px-4">Pegawai</th>
                            <th class="py-3 px-4">Kompetensi & Kategori</th>
                            <th class="py-3 px-4">Level Aktual</th>
                            <th class="py-3 px-4">Target Jabatan</th>
                            <th class="py-3 px-4">Asesor & Tanggal</th>
                            <th class="py-3 px-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($employeeCompetencies as $ec)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs shrink-0">
                                            {{ $ec->employee->initials }}
                                        </div>
                                        <div>
                                            <a href="{{ route('employees.show', $ec->employee) }}" class="font-bold text-slate-900 hover:text-emerald-600 block">
                                                {{ $ec->employee->name }}
                                            </a>
                                            <span class="text-[10px] text-slate-400">{{ $ec->employee->department?->name }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900 block">{{ $ec->competency->name }}</span>
                                    <span class="text-[10px] text-emerald-700 font-medium">{{ $ec->competency->category?->name }}</span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $ec->level === 'Mahir' ? 'bg-emerald-100 text-emerald-800' : ($ec->level === 'Menengah' ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-700') }}">
                                        {{ $ec->level }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 font-medium text-slate-600">
                                    {{ $ec->competency->target_level }}
                                </td>

                                <td class="py-3.5 px-4 text-slate-500">
                                    <span class="block font-semibold text-slate-700">{{ $ec->assessor_name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $ec->assessed_date ? date('d M Y', strtotime($ec->assessed_date)) : '-' }}</span>
                                </td>

                                <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate">
                                    {{ $ec->notes ?? 'Kompeten sesuai standar operasional.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <p class="text-xs">Tidak ditemukan data pemetaan kompetensi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employeeCompetencies->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $employeeCompetencies->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 2: MASTER / KAMUS KOMPETENSI -->
    <div x-show="tab === 'master'" x-cloak class="space-y-6">
        @foreach($categories as $cat)
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900">{{ $cat->name }}</h4>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $cat->description }}</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                        {{ $cat->competencies->count() }} Standar
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($cat->competencies as $comp)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-100/60 px-1.5 py-0.2 rounded">{{ $comp->code }}</span>
                                    <span class="text-[10px] font-semibold text-slate-500">Target: <strong class="text-slate-800">{{ $comp->target_level }}</strong></span>
                                </div>
                                <h5 class="text-xs font-bold text-slate-900 leading-snug">{{ $comp->name }}</h5>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ $comp->description ?? 'Standar keterampilan operasional dalam mendukung layanan asrama haji.' }}</p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400">Dimiliki oleh:</span>
                                <span class="font-bold text-emerald-700">{{ $comp->employeeCompetencies->count() }} Pegawai</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- TAB 3: DAFTAR SERTIFIKASI RESMI -->
    <div x-show="tab === 'certifications'" x-cloak class="space-y-4">
        <div class="flex justify-end">
            <button @click="modalCert = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Sertifikasi Pegawai</span>
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Pegawai</th>
                        <th class="py-3 px-4">Nama Sertifikasi</th>
                        <th class="py-3 px-4">Lembaga Penerbit</th>
                        <th class="py-3 px-4">Nomor Sertifikat</th>
                        <th class="py-3 px-4">Masa Berlaku</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($certifications as $cert)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4">
                                <a href="{{ route('employees.show', $cert->employee) }}" class="font-bold text-slate-900 hover:text-emerald-600 block">
                                    {{ $cert->employee->name }}
                                </a>
                                <span class="text-[10px] text-slate-400">{{ $cert->employee->department?->name }}</span>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $cert->name }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $cert->issuer }}</td>
                            <td class="py-3 px-4 font-mono text-slate-700">{{ $cert->certificate_number ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $cert->expiry_date ? date('d M Y', strtotime($cert->expiry_date)) : 'Seumur Hidup' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: ASESMEN KOMPETENSI PEGAWAI -->
    <div x-show="modalAssign" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalAssign = false">
            <h3 class="text-sm font-bold text-slate-900">Petakan Asesmen Kompetensi Pegawai</h3>
            <form action="{{ route('competencies.assign') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pegawai</label>
                    <select name="employee_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kompetensi yang Dinilai</label>
                    <select name="competency_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($competencies as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->category?->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Capaian Level Aktual</label>
                    <select name="level" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="Dasar">Dasar (Basic / Pemula)</option>
                        <option value="Menengah" selected>Menengah (Proficient / Terampil)</option>
                        <option value="Mahir">Mahir (Expert / Instruktur)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Asesmen</label>
                        <input type="date" name="assessed_date" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Asesor</label>
                        <input type="text" name="assessor_name" placeholder="Tim Asesor Balai..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalAssign = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Hasil Asesmen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: TAMBAH MASTER KOMPETENSI -->
    <div x-show="modalMaster" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalMaster = false">
            <h3 class="text-sm font-bold text-slate-900">Tambah Master Standar Kompetensi</h3>
            <form action="{{ route('competencies.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Kompetensi</label>
                    <input type="text" name="code" placeholder="Contoh: KOMP-11" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kompetensi</label>
                    <input type="text" name="name" placeholder="Contoh: Standar Kebersihan & Sanitasi Kamar Asrama" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Minimal Jabatan</label>
                    <select name="target_level" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="Dasar">Dasar</option>
                        <option value="Menengah" selected>Menengah</option>
                        <option value="Mahir">Mahir</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalMaster = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Master</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: TAMBAH SERTIFIKASI -->
    <div x-show="modalCert" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalCert = false">
            <h3 class="text-sm font-bold text-slate-900">Catat Sertifikasi Pegawai</h3>
            <form action="{{ route('competencies.certifications.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Pegawai</label>
                    <select name="employee_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Sertifikasi</label>
                    <input type="text" name="name" placeholder="Contoh: Ahli K3 Umum Kemenaker" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Lembaga Penerbit</label>
                    <input type="text" name="issuer" placeholder="Contoh: BNSP / Kementerian Ketenagakerjaan RI" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Terbit</label>
                        <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Masa Berlaku s/d</label>
                        <input type="date" name="expiry_date" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalCert = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Sertifikat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

