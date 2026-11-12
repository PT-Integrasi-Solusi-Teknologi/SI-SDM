@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ tab: 'audit', modalUser: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Sistem & Audit Trail</h2>
            <p class="text-xs text-slate-500 mt-0.5">Jejak aktivitas keamanan, hak akses akun pengguna, dan konfigurasi modul SI-SDM.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="modalUser = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Tambah Akun Baru</span>
            </button>
        </div>
    </div>

    <!-- Tab Selector -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
        <button @click="tab = 'audit'" :class="tab === 'audit' ? 'bg-emerald-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 font-medium'" class="px-4 py-2 text-xs rounded-xl transition shadow-2xs">
            Jejak Audit Aktivitas (Audit Trail)
        </button>
        <button @click="tab = 'users'" :class="tab === 'users' ? 'bg-emerald-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 font-medium'" class="px-4 py-2 text-xs rounded-xl transition shadow-2xs">
            Akun & Manajemen Peran ({{ $users->count() }})
        </button>
        <button @click="tab = 'system'" :class="tab === 'system' ? 'bg-emerald-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-50 font-medium'" class="px-4 py-2 text-xs rounded-xl transition shadow-2xs">
            Status Sistem & Integrasi SI-AH
        </button>
    </div>

    <!-- TAB 1: AUDIT TRAIL LOG -->
    <div x-show="tab === 'audit'" class="space-y-4">
        <!-- Filter Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs">
            <form action="{{ route('settings.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Cari Keterangan / Pelaku</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas, nama user, keyword..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Modul</label>
                    <select name="module" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="">Semua Modul</option>
                        <option value="Pegawai" {{ request('module') === 'Pegawai' ? 'selected' : '' }}>Pegawai</option>
                        <option value="Cuti & Izin" {{ request('module') === 'Cuti & Izin' ? 'selected' : '' }}>Cuti & Izin</option>
                        <option value="Kehadiran" {{ request('module') === 'Kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                        <option value="Kompetensi" {{ request('module') === 'Kompetensi' ? 'selected' : '' }}>Kompetensi</option>
                        <option value="Organisasi" {{ request('module') === 'Organisasi' ? 'selected' : '' }}>Organisasi</option>
                        <option value="Autentikasi" {{ request('module') === 'Autentikasi' ? 'selected' : '' }}>Autentikasi</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">Filter</button>
                    <a href="{{ route('settings.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold">Reset</a>
                </div>
            </form>
        </div>

        <!-- Audit Table -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Catatan Aktivitas Sistem Real-Time</h3>
                <span class="text-xs text-slate-400">Total Log: {{ $auditLogs->total() }} Tindakan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Pengguna</th>
                            <th class="py-3 px-4">Aksi</th>
                            <th class="py-3 px-4">Modul</th>
                            <th class="py-3 px-4">Deskripsi Aktivitas</th>
                            <th class="py-3 px-4">Alamat IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                    <span class="font-bold text-slate-700 block">{{ $log->created_at->format('H:i:s') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $log->created_at->format('d M Y') }}</span>
                                </td>

                                <td class="py-3 px-4 font-bold text-slate-900 whitespace-nowrap">
                                    {{ $log->user_name ?? 'Sistem' }}
                                </td>

                                <td class="py-3 px-4">
                                    @php
                                        $color = match($log->action) {
                                            'CREATE' => 'bg-emerald-100 text-emerald-800',
                                            'UPDATE' => 'bg-blue-100 text-blue-800',
                                            'DELETE' => 'bg-rose-100 text-rose-800',
                                            'APPROVE' => 'bg-teal-100 text-teal-800',
                                            'REJECT' => 'bg-amber-100 text-amber-800',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold font-mono {{ $color }}">
                                        {{ $log->action }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 font-semibold text-slate-700 whitespace-nowrap">
                                    {{ $log->module }}
                                </td>

                                <td class="py-3 px-4 text-slate-600 leading-relaxed">
                                    {{ $log->description }}
                                </td>

                                <td class="py-3 px-4 font-mono text-[10px] text-slate-400 whitespace-nowrap">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <p class="text-xs">Belum ada riwayat audit log.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($auditLogs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 2: AKUN PENGGUNA & RBAC -->
    <div x-show="tab === 'users'" x-cloak class="space-y-4">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Daftar Akun Pengguna Terdaftar</h3>
                    <p class="text-xs text-slate-400">Pengaturan akses berbasis role (Super Admin, Admin SDM, Pimpinan, Pegawai)</p>
                </div>
            </div>

            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Alamat Email</th>
                        <th class="py-3 px-4">Peran (Role)</th>
                        <th class="py-3 px-4">Tautan Pegawai</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Login Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ mb_substr($u->name, 0, 2) }}
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">{{ $u->email }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->role === 'superadmin' ? 'bg-purple-100 text-purple-800' : ($u->role === 'admin_sdm' ? 'bg-emerald-100 text-emerald-800' : ($u->role === 'pimpinan' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ str_replace('_', ' ', $u->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-medium">
                                {{ $u->employee?->name ?? 'Administrator Khusus' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('switch-role', $u->role) }}" class="px-3 py-1 bg-slate-100 hover:bg-emerald-600 hover:text-white rounded-lg text-slate-700 font-bold text-[11px] transition">
                                    Ganti ke Akun Ini
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: STATUS SISTEM & INTEGRASI -->
    <div x-show="tab === 'system'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Status Modul SI-SDM Asrama Haji</h3>
            <div class="space-y-3 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Nama Aplikasi:</span>
                    <span class="font-bold text-slate-800">SI-SDM Asrama Haji Terpadu</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Versi Rilis:</span>
                    <span class="font-mono font-bold text-emerald-700">v1.0 (Demo Operasional Penuh)</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Basis Data:</span>
                    <span class="font-mono font-bold text-slate-800">MySQL 8.0 &bull; Database: si_sdm</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Framework:</span>
                    <span class="font-bold text-slate-800">Laravel 13 &bull; PHP 8.4</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Frontend Engine:</span>
                    <span class="font-bold text-slate-800">Tailwind CSS 4 + Alpine.js (Lightweight)</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Kesiapan Kontrak Integrasi SI-AH (6 Modul)</h3>
            <div class="space-y-2.5 text-xs">
                <div class="p-2.5 rounded-xl bg-slate-50 flex items-center justify-between">
                    <span class="font-bold text-slate-800">SI-LAYAN (Penugasan Petugas Layanan)</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Siap</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-50 flex items-center justify-between">
                    <span class="font-bold text-slate-800">SI-ASET (Pemegang Perlengkapan/Kunci)</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Siap</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-50 flex items-center justify-between">
                    <span class="font-bold text-slate-800">SI-DIKLAT (Pembaruan Bukti Pelatihan)</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Siap</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-50 flex items-center justify-between">
                    <span class="font-bold text-slate-800">SI-PBJP (Data Pengadaan & BMN)</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Siap</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH USER BARU -->
    <div x-show="modalUser" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalUser = false">
            <h3 class="text-sm font-bold text-slate-900">Tambah Akun Pengguna Baru</h3>
            <form action="{{ route('settings.users.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pengguna</label>
                    <input type="text" name="name" placeholder="Nama lengkap user..." required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Pengguna</label>
                    <input type="email" name="email" placeholder="user@asramahaji.go.id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi (Password)</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter..." required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hak Akses / Peran (Role)</label>
                    <select name="role" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="admin_sdm">Admin SDM (Pengelola Penuh)</option>
                        <option value="pimpinan">Pimpinan (Kepala UPT / Approval)</option>
                        <option value="pegawai">Pegawai (Self Service)</option>
                        <option value="superadmin">Super Administrator</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tautkan ke Profil Pegawai (Opsional)</label>
                    <select name="employee_id" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="">Tidak Ditautkan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalUser = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

