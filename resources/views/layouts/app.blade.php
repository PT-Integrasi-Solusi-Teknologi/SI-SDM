<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f8faf9]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SI-SDM' }} — Asrama Haji Terpadu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full overflow-hidden text-slate-800 antialiased selection:bg-emerald-500 selection:text-white" x-data="{ sidebarOpen: false }">
    <div class="h-full flex flex-col lg:flex-row overflow-hidden">
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" x-cloak 
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs lg:hidden transition-opacity"></div>

        <!-- SIDEBAR (Permanen Terkunci di Layar Desktop, Scroll Hanya di Dalam Sidebar Jika Diperlukan) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-100 flex flex-col h-screen transition-transform duration-300 ease-in-out lg:static lg:h-screen lg:shrink-0">
            
            <!-- Brand / Logo (Header Sidebar Terkunci) -->
            <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 font-bold text-xl tracking-wider">
                        AH
                    </div>
                    <div>
                        <span class="block text-lg font-bold tracking-tight text-slate-900 leading-tight">SI-SDM</span>
                        <span class="block text-xs font-semibold text-emerald-600 uppercase tracking-wider">Asrama Haji Terpadu</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation Links (Hanya area ini yang scroll jika tinggi layar lebih pendek) -->
            <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto">
                <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</div>

                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Dashboard SDM</span>
                </a>

                <a href="{{ route('employees.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('employees.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="users" class="w-5 h-5 {{ request()->routeIs('employees.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Data Pegawai</span>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-semibold {{ request()->routeIs('employees.*') ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ \App\Models\Employee::count() }}</span>
                </a>

                <a href="{{ route('organization.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('organization.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="network" class="w-5 h-5 {{ request()->routeIs('organization.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Organisasi & Bagan</span>
                </a>

                <a href="{{ route('attendance.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('attendance.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="calendar-check" class="w-5 h-5 {{ request()->routeIs('attendance.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Kehadiran & Absensi</span>
                </a>

                <a href="{{ route('leaves.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('leaves.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="calendar-off" class="w-5 h-5 {{ request()->routeIs('leaves.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Cuti & Izin</span>
                    @php $pendingCount = \App\Models\LeaveRequest::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-700 animate-pulse">{{ $pendingCount }}</span>
                    @endif
                </a>

                <div class="px-3 pt-4 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Kompetensi & Kinerja</div>

                <a href="{{ route('competencies.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('competencies.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="award" class="w-5 h-5 {{ request()->routeIs('competencies.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Kompetensi & Sertifikat</span>
                </a>

                <a href="{{ route('performance.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('performance.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="trending-up" class="w-5 h-5 {{ request()->routeIs('performance.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Penilaian Kinerja</span>
                </a>

                <a href="{{ route('documents.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('documents.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="file-text" class="w-5 h-5 {{ request()->routeIs('documents.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Dokumen Pegawai</span>
                </a>

                <div class="px-3 pt-4 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Laporan & Sistem</div>

                <a href="{{ route('reports.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('reports.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="printer" class="w-5 h-5 {{ request()->routeIs('reports.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Laporan Eksekutif</span>
                </a>

                <a href="{{ route('settings.index') }}" 
                   class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('settings.*') ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="shield-check" class="w-5 h-5 {{ request()->routeIs('settings.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Audit Log & Akun</span>
                </a>
            </nav>

            <!-- Footer Sidebar: User Profile (Footer Sidebar Terkunci) -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 shrink-0">
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=80' }}" 
                         alt="User Avatar" class="w-10 h-10 rounded-xl object-cover ring-2 ring-emerald-500/20">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name ?? 'Admin SDM' }}</p>
                        <p class="text-[11px] text-emerald-600 font-medium capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? 'Admin SDM') }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Keluar" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-white rounded-lg transition">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA (Scroll Independen Tanpa Menggeser Sidebar) -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
            <!-- TOPBAR -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4 shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">
                            {{ $pageTitle ?? 'Dashboard SDM' }}
                        </h1>
                        <p class="text-xs text-slate-400 hidden sm:block">
                            Ekosistem SI-AH Terpadu &bull; Balai Asrama Haji
                        </p>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3">
                    <!-- Global Search Box -->
                    <form action="{{ route('employees.index') }}" method="GET" class="relative hidden md:block w-64 lg:w-72">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="search" placeholder="Cari pegawai, NIP, jabatan..." 
                               value="{{ request('search') }}"
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    </form>

                    <!-- ROLE SWITCHER PILL (Quick Demo Switcher) -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs font-semibold hover:bg-emerald-100/70 transition shadow-2xs">
                            <i data-lucide="user-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span class="hidden sm:inline">Peran:</span>
                            <span class="capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? 'Admin SDM') }}</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-emerald-600"></i>
                        </button>

                        <div x-show="open" x-cloak 
                             class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 py-2 z-50">
                            <div class="px-4 py-1.5 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                Ganti Peran Demo
                            </div>
                            <a href="{{ route('switch-role', 'admin_sdm') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                <span class="w-2 h-2 rounded-full {{ auth()->user()->role === 'admin_sdm' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                Admin SDM (Penuh)
                            </a>
                            <a href="{{ route('switch-role', 'pimpinan') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                <span class="w-2 h-2 rounded-full {{ auth()->user()->role === 'pimpinan' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                Pimpinan (Kepala UPT)
                            </a>
                            <a href="{{ route('switch-role', 'pegawai') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                <span class="w-2 h-2 rounded-full {{ auth()->user()->role === 'pegawai' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                Pegawai (Self Service)
                            </a>
                            <a href="{{ route('switch-role', 'superadmin') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                <span class="w-2 h-2 rounded-full {{ auth()->user()->role === 'superadmin' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                Super Administrator
                            </a>
                        </div>
                    </div>

                    <!-- Date Badge -->
                    <div class="hidden xl:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-600">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- FLASH NOTIFICATIONS -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5 text-xs font-semibold">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5 text-xs font-semibold">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div x-data="{ show: true }" x-show="show" class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5 text-xs font-semibold">
                        <i data-lucide="info" class="w-4 h-4 text-sky-600 shrink-0"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button @click="show = false" class="text-sky-500 hover:text-sky-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600"></i>
                            <span>Periksa kembali isian formulir:</span>
                        </div>
                        <button @click="show = false" class="text-amber-500 hover:text-amber-700">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-amber-800">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- PAGE BODY -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="px-6 py-4 border-t border-slate-100 bg-white text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2 shrink-0">
                <p>&copy; 2026 SI-AH Terpadu &bull; Modul SI-SDM Asrama Haji. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4 text-slate-500">
                    <a href="{{ route('reports.index') }}" class="hover:text-emerald-600">Laporan</a>
                    <span>&bull;</span>
                    <a href="{{ route('settings.index') }}" class="hover:text-emerald-600">Audit Trail</a>
                    <span>&bull;</span>
                    <span class="text-emerald-600 font-semibold">v1.0 Demo Operasional</span>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
