@extends('layouts.app')
@section('title', 'Rekap Absensi Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 print:hidden">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                    <i class="bi bi-file-earmark-bar-graph text-xl"></i>
                </span>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Rekapitulasi Absensi Karyawan</h2>
                    <p class="text-sm text-gray-500">Laporan ringkasan presensi, keterlambatan, dan ketidakhadiran per periode</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" class="flex items-center gap-2 bg-white p-1.5 rounded-xl border border-gray-200 shadow-sm">
                <select name="bulan" class="bg-transparent text-xs font-semibold text-gray-600 border-0 focus:ring-0 py-1.5 px-3">
                    @foreach(range(1,12) as $m)
                        @php $mVal = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $mVal }}" {{ $bulan == $mVal ? 'selected' : '' }} class="bg-white">
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('id')->monthName }}
                        </option>
                    @endforeach
                </select>
                <div class="h-4 w-px bg-slate-200 bg-gray-100"></div>
                <select name="tahun" class="bg-transparent text-xs font-semibold text-gray-600 border-0 focus:ring-0 py-1.5 px-3">
                    @foreach(range(now()->year, now()->year-4) as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }} class="bg-white">{{ $t }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition-all">
                    <i class="bi bi-funnel-fill text-xs"></i> Filter
                </button>
            </form>

            <x-button variant="secondary" href="{{ route('laporan.absensi.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank">
                <i class="bi bi-file-earmark-pdf text-rose-500"></i> Export PDF
            </x-button>
            <x-button variant="secondary" onclick="window.print()">
                <i class="bi bi-printer text-indigo-500"></i> Cetak
            </x-button>
        </div>
    </div>

    <!-- Stat Summary Cards -->
    @php
        $totalHadir = array_sum(array_column($rekap, 'hadir'));
        $totalTerlambat = array_sum(array_column($rekap, 'terlambat'));
        $totalIzinSakit = array_sum(array_column($rekap, 'izin')) + array_sum(array_column($rekap, 'sakit')) + array_sum(array_column($rekap, 'cuti'));
        $totalAlfa = array_sum(array_column($rekap, 'alfa'));
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 print:hidden">
        <div class="card p-5 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-600">
                <i class="bi bi-check-circle-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Hadir</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ number_format($totalHadir) }}</h3>
                <p class="text-[11px] text-emerald-600 font-medium">Kehadiran Karyawan</p>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-600">
                <i class="bi bi-clock-history text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Terlambat</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ number_format($totalTerlambat) }}</h3>
                <p class="text-[11px] text-amber-600 font-medium">Kejadian Keterlambatan</p>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-sky-50 border border-sky-200/60 flex items-center justify-center text-sky-600">
                <i class="bi bi-envelope-open-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Izin / Sakit / Cuti</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ number_format($totalIzinSakit) }}</h3>
                <p class="text-[11px] text-sky-600 font-medium">Ketidakhadiran Berizin</p>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-rose-50 border border-rose-200/60 flex items-center justify-center text-rose-600">
                <i class="bi bi-x-octagon-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tanpa Keterangan (Alfa)</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ number_format($totalAlfa) }}</h3>
                <p class="text-[11px] text-rose-600 font-medium">Tidak Masuk Kerja</p>
            </div>
        </div>
    </div>

    <!-- Print Header Only -->
    <div class="hidden print:block mb-6 text-center border-b pb-4">
        <h2 class="text-xl font-bold">REKAPITULASI ABSENSI KARYAWAN</h2>
        <p class="text-sm text-slate-600">Periode: {{ \Carbon\Carbon::createFromDate(null, (int)$bulan, 1)->locale('id')->monthName }} {{ $tahun }}</p>
        <p class="text-xs text-slate-500">Universitas Darma Persada - HRIS ITK</p>
    </div>

    <!-- Data Table Card -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between print:hidden">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                <h3 class="font-bold text-sm text-gray-900">Detail Presensi Karyawan ({{ count($rekap) }} Karyawan)</h3>
            </div>
            <span class="text-xs text-gray-500">
                Periode {{ \Carbon\Carbon::createFromDate(null, (int)$bulan, 1)->locale('id')->monthName }} {{ $tahun }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead class="table-header">
                    <tr class="table-row">
                        <th class="table-head">Karyawan</th>
                        <th class="table-head">Jabatan / Unit</th>
                        <th class="table-head text-center">Hadir</th>
                        <th class="table-head text-center">Terlambat</th>
                        <th class="table-head text-center">Izin</th>
                        <th class="table-head text-center">Sakit</th>
                        <th class="table-head text-center">Cuti</th>
                        <th class="table-head text-center">Alfa</th>
                        <th class="table-head text-center">Tingkat Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap as $r)
                        @php
                            $k = $r['karyawan'];
                            $totalWorking = max(1, $r['total']);
                            $rate = round(($r['hadir'] / $totalWorking) * 100);
                        @endphp
                        <tr class="table-row">
                            <td class="table-cell">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                        {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-900">{{ $k->nama_lengkap }}</span>
                                        <span class="inline-block text-[11px] font-mono font-medium text-slate-400">NIP: {{ $k->nip }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell">
                                <span class="block text-xs font-semibold text-slate-800">{{ $k->jabatan->nama_jabatan ?? '-' }}</span>
                                <span class="block text-[11px] text-slate-400">{{ $k->satuanKerja->nama_divisi ?? '-' }}</span>
                            </td>
                            <td class="table-cell text-center">
                                <x-badge variant="default">{{ $r['hadir'] }}</x-badge>
                            </td>
                            <td class="table-cell text-center">
                                <x-badge variant="secondary">{{ $r['terlambat'] }}</x-badge>
                            </td>
                            <td class="table-cell text-center">
                                <x-badge variant="secondary">{{ $r['izin'] }}</x-badge>
                            </td>
                            <td class="table-cell text-center">
                                <x-badge variant="secondary">{{ $r['sakit'] }}</x-badge>
                            </td>
                            <td class="table-cell text-center">
                                <x-badge variant="secondary">{{ $r['cuti'] }}</x-badge>
                            </td>
                            <td class="table-cell text-center">
                                <x-badge variant="destructive">{{ $r['alfa'] }}</x-badge>
                            </td>
                            <td class="table-cell text-center">
                                <div class="w-full max-w-[120px] mx-auto">
                                    <div class="flex items-center justify-between text-xs mb-1 font-bold">
                                        <span class="{{ $rate >= 80 ? 'text-emerald-600' : ($rate >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ $rate }}%
                                        </span>
                                    </div>
                                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $rate >= 80 ? 'bg-emerald-500' : ($rate >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ min(100, $rate) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="table-row">
                            <td colspan="9" class="table-cell text-center text-slate-400 py-12">
                                <i class="bi bi-inbox text-3xl block mb-2 opacity-50"></i>
                                Tidak ada data absensi untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

