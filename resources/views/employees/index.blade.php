@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ viewMode: 'table' }">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Direktori Data Pegawai</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh data induk pegawai, penempatan unit, dan status kepegawaian.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="flex items-center bg-slate-100 p-1 rounded-xl text-slate-500">
                <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'hover:text-slate-900'" class="px-2.5 py-1 text-xs rounded-lg transition">
                    Tabel
                </button>
                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'hover:text-slate-900'" class="px-2.5 py-1 text-xs rounded-lg transition">
                    Kartu Grid
                </button>
            </div>
            <a href="{{ route('employees.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Pegawai Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Pills -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-3.5 rounded-2xl bg-white border border-slate-100 shadow-2xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase">Total Terdaftar</span>
            <p class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total'] }}</p>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-100 shadow-2xs">
            <span class="text-[10px] font-bold text-emerald-600 uppercase">Aktif Bertugas</span>
            <p class="text-xl font-extrabold text-emerald-800 mt-0.5">{{ $stats['active'] }}</p>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-100 shadow-2xs">
            <span class="text-[10px] font-bold text-blue-600 uppercase">Pegawai ASN / PNS</span>
            <p class="text-xl font-extrabold text-blue-800 mt-0.5">{{ $stats['asn'] }}</p>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-100 shadow-2xs">
            <span class="text-[10px] font-bold text-purple-600 uppercase">Pegawai PPPK</span>
            <p class="text-xl font-extrabold text-purple-800 mt-0.5">{{ $stats['pppk'] }}</p>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-100 shadow-2xs col-span-2 sm:col-span-1">
            <span class="text-[10px] font-bold text-teal-600 uppercase">Kontrak & Alih Daya</span>
            <p class="text-xl font-extrabold text-teal-800 mt-0.5">{{ $stats['kontrak'] }}</p>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs">
        <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2 relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, kode pegawai, email..."
                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
            </div>

            <!-- Dept Filter -->
            <div>
                <select name="department_id" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    <option value="">Semua Unit Kerja</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <select name="employment_type_id" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    <option value="">Semua Jenis Pegawai</option>
                    @foreach($employmentTypes as $type)
                        <option value="{{ $type->id }}" {{ request('employment_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition">
                    Terapkan
                </button>
                <a href="{{ route('employees.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table View -->
    <div x-show="viewMode === 'table'" class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3.5 px-4">Pegawai</th>
                        <th class="py-3.5 px-4">Unit Kerja & Jabatan</th>
                        <th class="py-3.5 px-4">Status & Kategori</th>
                        <th class="py-3.5 px-4">Model Kerja</th>
                        <th class="py-3.5 px-4">Bergabung</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- Pegawai Profile -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($emp->avatar)
                                        <img src="{{ $emp->avatar }}" alt="{{ $emp->name }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-emerald-500/20 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0 text-xs">
                                            {{ $emp->initials }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('employees.show', $emp) }}" class="font-bold text-slate-900 hover:text-emerald-600 text-xs block leading-snug">
                                            {{ $emp->name }}
                                        </a>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[10px] font-mono text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.2 rounded">{{ $emp->employee_code }}</span>
                                            @if($emp->nip)
                                                <span class="text-[10px] text-slate-400">NIP: {{ $emp->nip }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Unit & Jabatan -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $emp->jobPosition?->title ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $emp->department?->name ?? '-' }}</span>
                            </td>

                            <!-- Status & Kategori -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $emp->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $emp->employmentStatus?->name ?? 'Aktif' }}
                                    </span>
                                    <span class="block text-[10px] text-slate-500 font-medium">
                                        {{ $emp->employmentType?->name ?? 'ASN' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Model Kerja -->
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $emp->work_model }}
                                </span>
                            </td>

                            <!-- Join Date -->
                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                {{ $emp->join_date ? date('d M Y', strtotime($emp->join_date)) : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('employees.show', $emp) }}" class="p-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg text-slate-500 transition" title="Lihat Profil">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $emp) }}" class="p-1.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 rounded-lg text-slate-500 transition" title="Ubah Data">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $emp) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan pegawai {{ $emp->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 rounded-lg text-slate-500 transition" title="Arsipkan Pegawai">
                                            <i data-lucide="archive" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i data-lucide="users" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                                <p class="text-xs">Tidak ditemukan data pegawai yang cocok dengan filter pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

    <!-- Data Grid Cards View -->
    <div x-show="viewMode === 'grid'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($employees as $emp)
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-mono px-2 py-0.5 bg-emerald-50 text-emerald-800 font-bold rounded-md">
                            {{ $emp->employee_code }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $emp->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $emp->work_model }}
                        </span>
                    </div>

                    <div class="text-center my-3">
                        @if($emp->avatar)
                            <img src="{{ $emp->avatar }}" alt="{{ $emp->name }}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-emerald-500/20 mx-auto shadow-sm">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center mx-auto text-base">
                                {{ $emp->initials }}
                            </div>
                        @endif
                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm mt-3 leading-snug line-clamp-1">
                            {{ $emp->name }}
                        </h4>
                        <p class="text-[11px] text-emerald-700 font-semibold mt-0.5 line-clamp-1">{{ $emp->jobPosition?->title }}</p>
                        <p class="text-[10px] text-slate-400 line-clamp-1">{{ $emp->department?->name }}</p>
                    </div>

                    <div class="space-y-1.5 pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                        <div class="flex items-center justify-between">
                            <span>Status:</span>
                            <span class="font-bold text-slate-700">{{ $emp->employmentType?->name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Email:</span>
                            <span class="font-medium text-slate-700 truncate max-w-[130px]">{{ $emp->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('employees.show', $emp) }}" class="flex-1 py-1.5 text-center bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-800 font-bold text-xs rounded-xl transition">
                        Buka Profil
                    </a>
                    <a href="{{ route('employees.edit', $emp) }}" class="p-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-600 transition" title="Edit">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

