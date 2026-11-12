@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Eksekutif & Rekapitulasi SDM</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ringkasan analitik kepegawaian untuk pimpinan, kementerian, dan stakeholder terkait.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.print') }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Lembar Laporan Resmi (PDF)</span>
            </a>
        </div>
    </div>

    <!-- ROW 1: REKAPITULASI UNIT KERJA & STATUS PEGAWAI -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Rekapitulasi SDM per Unit Kerja & Kategori</h3>
                <p class="text-xs text-slate-400">Distribusi status ASN, PPPK, Kontrak, dan Tenaga Alih Daya</p>
            </div>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                {{ \App\Models\Employee::count() }} Total Pegawai
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Unit Kerja / Departemen</th>
                        <th class="py-3 px-4 text-center">PNS / ASN</th>
                        <th class="py-3 px-4 text-center">PPPK</th>
                        <th class="py-3 px-4 text-center">Kontrak</th>
                        <th class="py-3 px-4 text-center">Alih Daya</th>
                        <th class="py-3 px-4 text-right">Total Pegawai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($departments as $dept)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $dept->name }}</td>
                            <td class="py-3 px-4 text-center font-semibold text-blue-700">{{ $dept->asn_count }}</td>
                            <td class="py-3 px-4 text-center font-semibold text-purple-700">{{ $dept->pppk_count }}</td>
                            <td class="py-3 px-4 text-center font-semibold text-teal-700">{{ $dept->kontrak_count }}</td>
                            <td class="py-3 px-4 text-center font-semibold text-slate-600">{{ $dept->outsourcing_count }}</td>
                            <td class="py-3 px-4 text-right font-extrabold text-emerald-800">{{ $dept->employees_count }} Orang</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ROW 2: REKAPITULASI CUTI & PRESENSI -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rekap Cuti -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-1">Rekapitulasi Penggunaan Cuti Terverifikasi</h3>
            <p class="text-xs text-slate-400 mb-4">Total hari kerja yang telah disetujui</p>

            <div class="space-y-3">
                @foreach($leaveReport as $lr)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-xs text-slate-800 block">{{ $lr->leaveType->name }}</span>
                            <span class="text-[10px] text-slate-400">{{ $lr->count }} Berkas Pengajuan</span>
                        </div>
                        <span class="font-bold text-xs text-emerald-700 bg-emerald-100/60 px-2.5 py-1 rounded-xl">
                            {{ $lr->total_days }} Hari Kerja
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Rekap Presensi -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-1">Akumulasi Catatan Presensi</h3>
            <p class="text-xs text-slate-400 mb-4">Monitoring kepatuhan kehadiran</p>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase">Hadir Tepat</span>
                    <p class="text-2xl font-extrabold text-emerald-900 mt-1">{{ $attendanceStats['hadir'] }}</p>
                    <span class="text-[10px] text-emerald-600">Presensi normal</span>
                </div>
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100">
                    <span class="text-[10px] font-bold text-amber-700 uppercase">Terlambat</span>
                    <p class="text-2xl font-extrabold text-amber-900 mt-1">{{ $attendanceStats['terlambat'] }}</p>
                    <span class="text-[10px] text-amber-600">Koreksi harian</span>
                </div>
                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <span class="text-[10px] font-bold text-blue-700 uppercase">Dinas Luar</span>
                    <p class="text-2xl font-extrabold text-blue-900 mt-1">{{ $attendanceStats['dinas_luar'] }}</p>
                    <span class="text-[10px] text-blue-600">Surat tugas resmi</span>
                </div>
                <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-100">
                    <span class="text-[10px] font-bold text-purple-700 uppercase">Izin / Sakit</span>
                    <p class="text-2xl font-extrabold text-purple-900 mt-1">{{ $attendanceStats['izin'] + $attendanceStats['sakit'] }}</p>
                    <span class="text-[10px] text-purple-600">Surat dokter / izin</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

