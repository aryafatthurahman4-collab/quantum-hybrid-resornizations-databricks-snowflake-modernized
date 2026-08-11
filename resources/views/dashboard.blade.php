@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php 
    $user = Auth::user(); 
    $data = $data ?? [];
    $absensiHariIni = $data['absensi_hari_ini'] ?? null;
@endphp

<!-- Welcome Banner & Quick Action Toolbar -->
<div style="background-color: #0f172a;" class="relative overflow-hidden rounded-2xl p-5 md:p-6 text-white shadow-lg border border-indigo-900/50 mb-6">
    <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="h-11 w-11 rounded-xl bg-indigo-600 text-white font-extrabold text-lg flex items-center justify-center shadow-md flex-shrink-0 border border-indigo-400/30">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-base md:text-lg font-bold text-white tracking-tight">Selamat Datang, {{ $user->name }}!</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/40 text-indigo-200 border border-indigo-400/30 uppercase tracking-wider">{{ $user->role }}</span>
                </div>
                <p class="text-xs text-slate-300 mt-0.5 font-medium">Akses cepat seluruh fungsi & fitur utama portal HRIS ITK.</p>
            </div>
        </div>

        <!-- Action Toolbar Buttons -->
        <div class="flex items-center gap-2 flex-wrap">
            @if($user->isAdmin())
            <a href="{{ route('karyawan.create') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-person-plus-fill text-sm"></i>
                <span>+ Karyawan</span>
            </a>
            <a href="{{ route('jabatan.create') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-briefcase-fill text-sm"></i>
                <span>+ Jabatan</span>
            </a>
            <a href="{{ route('satuan-kerja.create') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-building-fill text-sm"></i>
                <span>+ Unit Kerja</span>
            </a>
            <a href="{{ route('import.index') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-file-earmark-arrow-up-fill text-sm"></i>
                <span>Import Excel</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-pie-chart-fill text-sm"></i>
                <span>Pusat Laporan</span>
            </a>
            @elseif($user->isAtasan())
            <a href="{{ route('tugas.create') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-plus-circle-fill text-sm"></i>
                <span>Buat Tugas</span>
            </a>
            <a href="{{ route('penilaian.create') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-star-fill text-sm"></i>
                <span>Penilaian Kinerja</span>
            </a>
            <a href="{{ route('pengajuan-izin.index') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-envelope-open-fill text-sm"></i>
                <span>Persetujuan Izin</span>
            </a>
            <a href="{{ route('absensi.rekap') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-table text-sm"></i>
                <span>Rekap Absensi</span>
            </a>
            @else
            <a href="{{ route('pengajuan-izin.create') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-plus-circle-fill text-sm"></i>
                <span>Ajukan Izin / Cuti</span>
            </a>
            <a href="{{ route('tugas.index') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-journal-text text-sm"></i>
                <span>Tugas Saya</span>
            </a>
            <a href="{{ route('penggajian.index') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="bi bi-wallet2 text-sm"></i>
                <span>Slip Gaji</span>
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Attendance Alert Box for Karyawan -->
@if($user && $user->isKaryawan() && !$absensiHariIni)
<div class="mb-6 p-4 sm:p-5 rounded-2xl bg-amber-50 border border-amber-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm animate-in">
    <div class="flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <h3 class="font-bold text-sm text-slate-900">Presensi Hari Ini Belum Tercatat</h3>
            <p class="text-xs text-slate-600 mt-0.5">Silakan catat kehadiran (Absen Hadir) untuk hari ini.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('absensi.harian') }}">
        @csrf
        <button type="submit" name="status" value="hadir" 
                class="inline-flex items-center gap-2 h-9 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs transition-all shadow-sm">
            <i class="bi bi-check-circle-fill"></i>
            <span>Absen Hadir Sekarang</span>
        </button>
    </form>
