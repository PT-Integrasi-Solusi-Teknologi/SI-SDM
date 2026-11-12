@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ modalManual: false, modalQuickScan: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Presensi & Kehadiran Pegawai</h2>
            <p class="text-xs text-slate-500 mt-0.5">Monitoring catatan kehadiran harian, waktu tugas, dan rekapitulasi keterlambatan.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button @click="modalManual = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Catat Presensi Manual</span>
            </button>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-emerald-600 uppercase">Hadir Tepat Waktu</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-800 mt-1">{{ $stats['hadir'] }}</p>
            <span class="text-[10px] text-slate-400">Presensi sebelum 08:00 WIB</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-amber-600 uppercase">Terlambat Masuk</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-800 mt-1">{{ $stats['terlambat'] }}</p>
            <span class="text-[10px] text-slate-400">Tercatat dispensasi / potong jam</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-blue-600 uppercase">Dinas Luar (DL)</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-blue-800 mt-1">{{ $stats['dinas_luar'] }}</p>
            <span class="text-[10px] text-slate-400">Koordinasi Kemenag / Bandara</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <span class="text-xs font-bold text-purple-600 uppercase">Izin & Sakit</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-purple-800 mt-1">{{ $stats['izin'] }}</p>
            <span class="text-[10px] text-slate-400">Disertai surat keterangan</span>
        </div>
    </div>

    <!-- Filter & Date Picker Toolbar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs">
        <form action="{{ route('attendance.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Tanggal Presensi</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Unit Kerja</label>
                <select name="department_id" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    <option value="">Semua Unit</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Status Kehadiran</label>
                <select name="status" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="terlambat" {{ request('status') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="dinas_luar" {{ request('status') === 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                    <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Cari Pegawai</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NIP..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                    Filter
                </button>
                <a href="{{ route('attendance.index') }}" class="py-2 px-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Catatan Presensi Tanggal {{ date('d F Y', strtotime($date)) }}</h3>
            <span class="text-xs text-slate-400">Total data: {{ $records->total() }} Pegawai</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Pegawai</th>
                        <th class="py-3 px-4">Unit Kerja & Jabatan</th>
                        <th class="py-3 px-4">Jam Masuk</th>
                        <th class="py-3 px-4">Jam Pulang</th>
                        <th class="py-3 px-4">Total Waktu</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Lokasi & Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $rec)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ $rec->employee->initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('employees.show', $rec->employee) }}" class="font-bold text-slate-900 hover:text-emerald-600 block">
                                            {{ $rec->employee->name }}
                                        </a>
                                        <span class="text-[10px] font-mono text-emerald-700 bg-emerald-50 px-1 rounded">{{ $rec->employee->employee_code }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800 block">{{ $rec->employee->jobPosition?->title }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $rec->employee->department?->name }}</span>
                            </td>

                            <td class="py-3 px-4 font-mono font-semibold text-slate-800">
                                {{ $rec->clock_in ?? '-' }}
                            </td>

                            <td class="py-3 px-4 font-mono font-semibold text-slate-800">
                                {{ $rec->clock_out ?? '-' }}
                            </td>

                            <td class="py-3 px-4 font-extrabold text-emerald-800">
                                {{ $rec->work_hours }} Jam
                            </td>

                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $rec->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : ($rec->status === 'terlambat' ? 'bg-amber-100 text-amber-800' : ($rec->status === 'dinas_luar' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $rec->status)) }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-slate-500 text-[11px]">
                                <span class="block text-slate-700 font-medium">{{ $rec->location }}</span>
                                @if($rec->notes)
                                    <span class="block text-[10px] text-slate-400 italic">{{ $rec->notes }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i data-lucide="clock" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                                <p class="text-xs">Tidak ada data kehadiran pada tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL: CATAT PRESENSI MANUAL -->
    <div x-show="modalManual" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalManual = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Catat Presensi Manual / Koreksi</h3>
                <button @click="modalManual = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('attendance.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Pegawai</label>
                    <select name="employee_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jam Masuk</label>
                        <input type="text" name="clock_in" value="07:45" placeholder="07:45" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jam Pulang</label>
                        <input type="text" name="clock_out" value="16:30" placeholder="16:30" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Kehadiran</label>
                    <select name="status" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="hadir">Hadir Tepat Waktu</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="dinas_luar">Dinas Luar</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpa">Tanpa Keterangan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Alasan Dispensasi</label>
                    <textarea name="notes" rows="2" placeholder="Catatan tugas lapangan atau alasan koreksi..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalManual = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

