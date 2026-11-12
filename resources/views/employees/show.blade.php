@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'overview', copySuccess: false }">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('employees.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-300 transition shadow-2xs">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('employees.index') }}" class="hover:text-emerald-600">Data Pegawai</a>
                    <span>/</span>
                    <span class="text-slate-600 font-semibold">Detail Profil Pegawai</span>
                </div>
                <h2 class="text-xl font-extrabold text-slate-900 mt-0.5">Detail Pegawai: {{ $employee->name }}</h2>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:border-emerald-300 hover:text-emerald-700 text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                <span>Edit Profil</span>
            </a>
            <a href="{{ route('reports.print', ['type' => 'pegawai_tunggal', 'employee_id' => $employee->id]) }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Cetak Biodata</span>
            </a>
        </div>
    </div>

    <!-- MAIN GRID 3-COLUMN (Mirip Persis Layout TeamHub / Screen 1) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- ================= LEFT COLUMN (Profile Card & Personal Info) ================= -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Profile Identity Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs text-center relative overflow-hidden">
                <div class="w-28 h-28 mx-auto rounded-3xl overflow-hidden ring-4 ring-emerald-500/20 shadow-md mb-4 bg-emerald-50">
                    @if($employee->avatar)
                        <img src="{{ $employee->avatar }}" alt="{{ $employee->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-emerald-100 text-emerald-800 font-extrabold text-2xl">
                            {{ $employee->initials }}
                        </div>
                    @endif
                </div>

                <h3 class="text-base font-extrabold text-slate-900 leading-snug">{{ $employee->name }}</h3>
                <p class="text-xs text-emerald-700 font-semibold mt-0.5">{{ $employee->jobPosition?->title }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $employee->department?->name }}</p>

                <!-- Employee Code with Copy Button -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono font-bold text-slate-800 mt-3.5">
                    <span>{{ $employee->employee_code }}</span>
                    <button @click="navigator.clipboard.writeText('{{ $employee->employee_code }}'); copySuccess = true; setTimeout(() => copySuccess = false, 2000)" 
                            class="text-slate-400 hover:text-emerald-600 transition" title="Salin Kode">
                        <i data-lucide="copy" class="w-3.5 h-3.5" x-show="!copySuccess"></i>
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600" x-show="copySuccess" x-cloak></i>
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-5 mt-5 border-t border-slate-100 text-[11px]">
                    <div>
                        <span class="block text-slate-400">Jenis:</span>
                        <span class="font-bold text-slate-800">{{ $employee->employmentType?->name }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400">Model:</span>
                        <span class="font-bold text-slate-800">{{ $employee->work_model }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400">Mulai:</span>
                        <span class="font-bold text-slate-800">{{ $employee->join_date ? date('d M Y', strtotime($employee->join_date)) : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Personal Info Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Informasi Pribadi</h4>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Tervalidasi</span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center gap-3 text-slate-600">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Jenis Kelamin</span>
                            <span class="font-bold text-slate-800">{{ $employee->gender }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-slate-600">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Tempat & Tanggal Lahir</span>
                            <span class="font-bold text-slate-800">
                                {{ $employee->birth_place ?? 'Jakarta' }}, {{ $employee->birth_date ? date('d F Y', strtotime($employee->birth_date)) : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-slate-600">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <div class="truncate">
                            <span class="text-[10px] text-slate-400 block">Alamat Email</span>
                            <span class="font-bold text-slate-800 truncate block">{{ $employee->email ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-slate-600">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Nomor Telepon</span>
                            <span class="font-bold text-slate-800 font-mono">{{ $employee->phone ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 text-slate-600">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 mt-0.5">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Alamat Domisili</span>
                            <span class="font-medium text-slate-700 leading-relaxed block">{{ $employee->address ?? 'Pondok Gede, Jakarta Timur' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internal Notes & Appreciation Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs" x-data="{ editingNote: false, noteText: '{{ addslashes($employee->notes ?? '') }}' }">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Catatan & Apresiasi Internal</h4>
                    <button @click="editingNote = !editingNote" class="text-emerald-600 hover:text-emerald-700 text-xs font-semibold">
                        <span x-text="editingNote ? 'Batal' : 'Ubah'"></span>
                    </button>
                </div>

                <div x-show="!editingNote">
                    <p class="text-xs text-slate-600 leading-relaxed bg-emerald-50/50 p-3.5 rounded-2xl border border-emerald-100/60 italic">
                        "{{ $employee->notes ?? 'Pegawai bertugas dengan penuh tanggung jawab dan berdedikasi dalam penyelenggaraan operasional asrama haji.' }}"
                    </p>
                </div>

                <form x-show="editingNote" x-cloak action="{{ route('employees.notes', $employee) }}" method="POST" class="space-y-2">
                    @csrf
                    <textarea name="notes" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" placeholder="Tuliskan catatan apresiasi atau evaluasi kinerja...">{{ $employee->notes }}</textarea>
                    <button type="submit" class="w-full py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>

        <!-- ================= CENTER & RIGHT (Stats, Charts, Documents & Summary) ================= -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- TOP STATS: 4 Circular Ring Gauges (Mirip Referensi Gambar) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <!-- Gauge 1: All Leaves -->
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs text-center flex flex-col items-center justify-center">
                    <div class="relative w-16 h-16 flex items-center justify-center mb-2">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-emerald-500 stroke-current" stroke-dasharray="{{ min(100, round(($allLeavesUsed / max(1, $allLeavesQuota)) * 100)) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute font-extrabold text-xs text-slate-800">{{ $allLeavesUsed }}/{{ $allLeavesQuota }}</span>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Semua Cuti</span>
                    <span class="text-[10px] text-slate-400">Total terpakai</span>
                </div>

                <!-- Gauge 2: Annual Leaves -->
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs text-center flex flex-col items-center justify-center">
                    <div class="relative w-16 h-16 flex items-center justify-center mb-2">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-teal-500 stroke-current" stroke-dasharray="{{ min(100, round(($annualUsed / max(1, $annualQuota)) * 100)) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute font-extrabold text-xs text-slate-800">{{ $annualUsed }}/{{ $annualQuota }}</span>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Cuti Tahunan</span>
                    <span class="text-[10px] text-slate-400">Sisa: {{ max(0, $annualQuota - $annualUsed) }} hari</span>
                </div>

                <!-- Gauge 3: Casual / Alasan Penting Leaves -->
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs text-center flex flex-col items-center justify-center">
                    <div class="relative w-16 h-16 flex items-center justify-center mb-2">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-blue-500 stroke-current" stroke-dasharray="{{ min(100, round(($casualUsed / max(1, $casualQuota)) * 100)) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute font-extrabold text-xs text-slate-800">{{ $casualUsed }}/{{ $casualQuota }}</span>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Cuti Khusus</span>
                    <span class="text-[10px] text-slate-400">Sisa: {{ max(0, $casualQuota - $casualUsed) }} hari</span>
                </div>

                <!-- Gauge 4: Sick Leaves -->
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xs text-center flex flex-col items-center justify-center">
                    <div class="relative w-16 h-16 flex items-center justify-center mb-2">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-amber-500 stroke-current" stroke-dasharray="{{ min(100, round(($sickUsed / max(1, $sickQuota)) * 100)) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute font-extrabold text-xs text-slate-800">{{ $sickUsed }}/{{ $sickQuota }}</span>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Cuti Sakit</span>
                    <span class="text-[10px] text-slate-400">Surat dokter</span>
                </div>
            </div>

            <!-- ROW: Performance Overview & Compensation Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Performance Overview Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tinjauan Performa</h4>
                                <p class="text-[11px] text-slate-400">Indikator Kinerja Pegawai (IKP)</p>
                            </div>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Tahun 2026</span>
                        </div>

                        <div class="flex items-baseline gap-2 my-3">
                            <span class="text-3xl font-extrabold text-slate-900">{{ $employee->latest_performance_score ?? 86.76 }}%</span>
                            <span class="text-xs font-bold text-emerald-600 flex items-center">
                                <i data-lucide="trending-up" class="w-3.5 h-3.5 mr-0.5"></i> Sangat Baik
                            </span>
                        </div>

                        <!-- Mini Trend Chart Simulation -->
                        <div class="h-20 w-full flex items-end gap-1.5 pt-3 border-t border-slate-100">
                            @foreach([78, 82, 85, 83, 89, 87, 91, 88, 92, 90, 89, 94] as $val)
                                <div class="flex-1 bg-emerald-100 hover:bg-emerald-500 rounded-t-sm transition-all group relative" style="height: {{ $val }}%">
                                    <span class="absolute -top-6 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[9px] px-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition">
                                        {{ $val }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 mt-3">
                        <span>Evaluator: Drs. H. Ahmad Fauzi</span>
                        <a href="{{ route('performance.index', ['search' => $employee->name]) }}" class="text-emerald-600 font-bold hover:underline">Detail Evaluasi &rarr;</a>
                    </div>
                </div>

                <!-- Hours Logged & Jam Kerja Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Jam Kerja Masuk</h4>
                                <p class="text-[11px] text-slate-400">Total akumulasi jam tugas</p>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">Minggu Ini</span>
                        </div>

                        <div class="flex items-baseline gap-2 my-3">
                            <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalHoursLogged, 1) }} Jam</span>
                            <span class="text-xs text-slate-400 font-medium">dari target 37.5 Jam</span>
                        </div>

                        <!-- Weekly Bar Chart -->
                        <div class="h-20 w-full flex items-end justify-between gap-2 pt-3 border-t border-slate-100">
                            @php $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']; @endphp
                            @foreach($days as $idx => $d)
                                @php $h = ($idx < 5) ? rand(70, 95) : 0; @endphp
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full bg-slate-100 rounded-t-md h-14 flex items-end">
                                        <div class="w-full {{ $h > 0 ? 'bg-emerald-600' : 'bg-transparent' }} rounded-t-md" style="height: {{ $h }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $d }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 mt-3">
                        <span>Kehadiran rata-rata: 98.4%</span>
                        <a href="{{ route('attendance.index', ['search' => $employee->name]) }}" class="text-emerald-600 font-bold hover:underline">Riwayat Absensi &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- ROW: Dokumen Cepat & Ringkasan Tunjangan / Kompensasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Documents Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Arsip Dokumen Kepegawaian</h4>
                            <p class="text-[11px] text-slate-400">Berkas sah tervalidasi sistem</p>
                        </div>
                        <a href="{{ route('documents.index', ['employee_id' => $employee->id]) }}" class="text-xs font-semibold text-emerald-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($employee->documents->take(3) as $doc)
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 truncate">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </div>
                                    <div class="truncate">
                                        <span class="font-bold text-xs text-slate-800 block truncate">{{ $doc->title }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $doc->file_size }} &bull; {{ $doc->document_type }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('documents.download', $doc) }}" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-white rounded-lg transition" title="Unduh Berkas">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-4 text-center">Belum ada dokumen yang diunggah.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Compensation / Payroll Summary (Mirip Desain Referensi) -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Ringkasan Hak & Kompensasi</h4>
                            <p class="text-[11px] text-slate-400">Rincian gaji pokok & tunjangan resmi</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Aktif</span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Gaji Pokok / Honorarium</span>
                            <span class="font-mono font-bold text-slate-800">Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Tunjangan Kinerja / Posisi</span>
                            <span class="font-mono font-bold text-slate-800">Rp 1.850.000</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Tunjangan Transport & Operasional</span>
                            <span class="font-mono font-bold text-slate-800">Rp 750.000</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>BPJS Kesehatan & Ketenagakerjaan</span>
                            <span class="font-bold text-emerald-600">Ditanggung Instansi</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-extrabold text-slate-900">
                            <span>Total Estimasi Bulanan:</span>
                            <span class="font-mono text-emerald-700 text-sm">Rp {{ number_format($employee->base_salary + 2600000, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAILED TABS SECTION (Riwayat Penempatan, Kompetensi, Dokumen, Absensi) -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
                <!-- Tab Headers -->
                <div class="flex border-b border-slate-100 px-6 pt-3 gap-6 overflow-x-auto">
                    <button @click="activeTab = 'penempatan'" 
                            :class="activeTab === 'penempatan' ? 'border-emerald-600 text-emerald-700 font-bold' : 'border-transparent text-slate-400 hover:text-slate-700'"
                            class="pb-3 text-xs border-b-2 font-medium transition whitespace-nowrap">
                        Riwayat Penempatan & Mutasi ({{ $employee->assignments->count() }})
                    </button>
                    <button @click="activeTab = 'kompetensi'" 
                            :class="activeTab === 'kompetensi' ? 'border-emerald-600 text-emerald-700 font-bold' : 'border-transparent text-slate-400 hover:text-slate-700'"
                            class="pb-3 text-xs border-b-2 font-medium transition whitespace-nowrap">
                        Kompetensi & Sertifikasi ({{ $employee->competencies->count() }})
                    </button>
                    <button @click="activeTab = 'dokumen'" 
                            :class="activeTab === 'dokumen' ? 'border-emerald-600 text-emerald-700 font-bold' : 'border-transparent text-slate-400 hover:text-slate-700'"
                            class="pb-3 text-xs border-b-2 font-medium transition whitespace-nowrap">
                        Arsip Dokumen ({{ $employee->documents->count() }})
                    </button>
                    <button @click="activeTab = 'kehadiran'" 
                            :class="activeTab === 'kehadiran' ? 'border-emerald-600 text-emerald-700 font-bold' : 'border-transparent text-slate-400 hover:text-slate-700'"
                            class="pb-3 text-xs border-b-2 font-medium transition whitespace-nowrap">
                        Log Kehadiran & Cuti
                    </button>
                </div>

                <!-- Tab 1: Penempatan & Mutasi -->
                <div x-show="activeTab === 'penempatan' || activeTab === 'overview'" class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Jejak Rekam Penempatan & Mutasi Jabatan</h4>
                        <button type="button" @click="$dispatch('open-modal-assignment')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Tambah Penempatan / Mutasi</span>
                        </button>
                    </div>

                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-100">
                        @forelse($employee->assignments as $asg)
                            <div class="relative group">
                                <span class="absolute -left-6 top-1.5 w-3 h-3 rounded-full bg-emerald-600 ring-4 ring-emerald-100"></span>
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-xs text-slate-900">{{ $asg->assignment_type }}</span>
                                        <span class="text-[10px] font-semibold text-slate-400">{{ date('d M Y', strtotime($asg->start_date)) }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-emerald-700">{{ $asg->jobPosition?->title }} &bull; {{ $asg->department?->name }}</p>
                                    @if($asg->sk_number)
                                        <p class="text-[11px] text-slate-500 font-mono mt-1">Nomor SK: {{ $asg->sk_number }}</p>
                                    @endif
                                    @if($asg->notes)
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $asg->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada riwayat penempatan tambahan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Tab 2: Kompetensi & Sertifikasi -->
                <div x-show="activeTab === 'kompetensi'" x-cloak class="p-6 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Daftar Kompetensi Pegawai</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @forelse($employee->competencies as $ec)
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-[10px] font-bold uppercase text-slate-400">{{ $ec->competency->category?->name }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ec->level === 'Mahir' ? 'bg-emerald-100 text-emerald-800' : ($ec->level === 'Menengah' ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-700') }}">
                                            Level {{ $ec->level }}
                                        </span>
                                    </div>
                                    <h5 class="font-bold text-xs text-slate-900">{{ $ec->competency->name }}</h5>
                                    <p class="text-[11px] text-slate-400 mt-1">Asesor: {{ $ec->assessor_name ?? 'Tim Asesor Balai' }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">Belum ada data kompetensi terekam.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Sertifikasi Keahlian Resmi</h4>
                        <div class="space-y-2">
                            @forelse($employee->certifications as $cert)
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                    <div>
                                        <h5 class="font-bold text-xs text-slate-900">{{ $cert->name }}</h5>
                                        <p class="text-[11px] text-slate-500">{{ $cert->issuer }} &bull; No: {{ $cert->certificate_number }}</p>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Aktif s/d {{ date('Y', strtotime($cert->expiry_date)) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">Belum ada sertifikasi yang ditambahkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Dokumen -->
                <div x-show="activeTab === 'dokumen'" x-cloak class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Seluruh Berkas & Dokumen Pegawai</h4>
                        <a href="{{ route('documents.index', ['employee_id' => $employee->id]) }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                            Ke Modul Dokumen &rarr;
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($employee->documents as $doc)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                        <i data-lucide="file-check" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-xs text-slate-900 truncate max-w-[200px]">{{ $doc->title }}</h5>
                                        <p class="text-[10px] text-slate-400">{{ $doc->document_type }} &bull; {{ $doc->file_size }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('documents.download', $doc) }}" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-white rounded-lg transition">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tab 4: Kehadiran & Cuti -->
                <div x-show="activeTab === 'kehadiran'" x-cloak class="p-6 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Catatan Kehadiran 10 Hari Terakhir</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 text-[10px] font-bold uppercase">
                                        <th class="pb-2">Tanggal</th>
                                        <th class="pb-2">Masuk</th>
                                        <th class="pb-2">Pulang</th>
                                        <th class="pb-2">Total Jam</th>
                                        <th class="pb-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($employee->attendanceRecords as $att)
                                        <tr>
                                            <td class="py-2.5 font-medium text-slate-800">{{ date('d M Y', strtotime($att->date)) }}</td>
                                            <td class="py-2.5 text-slate-600 font-mono">{{ $att->clock_in ?? '-' }}</td>
                                            <td class="py-2.5 text-slate-600 font-mono">{{ $att->clock_out ?? '-' }}</td>
                                            <td class="py-2.5 font-bold text-slate-800">{{ $att->work_hours }} Jam</td>
                                            <td class="py-2.5">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $att->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : ($att->status === 'terlambat' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                                    {{ ucfirst($att->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-4 text-center text-slate-400">Belum ada catatan absensi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Tambah Penempatan / Mutasi Baru -->
    <div x-data="{ open: false }" 
         x-on:open-modal-assignment.window="open = true" 
         x-show="open" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="open = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Catat Penempatan / Mutasi Pegawai</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('employees.assignments', $employee) }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Perubahan</label>
                    <select name="assignment_type" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="Mutasi Unit Kerja">Mutasi Unit Kerja</option>
                        <option value="Promosi Jabatan">Promosi Jabatan</option>
                        <option value="Pelaksana Tugas (Plt)">Pelaksana Tugas (Plt)</option>
                        <option value="Rotasi Internal">Rotasi Internal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Kerja Tujuan</label>
                    <select name="department_id" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $employee->department_id == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan Baru</label>
                    <select name="job_position_id" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ $employee->job_position_id == $pos->id ? 'selected' : '' }}>
                                {{ $pos->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK</label>
                        <input type="text" name="sk_number" placeholder="SK/UPT-AH/2026/..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Dasar Keputusan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan penugasan..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="open = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-xs">
                        Simpan Riwayat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

