<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f8faf9]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SI-SDM Asrama Haji Terpadu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 mx-auto rounded-3xl bg-emerald-600 flex items-center justify-center text-white shadow-xl shadow-emerald-600/20 font-extrabold text-2xl tracking-wider">
                AH
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">SI-SDM Asrama Haji</h1>
            <p class="text-xs text-slate-500 max-w-xs mx-auto">
                Sistem Informasi Pengelolaan Sumber Daya Manusia Terpadu &bull; Ekosistem SI-AH 1447H
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 space-y-6">
            <!-- Flash Errors -->
            @if($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('info'))
                <div class="p-3.5 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', 'sdm@asramahaji.go.id') }}" required placeholder="email@asramahaji.go.id"
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                        <span class="text-[10px] text-emerald-600 font-semibold">Default: password</span>
                    </div>
                    <input type="password" name="password" value="password" required
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition font-mono">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>Ingat saya di peramban</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- Quick Demo One-Click Access -->
            <div class="pt-5 border-t border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block text-center mb-3">
                    Akses Cepat Pengujian (1-Klik)
                </span>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('switch-role', 'admin_sdm') }}" class="p-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-center text-[11px] font-bold transition border border-emerald-100">
                        Admin SDM
                    </a>
                    <a href="{{ route('switch-role', 'pimpinan') }}" class="p-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 text-center text-[11px] font-bold transition border border-blue-100">
                        Kepala UPT
                    </a>
                    <a href="{{ route('switch-role', 'pegawai') }}" class="p-2.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-800 text-center text-[11px] font-bold transition border border-teal-100">
                        Pegawai
                    </a>
                    <a href="{{ route('switch-role', 'superadmin') }}" class="p-2.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-800 text-center text-[11px] font-bold transition border border-purple-100">
                        Super Admin
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center text-[11px] text-slate-400">
            &copy; 2026 Balai UPT Asrama Haji Terpadu &bull; SI-AH Terpadu
        </p>
    </div>
</body>
</html>

