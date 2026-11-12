@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ modalReview: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Evaluasi & Penilaian Kinerja Pegawai</h2>
            <p class="text-xs text-slate-500 mt-0.5">Indeks Kinerja Pegawai (IKP), target capaian operasional, dan leaderboard kinerja.</p>
        </div>
        <button @click="modalReview = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Input Evaluasi Kinerja Baru</span>
        </button>
    </div>

    <!-- ROW 1: 3-COLUMN METRIC WIDGETS (Mirip Persis Desain Screen 2) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Total Performance Bar Overview (Col 5) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Ikhtisar Kinerja Keseluruhan</h3>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Tahun 2026</span>
                </div>
                <div class="flex items-baseline gap-2 my-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $averageScore }}%</span>
                    <span class="text-xs font-bold text-emerald-600 flex items-center">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5 mr-0.5"></i> Optimal
                    </span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    Rata-rata capaian target pelayanan, penanganan kedatangan jamaah, dan kesiapan fasilitas kamar asrama.
                </p>

                <!-- Simulated Monthly Bar Chart -->
                <div class="h-28 w-full flex items-end justify-between gap-2 pt-3 border-t border-slate-100">
                    @php $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']; @endphp
                    @foreach($months as $idx => $m)
                        @php $val = 75 + ($idx * 3) + rand(0, 4); @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5">
                            <div class="w-full bg-slate-100 rounded-t-md h-20 flex items-end">
                                <div class="w-full bg-emerald-500 rounded-t-md hover:bg-emerald-600 transition" style="height: {{ $val }}%"></div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $m }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between mt-3">
                <span>Nilai Ambang Batas: 75%</span>
                <span class="font-bold text-emerald-700">Tercapai 100%</span>
            </div>
        </div>

        <!-- Performance by Category (Col 4) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Capaian per Bidang Layanan</h3>
                    <span class="text-[10px] text-slate-400">Target Semester</span>
                </div>

                <div class="space-y-3.5 mt-2">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-bold text-slate-800">Pelayanan Jamaah & Akomodasi</span>
                            <span class="font-mono font-bold text-emerald-700">92.4%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: 92.4%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-bold text-slate-800">Pemeliharaan Sarpras & Utilitas Listrik</span>
                            <span class="font-mono font-bold text-emerald-700">88.5%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-500 h-full rounded-full" style="width: 88.5%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-bold text-slate-800">Kepegawaian & Tata Usaha</span>
                            <span class="font-mono font-bold text-emerald-700">86.2%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-cyan-500 h-full rounded-full" style="width: 86.2%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-bold text-slate-800">Keamanan Kawasan & Protokol K3</span>
                            <span class="font-mono font-bold text-emerald-700">89.0%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full rounded-full" style="width: 89%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-bold text-slate-800">Unit TI & Command Center</span>
                            <span class="font-mono font-bold text-emerald-700">94.8%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-full rounded-full" style="width: 94.8%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 mt-4">
                <span>Distribusi Predikat:</span>
                <span class="font-bold text-slate-800">{{ $ratingDistribution['sangat_baik'] }} Sangat Baik &bull; {{ $ratingDistribution['baik'] }} Baik</span>
            </div>
        </div>

        <!-- Top Performers (Col 3) -->
        <div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Top 5 Performer</h3>
                    <i data-lucide="award" class="w-4 h-4 text-amber-500"></i>
                </div>

                <div class="space-y-3">
                    @foreach($topPerformers as $idx => $tp)
                        <div class="flex items-center justify-between p-2 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="w-4 text-center font-extrabold text-xs {{ $idx === 0 ? 'text-amber-500' : 'text-slate-400' }}">
                                    #{{ $idx + 1 }}
                                </span>
                                <div class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-[11px]">
                                    {{ $tp->employee->initials }}
                                </div>
                                <div>
                                    <h5 class="font-bold text-xs text-slate-900 leading-tight truncate max-w-[110px]">{{ $tp->employee->name }}</h5>
                                    <p class="text-[9px] text-slate-400 truncate max-w-[110px]">{{ $tp->employee->department?->name }}</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-xs text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">
                                {{ $tp->score }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-center mt-3">
                <span class="text-[10px] text-slate-400">Pemberian insentif kinerja per semester</span>
            </div>
        </div>
    </div>

    <!-- ROW 2: DETAILED REVIEWS TABLE -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Hasil Evaluasi Kinerja Pegawai (Performance Table)</h3>
                <p class="text-xs text-slate-400">Total data evaluasi: {{ $reviews->total() }} Pegawai</p>
            </div>
            
            <form action="{{ route('performance.index') }}" method="GET" class="flex items-center gap-2">
                <select name="period_id" onchange="this.form.submit()" class="py-1.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                    @foreach($periods as $per)
                        <option value="{{ $per->id }}" {{ $selectedPeriodId == $per->id ? 'selected' : '' }}>
                            {{ $per->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3.5 px-4">Pegawai</th>
                        <th class="py-3.5 px-4">Unit Kerja & Jabatan</th>
                        <th class="py-3.5 px-4">Nilai Kinerja</th>
                        <th class="py-3.5 px-4">Predikat</th>
                        <th class="py-3.5 px-4">Capaian & Evaluasi</th>
                        <th class="py-3.5 px-4">Penilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $rev)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ $rev->employee->initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('employees.show', $rev->employee) }}" class="font-bold text-slate-900 hover:text-emerald-600 block">
                                            {{ $rev->employee->name }}
                                        </a>
                                        <span class="text-[10px] font-mono text-emerald-700 bg-emerald-50 px-1 rounded">{{ $rev->employee->employee_code }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $rev->employee->jobPosition?->title }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $rev->employee->department?->name }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="text-sm font-extrabold text-slate-900 font-mono">{{ $rev->score }}%</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $rev->rating === 'Sangat Baik' ? 'bg-emerald-100 text-emerald-800' : ($rev->rating === 'Baik' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $rev->rating }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 max-w-sm text-slate-600 leading-relaxed">
                                <span class="font-medium text-slate-800 block">{{ $rev->achievements ?? 'Mencapai seluruh target penugasan sesuai SOP.' }}</span>
                                @if($rev->feedback)
                                    <span class="text-[10px] text-slate-400 italic block mt-0.5">Catatan: "{{ $rev->feedback }}"</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-500">
                                <span class="font-semibold text-slate-700 block">{{ $rev->evaluator_name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $rev->review_date ? date('d M Y', strtotime($rev->review_date)) : '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="text-xs">Belum ada evaluasi untuk periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL: INPUT EVALUASI BARU -->
    <div x-show="modalReview" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalReview = false">
            <h3 class="text-sm font-bold text-slate-900">Form Input Penilaian Kinerja Pegawai</h3>
            <form action="{{ route('performance.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Periode Penilaian</label>
                    <select name="period_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($periods as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Pegawai yang Dinilai</label>
                    <select name="employee_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }} - {{ $emp->department?->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Skor Kinerja (0 - 100)</label>
                    <input type="number" step="0.1" name="score" value="88.5" min="0" max="100" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">&ge;90: Sangat Baik, &ge;75: Baik, &ge;60: Cukup, &lt;60: Kurang</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pejabat Penilai</label>
                    <input type="text" name="evaluator_name" value="Drs. H. Ahmad Fauzi, M.M." required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Capaian Kinerja</label>
                    <textarea name="achievements" rows="2" placeholder="Capaian target utama selama masa tugas..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Saran / Rekomendasi Pengembangan</label>
                    <textarea name="feedback" rows="2" placeholder="Rekomendasi tindak lanjut atau usulan diklat..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalReview = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Evaluasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

