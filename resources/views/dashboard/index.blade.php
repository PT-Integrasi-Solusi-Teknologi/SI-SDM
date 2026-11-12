@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Top Banner / Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-700 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-emerald-900/10 relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-emerald-100 text-xs font-semibold mb-3">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-300"></i>
                <span>Sistem Terintegrasi SI-AH 1447H</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Pusat Kendali Sumber Daya Manusia
            </h2>
            <p class="text-emerald-100/90 text-xs sm:text-sm mt-1 leading-relaxed">
                Pemantauan real-time kepegawaian, kesiapan petugas haji, absensi harian, permohonan cuti, dan matriks kompetensi terpadu.
            </p>
        </div>
        <div class="relative z-10 flex flex-wrap items-center gap-2.5">
            <a href="{{ route('employees.create') }}" class="px-4 py-2.5 rounded-xl bg-white text-emerald-800 text-xs font-bold hover:bg-emerald-50 transition shadow-md flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4 text-emerald-600"></i>
                <span>Tambah Pegawai</span>
            </a>
            <a href="{{ route('leaves.index') }}" class="px-4 py-2.5 rounded-xl bg-emerald-800/60 hover:bg-emerald-800 text-white border border-emerald-400/30 text-xs font-bold transition flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-emerald-200"></i>
                <span>Kelola Cuti</span>
            </a>
            <a href="{{ route('reports.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-emerald-800/60 hover:bg-emerald-800 text-white border border-emerald-400/30 text-xs font-bold transition flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4 text-emerald-200"></i>
                <span>Cetak Rekap</span>
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Stat 1: Total Pegawai -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pegawai</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $totalEmployees }}</span>
                <span class="text-xs font-semibold text-emerald-600 flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i> 100%
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Tersebar di 7 Unit Kerja Balai UPT</p>
        </div>

        <!-- Stat 2: Pegawai Aktif -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pegawai Aktif</span>
                <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $activeEmployees }}</span>
                <span class="text-xs font-semibold text-slate-500">
                    ({{ round(($activeEmployees / max(1, $totalEmployees)) * 100) }}%)
                </span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-teal-500 h-full rounded-full" style="width: {{ round(($activeEmployees / max(1, $totalEmployees)) * 100) }}%"></div>
            </div>
        </div>

        <!-- Stat 3: Cuti Pending -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Approval Cuti</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $pendingLeaves }}</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Menunggu</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">
                <a href="{{ route('leaves.index', ['status' => 'pending']) }}" class="text-emerald-600 font-semibold hover:underline">
                    Lihat antrean persetujuan &rarr;
                </a>
            </p>
        </div>

        <!-- Stat 4: Skor Kinerja Rata-rata -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Kinerja</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $averagePerformance }}%</span>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Sangat Baik</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Berdasarkan evaluasi tahun berjalan</p>
        </div>
    </div>

    <!-- Row 2: Distribusi Pegawai & Kehadiran Hari Ini & Profile Spotlight -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Distribusi Unit Kerja -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Distribusi per Unit Kerja</h3>
                    <p class="text-xs text-slate-400">Komposisi penempatan pegawai</p>
                </div>
                <a href="{{ route('organization.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Bagan</a>
            </div>
            <div class="space-y-3.5 mt-2">
                @foreach($departmentStats->take(5) as $dept)
                    @php $pct = round(($dept->employees_count / max(1, $totalEmployees)) * 100); @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-medium text-slate-700 truncate max-w-[200px]">{{ $dept->name }}</span>
                            <span class="font-bold text-slate-900">{{ $dept->employees_count }} org ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Kehadiran Hari Ini -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Rekap Kehadiran Terkini</h3>
                    <p class="text-xs text-slate-400">Status absensi operasional</p>
                </div>
                <a href="{{ route('attendance.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Kelola Absensi</a>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-2">
                <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                    <span class="text-[11px] font-bold text-emerald-700 uppercase">Hadir Tepat</span>
                    <p class="text-2xl font-extrabold text-emerald-900 mt-1">{{ $todayAttendance['hadir'] }}</p>
                    <span class="text-[10px] text-emerald-600">Jam kerja normal</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-100">
                    <span class="text-[11px] font-bold text-amber-700 uppercase">Terlambat</span>
                    <p class="text-2xl font-extrabold text-amber-900 mt-1">{{ $todayAttendance['terlambat'] }}</p>
                    <span class="text-[10px] text-amber-600">&gt; 08:00 WIB</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100">
                    <span class="text-[11px] font-bold text-blue-700 uppercase">Dinas Luar</span>
                    <p class="text-2xl font-extrabold text-blue-900 mt-1">{{ $todayAttendance['dinas_luar'] }}</p>
                    <span class="text-[10px] text-blue-600">Tugas bandara/kemenag</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-purple-50/70 border border-purple-100">
                    <span class="text-[11px] font-bold text-purple-700 uppercase">Izin / Sakit</span>
                    <p class="text-2xl font-extrabold text-purple-900 mt-1">{{ $todayAttendance['izin'] }}</p>
                    <span class="text-[10px] text-purple-600">Surat dokter / cuti</span>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total kehadiran tervalidasi</span>
                <span class="font-bold text-slate-800">{{ array_sum($todayAttendance) }} Pegawai</span>
            </div>
        </div>

        <!-- Featured Employee Spotlight (Mirip Card Mia Torres di Referensi) -->
        @if($featuredEmployee)
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 p-6 rounded-3xl text-white shadow-lg shadow-emerald-800/10 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-200">Profil Unggulan</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-white/20 font-semibold">{{ $featuredEmployee->work_model }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <img src="{{ $featuredEmployee->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&auto=format&fit=crop&q=80' }}" 
                         alt="{{ $featuredEmployee->name }}" 
                         class="w-16 h-16 rounded-2xl object-cover ring-2 ring-white/40 shadow-md">
                    <div>
                        <h4 class="text-base font-bold text-white leading-snug">{{ $featuredEmployee->name }}</h4>
                        <p class="text-xs text-emerald-100">{{ $featuredEmployee->jobPosition?->title }}</p>
                        <span class="inline-block mt-1 text-[11px] font-mono px-2 py-0.5 rounded-md bg-black/20 text-emerald-100">
                            {{ $featuredEmployee->employee_code }}
                        </span>
                    </div>
                </div>
                <p class="text-xs text-emerald-100/90 mt-4 leading-relaxed line-clamp-2">
                    {{ $featuredEmployee->notes }}
                </p>
            </div>

            <div class="mt-6 pt-4 border-t border-white/15 flex items-center justify-between">
                <div>
                    <span class="block text-[10px] text-emerald-200">Kinerja Evaluasi</span>
                    <span class="text-sm font-extrabold">{{ $featuredEmployee->latest_performance_score ?? 88.5 }}%</span>
                </div>
                <a href="{{ route('employees.show', $featuredEmployee) }}" class="px-4 py-2 bg-white text-emerald-800 hover:bg-emerald-50 text-xs font-bold rounded-xl transition shadow-sm">
                    Buka Profil Lengkap &rarr;
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Row 3: Pengajuan Cuti Menunggu Approval & Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Antrean Cuti Menunggu Persetujuan (Interactive Approve/Reject) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Pengajuan Cuti & Izin Menunggu Keputusan</h3>
                    <p class="text-xs text-slate-400">Verifikasi cepat oleh pimpinan atau atasan</p>
                </div>
                <a href="{{ route('leaves.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua Cuti</a>
            </div>

            @if($pendingLeaveRequests->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto text-emerald-500 mb-2"></i>
                    <p class="text-xs font-medium">Semua pengajuan cuti telah diproses. Tidak ada antrean pending!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase text-[10px]">
                                <th class="pb-3">Pegawai</th>
                                <th class="pb-3">Jenis Cuti</th>
                                <th class="pb-3">Periode</th>
                                <th class="pb-3">Alasan</th>
                                <th class="pb-3 text-right">Keputusan Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($pendingLeaveRequests as $req)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[10px]">
                                                {{ $req->employee->initials }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $req->employee->name }}</p>
                                                <p class="text-[10px] text-slate-400">{{ $req->employee->department?->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full font-semibold text-[10px] bg-slate-100 text-slate-700">
                                            {{ $req->leaveType->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-slate-600">
                                        <span class="font-medium">{{ date('d M', strtotime($req->start_date)) }} - {{ date('d M Y', strtotime($req->end_date)) }}</span>
                                        <span class="text-[10px] text-slate-400 block font-semibold">({{ $req->duration_days }} hari)</span>
                                    </td>
                                    <td class="py-3 text-slate-500 max-w-[160px] truncate" title="{{ $req->reason }}">
                                        {{ $req->reason }}
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <form action="{{ route('leaves.approve', $req) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] transition shadow-2xs">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('leaves.reject', $req) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="approver_notes" value="Jadwal benturan dengan agenda penting kloter haji.">
                                                <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-bold text-[11px] transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Top Performers (Leaderboard Kinerja) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Pegawai Berkinerja Terbaik</h3>
                    <p class="text-xs text-slate-400">Leaderboard capaian kerja</p>
                </div>
                <a href="{{ route('performance.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>

            <div class="space-y-3.5 mt-2">
                @foreach($topPerformers as $idx => $performer)
                    <div class="flex items-center justify-between p-2.5 rounded-2xl bg-slate-50/70 border border-slate-100/60 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-5 text-center font-extrabold text-xs {{ $idx === 0 ? 'text-amber-500' : ($idx === 1 ? 'text-slate-400' : 'text-slate-500') }}">
                                #{{ $idx + 1 }}
                            </span>
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-xs">
                                {{ $performer->initials }}
                            </div>
                            <div>
                                <a href="{{ route('employees.show', $performer) }}" class="font-bold text-xs text-slate-800 hover:text-emerald-600 block truncate max-w-[140px]">
                                    {{ $performer->name }}
                                </a>
                                <p class="text-[10px] text-slate-400 truncate max-w-[140px]">{{ $performer->jobPosition?->title }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-emerald-700 block">
                                {{ $performer->latest_performance_score }}%
                            </span>
                            <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-100/60 px-1.5 py-0.5 rounded">
                                Sangat Baik
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Row 4: Audit Activity Log Feed -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Jejak Aktivitas & Audit Trail Terkini</h3>
                    <p class="text-xs text-slate-400">Pencatatan riwayat perubahan sistem transparan</p>
                </div>
            </div>
            <a href="{{ route('settings.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Semua Log &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($recentActivities as $log)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-slate-800">{{ $log->user_name ?? 'Sistem' }}</span>
                        <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-600 text-[11px] leading-relaxed line-clamp-2">{{ $log->description }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 uppercase">{{ $log->module }}</span>
                        <span class="text-[9px] font-mono text-slate-400">{{ $log->ip_address }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

