<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Resmi Kepegawaian — SI-SDM Asrama Haji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11px; }
            @page { margin: 1.5cm; size: A4 portrait; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen p-4 sm:p-8">

    <!-- Top Action Toolbar (Hidden When Printing) -->
    <div class="no-print max-w-4xl mx-auto mb-6 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <a href="{{ route('reports.index') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">
            &larr; Kembali ke Dashboard Laporan
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                <span>Cetak Lembar Resmi / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl sm:shadow-lg border border-slate-200">
        <!-- KOP SURAT RESMI -->
        <div class="border-b-4 border-double border-slate-900 pb-4 mb-6 text-center">
            <h3 class="text-xs uppercase tracking-widest font-bold text-slate-600">Kementerian Agama Republik Indonesia</h3>
            <h2 class="text-sm uppercase tracking-wide font-extrabold text-slate-800">Direktorat Jenderal Penyelenggaraan Haji dan Umrah</h2>
            <h1 class="text-lg uppercase tracking-tight font-black text-slate-950 mt-0.5">Balai Unit Pelaksana Teknis (UPT) Asrama Haji Terpadu</h1>
            <p class="text-[10px] text-slate-500 mt-1">
                Jl. Raya Hankam No. 1, Pondok Gede, Jakarta Timur 13560 &bull; Telp: (021) 8088-2345 &bull; Website: www.asramahaji.kemenag.go.id
            </p>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center mb-6">
            <h2 class="text-sm font-extrabold uppercase tracking-wide text-slate-900">
                Laporan Rekapitulasi Data Kepegawaian & Kesiapan Layanan Kloter Haji 1447 H / 2026 M
            </h2>
            <p class="text-xs text-slate-500 font-mono mt-0.5">
                Nomor: B-452/UPT-AH/SDM/XI/2026 &bull; Tanggal Cetak: {{ now()->locale('id')->isoFormat('D MMMM Y') }}
            </p>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-4 gap-3 mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-center text-xs">
            <div>
                <span class="block text-[10px] text-slate-500 font-bold uppercase">Total Pegawai</span>
                <span class="text-base font-extrabold text-slate-900">{{ $employees->count() }} Orang</span>
            </div>
            <div>
                <span class="block text-[10px] text-slate-500 font-bold uppercase">PNS / ASN</span>
                <span class="text-base font-extrabold text-blue-700">{{ $employees->where('employmentType.code', 'ASN')->count() }} Orang</span>
            </div>
            <div>
                <span class="block text-[10px] text-slate-500 font-bold uppercase">PPPK</span>
                <span class="text-base font-extrabold text-purple-700">{{ $employees->where('employmentType.code', 'PPPK')->count() }} Orang</span>
            </div>
            <div>
                <span class="block text-[10px] text-slate-500 font-bold uppercase">Kontrak & Alih Daya</span>
                <span class="text-base font-extrabold text-emerald-700">{{ $employees->whereIn('employmentType.code', ['KONTRAK', 'OUTSOURCING'])->count() }} Orang</span>
            </div>
        </div>

        <!-- Tabel Data Pegawai Lengkap -->
        <table class="w-full text-left text-[11px] border-collapse mb-8">
            <thead>
                <tr class="bg-slate-100 border-y border-slate-300 text-slate-700 font-bold uppercase text-[9px]">
                    <th class="py-2 px-2 border border-slate-200 text-center w-8">No</th>
                    <th class="py-2 px-2.5 border border-slate-200">NIP / ID</th>
                    <th class="py-2 px-2.5 border border-slate-200">Nama Lengkap Pegawai</th>
                    <th class="py-2 px-2.5 border border-slate-200">Jabatan</th>
                    <th class="py-2 px-2.5 border border-slate-200">Unit Kerja</th>
                    <th class="py-2 px-2 border border-slate-200 text-center">Jenis</th>
                    <th class="py-2 px-2 border border-slate-200 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($employees as $idx => $emp)
                    <tr class="{{ $idx % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                        <td class="py-1.5 px-2 border border-slate-200 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>
                        <td class="py-1.5 px-2.5 border border-slate-200 font-mono text-[10px]">{{ $emp->nip ?: $emp->employee_code }}</td>
                        <td class="py-1.5 px-2.5 border border-slate-200 font-bold text-slate-900">{{ $emp->name }}</td>
                        <td class="py-1.5 px-2.5 border border-slate-200">{{ $emp->jobPosition?->title }}</td>
                        <td class="py-1.5 px-2.5 border border-slate-200 text-slate-700">{{ $emp->department?->name }}</td>
                        <td class="py-1.5 px-2 border border-slate-200 text-center font-semibold">{{ $emp->employmentType?->code }}</td>
                        <td class="py-1.5 px-2 border border-slate-200 text-center text-[10px]">
                            <span class="font-bold {{ $emp->is_active ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $emp->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan Pengesahan -->
        <div class="grid grid-cols-2 gap-8 text-xs pt-4">
            <div class="text-center">
                <p class="text-slate-500">Mengetahui,</p>
                <p class="font-bold text-slate-900">Kasubbag Tata Usaha & SDM</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[10px] text-slate-300 font-serif italic">[Tanda Tangan & Cap Sah]</span>
                </div>
                <p class="font-extrabold text-slate-900 underline">Dra. Endang Wulandari</p>
                <p class="text-[10px] text-slate-500 font-mono">NIP. 197408182000032002</p>
            </div>

            <div class="text-center">
                <p class="text-slate-500">Jakarta, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                <p class="font-bold text-slate-900">Kepala UPT Asrama Haji Terpadu</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[10px] text-slate-300 font-serif italic">[Tanda Tangan & Cap Sah]</span>
                </div>
                <p class="font-extrabold text-slate-900 underline">Drs. H. Ahmad Fauzi, M.M.</p>
                <p class="text-[10px] text-slate-500 font-mono">NIP. 197005151996031001</p>
            </div>
        </div>
    </div>
</body>
</html>