</div>
@endif

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @if($user->isAdmin())
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Karyawan</span>
            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg ring-1 ring-indigo-500/10">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['total_karyawan'] ?? 0 }}</span>
            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                <i class="bi bi-arrow-up-short"></i> Active
            </span>
        </div>
        <p class="text-xs text-slate-400 font-medium mt-2">Seluruh Satuan Kerja ITK</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Presensi Hari Ini</span>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg ring-1 ring-emerald-500/10">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['total_absensi_hari_ini'] ?? 0 }}</span>
            <span class="text-xs text-slate-500 font-semibold">Pegawai</span>
        </div>
        <p class="text-xs text-slate-400 font-medium mt-2">Sudah mengisi kehadiran</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Izin / Cuti Pending</span>
            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg ring-1 ring-amber-500/10">
                <i class="bi bi-envelope-exclamation-fill"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['total_pengajuan_menunggu'] ?? 0 }}</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200">
                Menunggu
            </span>
        </div>
        <p class="text-xs text-slate-400 font-medium mt-2">Memerlukan persetujuan</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tugas Aktif</span>
            <div class="h-10 w-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-lg ring-1 ring-violet-500/10">
                <i class="bi bi-list-task"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['total_tugas_aktif'] ?? 0 }}</span>
            <span class="text-xs text-slate-500 font-semibold">Proyek</span>
        </div>
        <p class="text-xs text-slate-400 font-medium mt-2">Dalam proses pengerjaan</p>
    </div>

    @elseif($user->isAtasan())
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tugas Diberikan</span>
            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg ring-1 ring-indigo-500/10">
                <i class="bi bi-list-check"></i>
            </div>
        </div>
        <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['total_tugas_diberikan'] ?? 0 }}</span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tugas Aktif</span>
            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg ring-1 ring-amber-500/10">
                <i class="bi bi-clock-fill"></i>
            </div>
        </div>
        <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['tugas_aktif'] ?? 0 }}</span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Penilaian Dibuat</span>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg ring-1 ring-emerald-500/10">
                <i class="bi bi-star-fill"></i>
            </div>
        </div>
        <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['total_penilaian_dibuat'] ?? 0 }}</span>
    </div>

    @else
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Presensi Hari Ini</span>
            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg ring-1 ring-indigo-500/10">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
        </div>
        <span class="text-xl font-bold text-slate-900">
            {{ $data['absensi_hari_ini'] ? ucfirst($data['absensi_hari_ini']->status) : '-' }}
        </span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Penugasan</span>
            <div class="h-10 w-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-lg ring-1 ring-violet-500/10">
                <i class="bi bi-journal-text"></i>
            </div>
        </div>
        <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['total_tugas'] ?? 0 }}</span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tugas On Progress</span>
            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg ring-1 ring-amber-500/10">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $data['tugas_aktif'] ?? 0 }}</span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Skor Kinerja Terakhir</span>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg ring-1 ring-emerald-500/10">
                <i class="bi bi-award-fill"></i>
            </div>
        </div>
        <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">
            {{ $data['penilaian_terbaru'] ? number_format($data['penilaian_terbaru']->nilai_akhir, 1) : '-' }}
        </span>
    </div>
    @endif
</div>

<!-- Admin Dashboard Tables Grid -->
@if($user->isAdmin())
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Presensi Terbaru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="bi bi-calendar-check-fill text-sm"></i>
                </div>
                <h3 class="font-bold text-sm text-slate-900">Presensi Hari Ini</h3>
            </div>
            <a href="{{ route('absensi.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <i class="bi bi-chevron-right text-[10px]"></i>
            </a>
        </div>
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/60 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3 px-6">Karyawan</th>
                        <th class="py-3 px-4">Jam Masuk</th>
                        <th class="py-3 px-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($data['absensi_terbaru'] ?? [] as $a)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3.5 px-6 font-semibold text-slate-900">
                            {{ $a->karyawan->nama_lengkap ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 font-mono">
                            {{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            @php
                                $badgeStyle = match($a->status) {
                                    'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-slate-100 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize {{ $badgeStyle }}">
                                {{ $a->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bi bi-inbox text-3xl block mb-2 text-slate-300"></i>
                            Belum ada data presensi hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pengajuan Izin Menunggu -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="bi bi-envelope-open-fill text-sm"></i>
                </div>
                <h3 class="font-bold text-sm text-slate-900">Pengajuan Izin Pending</h3>
            </div>
            <a href="{{ route('pengajuan-izin.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <i class="bi bi-chevron-right text-[10px]"></i>
            </a>
        </div>
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/60 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3 px-6">Karyawan</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-6 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($data['pengajuan_terbaru'] ?? [] as $p)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3.5 px-6 font-semibold text-slate-900">
                            {{ $p->karyawan->nama_lengkap ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 capitalize">
                                {{ $p->jenis }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right text-slate-500 font-mono">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bi bi-check-all text-3xl block mb-2 text-slate-300"></i>
                            Tidak ada pengajuan izin yang menunggu persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Atasan Dashboard Tables Grid -->
@if($user->isAtasan())
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="bi bi-envelope-open-fill text-sm"></i>
                </div>
                <h3 class="font-bold text-sm text-slate-900">Pengajuan Menunggu Persetujuan</h3>
            </div>
            <a href="{{ route('pengajuan-izin.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <i class="bi bi-chevron-right text-[10px]"></i>
            </a>
        </div>
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/60 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3 px-6">Karyawan</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-6 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($data['pengajuan_menunggu'] ?? [] as $p)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3.5 px-6 font-semibold text-slate-900">
                            {{ $p->karyawan->nama_lengkap ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 capitalize">
                                {{ $p->jenis }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right text-slate-500 font-mono">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bi bi-check-all text-3xl block mb-2 text-slate-300"></i>
                            Tidak ada pengajuan izin yang menunggu persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
