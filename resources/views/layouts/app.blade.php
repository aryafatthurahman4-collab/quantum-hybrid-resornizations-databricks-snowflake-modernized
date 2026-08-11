<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg" type="image/svg+xml">
    <title>@yield('title', 'Dashboard') | HRIS ITK</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50/60 text-slate-900 selection:bg-indigo-600 selection:text-white" x-data="{ sidebarOpen: false }">
    @auth
    <div class="min-h-screen flex bg-slate-50/60">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden" 
             @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-sm lg:shadow-none">
            
            <!-- Sidebar Header / Logo -->
            <div class="h-16 px-6 flex items-center justify-between border-b border-slate-100">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-violet-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-all duration-200">
                        <i class="bi bi-person-badge-fill text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-base tracking-tight bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-800 bg-clip-text text-transparent">HRIS ITK</span>
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-600 border border-indigo-200/60">v1.0</span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium leading-none mt-0.5">Human Resource Portal</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            @php
                $route = Route::currentRouteName();
                $user = Auth::user();
                $role = $user->role;
            @endphp

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6 custom-scrollbar">
                <!-- Overview Section -->
                <div>
                    <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Overview</div>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                        <i class="bi bi-grid-1x2-fill text-base w-5 h-5 flex items-center justify-center"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Master Data (Admin Only) -->
                @if($role === 'admin')
                <div>
                    <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Master Data</div>
                    <div class="space-y-1">
                        <a href="{{ route('jabatan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'jabatan') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-briefcase-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Jabatan</span>
                        </a>
                        <a href="{{ route('satuan-kerja.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'satuan-kerja') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-building-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Satuan Kerja</span>
                        </a>
                        <a href="{{ route('karyawan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'karyawan') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-people-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Karyawan</span>
                        </a>
                        <a href="{{ route('komponen-gaji.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'komponen-gaji') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-cash-stack text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Komponen Gaji</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Transaksi Section -->
                <div>
                    <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Aktivitas & Transaksi</div>
                    <div class="space-y-1">
                        <a href="{{ route('absensi.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'absensi.index') || str_starts_with($route, 'absensi.create') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-calendar-check-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Absensi Presensi</span>
                        </a>

                        @if(in_array($role, ['admin', 'atasan']))
                        <a href="{{ route('absensi.rekap') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'absensi.rekap') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-file-earmark-bar-graph-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Rekap Absensi</span>
                        </a>
                        @endif

                        <a href="{{ route('pengajuan-izin.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'pengajuan-izin') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-envelope-open-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Izin / Cuti</span>
                        </a>

                        <a href="{{ route('tugas.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'tugas') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-check2-square text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Penugasan Kerja</span>
                        </a>

                        <a href="{{ route('penilaian.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'penilaian') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-star-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Penilaian Kinerja</span>
                        </a>

                        <a href="{{ route('penggajian.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'penggajian') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-wallet2 text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Penggajian (Payroll)</span>
                        </a>
                    </div>
                </div>

                <!-- Tools & Reports (Admin / Atasan) -->
                @if(in_array($role, ['admin', 'atasan']))
                <div>
                    <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Laporan & Utility</div>
                    <div class="space-y-1">
                        <a href="{{ route('import.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'import') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-file-earmark-arrow-up-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Import Data Excel</span>
                        </a>
                        <a href="{{ route('laporan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ str_starts_with($route, 'laporan') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-600' }}">
                            <i class="bi bi-pie-chart-fill text-base w-5 h-5 flex items-center justify-center"></i>
                            <span>Pusat Laporan</span>
                        </a>
                    </div>
                </div>
                @endif
            </nav>

            <!-- User Profile Box at Sidebar Bottom -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate leading-tight">{{ $user->name }}</p>
                        <p class="text-[10px] text-slate-500 capitalize flex items-center gap-1 mt-0.5 font-medium">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $user->role }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Navbar -->
            <header class="h-16 bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-base font-bold text-slate-900 tracking-tight">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Notifications Dropdown -->
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" class="h-9 w-9 rounded-xl border border-slate-200/80 bg-white text-slate-500 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all flex items-center justify-center relative shadow-sm">
                            <i class="bi bi-bell text-base"></i>
                            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-indigo-600 ring-2 ring-white"></span>
                        </button>
                        <div x-show="notifOpen" @click.away="notifOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 rounded-2xl bg-white shadow-xl border border-slate-200/80 p-4 z-50">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                                <span class="font-bold text-xs text-slate-900">Notifikasi System</span>
                                <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full font-semibold">Active</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex gap-3 text-xs">
                                    <div class="h-8 w-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Sistem Running Smooth</p>
                                        <p class="text-slate-500 text-[11px]">Seluruh modul HRIS ITK berjalan lancar.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div x-data="{ userMenuOpen: false }" class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 transition-all shadow-sm focus:outline-none">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-bold text-slate-900 leading-none mb-1">{{ $user->name }}</p>
                                <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider leading-none">{{ $role }}</p>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                        </button>

                        <!-- Menu dropdown -->
                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-200/80 py-1.5 z-50 divide-y divide-slate-100">
                            <div class="px-4 py-3">
                                <p class="text-xs font-bold text-slate-900">{{ $user->name }}</p>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $user->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-indigo-50/50 hover:text-indigo-600">
                                    <i class="bi bi-speedometer2"></i> Dashboard HRIS
                                </a>
                            </div>
                            <div class="py-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50">
                                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Notifications Flash Alerts -->
                @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center justify-between shadow-sm animate-in">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-check-lg text-xl"></i>
                        </div>
                        <p class="text-xs font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('warning'))
                <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200/80 p-4 text-amber-800 flex items-center justify-between shadow-sm animate-in">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-exclamation-triangle text-lg"></i>
                        </div>
                        <p class="text-xs font-semibold">{{ session('warning') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200/80 p-4 text-rose-800 flex items-center justify-between shadow-sm animate-in">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-x-circle text-lg"></i>
                        </div>
                        <p class="text-xs font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    @stack('scripts')
</body>
</html>
