@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ modalCreate: false, modalApprove: false, modalReject: false, currentLeaveId: null, currentLeaveTicket: '', currentLeaveName: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Cuti & Izin Pegawai</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pantau saldo hak cuti, kalender pengajuan, dan persetujuan atasan langsung.</p>
        </div>
        <button @click="modalCreate = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Ajukan Cuti / Izin Baru</span>
        </button>
    </div>

    <!-- 4 Metric Cards (Mirip Referensi Gambar) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase">Total Pengajuan</span>
                <div class="w-8 h-8 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
            <span class="text-[10px] text-slate-400">Tahun operasional 2026</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-amber-600 uppercase">Menunggu Keputusan</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-800">{{ $stats['pending'] }}</p>
            <span class="text-[10px] text-amber-600 font-semibold">Perlu persetujuan atasan</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-600 uppercase">Telah Disetujui</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-800">{{ $stats['approved'] }}</p>
            <span class="text-[10px] text-emerald-600 font-semibold">Jadwal pengganti tervalidasi</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-rose-600 uppercase">Permohonan Ditolak</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-rose-800">{{ $stats['rejected'] }}</p>
            <span class="text-[10px] text-rose-600 font-semibold">Karena jadwal puncak haji</span>
        </div>
    </div>

    <!-- Center Cards: Donut Breakdown & Quick Filters -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Jenis Cuti Distribution -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Komposisi Jenis Cuti</h3>
                <span class="text-[10px] text-slate-400">Tahun 2026</span>
            </div>
            <div class="space-y-3">
                @foreach($leaveTypes as $lt)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-medium text-slate-700">{{ $lt->name }}</span>
                            <span class="font-bold text-slate-900">{{ $lt->leave_requests_count }} pengajuan (Ku: {{ $lt->default_quota }} hr)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ min(100, $lt->leave_requests_count * 25) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Filter Pengajuan Cuti</h3>
                <form action="{{ route('leaves.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tiket, nama pegawai..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Status</label>
                        <select name="status" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">Jenis Cuti</label>
                        <select name="leave_type_id" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                            <option value="">Semua Jenis</option>
                            @foreach($leaveTypes as $lt)
                                <option value="{{ $lt->id }}" {{ request('leave_type_id') == $lt->id ? 'selected' : '' }}>
                                    {{ $lt->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-3 flex justify-end gap-2 pt-2">
                        <a href="{{ route('leaves.index') }}" class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold">Reset</a>
                        <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leave Activity Table (Mirip Tabel Leave Activity pada Desain Referensi) -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Aktivitas & Riwayat Pengajuan Cuti (Leave Activity)</h3>
            <span class="text-xs text-slate-400">Total: {{ $leaveRequests->total() }} Permohonan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Nomor Tiket & Pegawai</th>
                        <th class="py-3 px-4">Unit Kerja</th>
                        <th class="py-3 px-4">Jenis Cuti</th>
                        <th class="py-3 px-4">Periode & Durasi</th>
                        <th class="py-3 px-4">Alasan Cuti</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaveRequests as $leave)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Pegawai & Tiket -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ $leave->employee->initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('employees.show', $leave->employee) }}" class="font-bold text-slate-900 hover:text-emerald-600 block">
                                            {{ $leave->employee->name }}
                                        </a>
                                        <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-1 rounded">{{ $leave->ticket_number }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Unit Kerja -->
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ $leave->employee->department?->name }}
                            </td>

                            <!-- Jenis Cuti -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $leave->leaveType->name }}
                                </span>
                            </td>

                            <!-- Periode & Durasi -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ date('d M', strtotime($leave->start_date)) }} - {{ date('d M Y', strtotime($leave->end_date)) }}</span>
                                <span class="text-[10px] font-semibold text-emerald-600">{{ $leave->duration_days }} Hari Kerja</span>
                            </td>

                            <!-- Alasan -->
                            <td class="py-3.5 px-4 max-w-xs text-slate-600 leading-relaxed truncate" title="{{ $leave->reason }}">
                                {{ $leave->reason }}
                                @if($leave->approver_notes)
                                    <span class="block text-[10px] text-slate-400 mt-0.5 italic">Catatan atasan: "{{ $leave->approver_notes }}"</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4">
                                @if($leave->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                        Disetujui
                                    </span>
                                @elseif($leave->status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 animate-pulse">
                                        Menunggu
                                    </span>
                                @endif
                            </td>

                            <!-- Tindakan Langsung -->
                            <td class="py-3.5 px-4 text-right">
                                @if($leave->status === 'pending')
                                    <div class="inline-flex items-center gap-1.5">
                                        <button @click="currentLeaveId = {{ $leave->id }}; currentLeaveTicket = '{{ $leave->ticket_number }}'; currentLeaveName = '{{ addslashes($leave->employee->name) }}'; modalApprove = true" 
                                                class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] transition shadow-2xs">
                                            Setujui
                                        </button>
                                        <button @click="currentLeaveId = {{ $leave->id }}; currentLeaveTicket = '{{ $leave->ticket_number }}'; currentLeaveName = '{{ addslashes($leave->employee->name) }}'; modalReject = true" 
                                                class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-bold text-[11px] transition">
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 font-medium">Selesai ({{ $leave->approver_name ?? 'Atasan' }})</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i data-lucide="calendar-check" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                                <p class="text-xs">Belum ada permohonan cuti dalam daftar ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaveRequests->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL 1: AJUKAN CUTI BARU -->
    <div x-show="modalCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalCreate = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Form Pengajuan Cuti / Izin Baru</h3>
                <button @click="modalCreate = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('leaves.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Pegawai Pemohon</label>
                    <select name="employee_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }} - {{ $emp->department?->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Cuti</label>
                    <select name="leave_type_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($leaveTypes as $lt)
                            <option value="{{ $lt->id }}">{{ $lt->name }} (Kuota s/d {{ $lt->default_quota }} Hari)</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai Cuti</label>
                        <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" required value="{{ date('Y-m-d') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Pengajuan Cuti</label>
                    <textarea name="reason" rows="3" required placeholder="Tuliskan alasan lengkap, rencana serah terima tugas sementara..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalCreate = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Kirim Pengajuan Cuti</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: PERSETUJUAN CUTI (APPROVE) -->
    <div x-show="modalApprove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalApprove = false">
            <h3 class="text-sm font-bold text-slate-900">Setujui Permohonan Cuti</h3>
            <p class="text-xs text-slate-500 mt-1">
                Anda akan menyetujui cuti <span class="font-bold text-emerald-700" x-text="currentLeaveTicket"></span> an. <span class="font-bold" x-text="currentLeaveName"></span>.
            </p>

            <form :action="'{{ url('leaves') }}/' + currentLeaveId + '/approve'" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Persetujuan (Opsional)</label>
                    <textarea name="approver_notes" rows="2" placeholder="Disetujui. Pastikan piket pengganti telah standby..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalApprove = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Konfirmasi Setujui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: PENOLAKAN CUTI (REJECT) -->
    <div x-show="modalReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalReject = false">
            <h3 class="text-sm font-bold text-rose-700">Tolak Permohonan Cuti</h3>
            <p class="text-xs text-slate-500 mt-1">
                Berikan alasan penolakan permohonan <span class="font-bold" x-text="currentLeaveTicket"></span>.
            </p>

            <form :action="'{{ url('leaves') }}/' + currentLeaveId + '/reject'" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="approver_notes" required rows="3" placeholder="Contoh: Bertepatan dengan jadwal kedatangan gelombang 1 kloter haji..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalReject = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

