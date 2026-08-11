@extends('layouts.app')
@section('title', 'Laporan Absensi')
@section('content')
@php $fmt = function($d) { return $d ? \Carbon\Carbon::parse($d)->format('d/m/Y') : '-'; }; @endphp

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Absensi & Kehadiran</h2>
        <p class="text-xs text-gray-500">Periode: <b>{{ $fmt($startDate) }}</b> s/d <b>{{ $fmt($endDate) }}</b>
            @if($startTime || $endTime) | Jam: <b>{{ $startTime ?? 'Mulai' }}</b> - <b>{{ $endTime ?? 'Selesai' }}</b> @endif
        </p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('laporan.absensi.excel', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-excel text-sm"></i> Excel</a>
        <a href="{{ route('laporan.absensi.word', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-word text-sm"></i> Word</a>
        <a href="{{ route('laporan.absensi.pdf', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-pdf text-sm"></i> PDF</a>
        <a href="{{ route('laporan.absensi.pptx', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-slides text-sm"></i> PPTX</a>
        <button onclick="window.print()" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold shadow-sm transition-all"><i class="bi bi-printer text-sm"></i> Cetak</button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('laporan.absensi') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Selesai</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Masuk (Min)</label>
            <input type="time" name="start_time" value="{{ $startTime }}" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Masuk (Max)</label>
            <input type="time" name="end_time" value="{{ $endTime }}" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 h-10 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('laporan.absensi') }}" class="flex-1 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold shadow-sm transition-all flex items-center justify-center gap-1.5"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

<div x-data="{ tab: 'rekap' }">
    <div class="flex items-center gap-2 mb-4">
        <button @click="tab = 'rekap'" :class="tab === 'rekap' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"><i class="bi bi-table"></i> Rekapitulasi Kehadiran</button>
        <button @click="tab = 'detail'" :class="tab === 'detail' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"><i class="bi bi-list-ul"></i> Rincian Detail</button>
    </div>

    <div x-show="tab === 'rekap'" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100/50 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3 px-4">NIP</th><th>Nama</th><th>Unit</th><th class="text-center">Hadir</th><th class="text-center">Terlambat</th><th class="text-center">Izin</th><th class="text-center">Sakit</th><th class="text-center">Cuti</th><th class="text-center">DL</th><th class="text-center">Alfa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @foreach($rekap as $r)
                    <tr class="hover:bg-gray-100/40 transition-colors">
                        <td class="py-3 px-4 font-mono text-gray-500">{{ $r['nip'] }}</td>
                        <td class="font-semibold text-gray-900">{{ $r['nama'] }}</td>
                        <td class="text-gray-500">{{ $r['unit'] }}</td>
                        <td class="text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $r['hadir'] }}</span></td>
                        <td class="text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">{{ $r['terlambat'] }}</span></td>
                        <td class="text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">{{ $r['izin'] }}</span></td>
                        <td class="text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200">{{ $r['sakit'] }}</span></td>
                        <td class="text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-bold bg-violet-50 text-violet-700 border border-violet-200">{{ $r['cuti'] }}</span></td>
                        <td class="text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $r['dinas_luar'] }}</span></td>
                        <td class="text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ $r['alfa'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="tab === 'detail'" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100/50 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3 px-4">Tanggal</th><th>NIP</th><th>Nama</th><th>Jabatan</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Status</th><th>Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($details as $d)
                    <tr class="hover:bg-gray-100/40 transition-colors">
                        <td class="py-3 px-4 font-mono text-gray-500">{{ $fmt($d->tanggal) }}</td>
                        <td class="font-mono text-gray-500">{{ $d->karyawan->nip ?? '-' }}</td>
                        <td class="font-semibold text-gray-900">{{ $d->karyawan->nama_lengkap ?? '-' }}</td>
                        <td class="text-gray-600">{{ $d->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                        <td>{{ $d->jam_masuk ?? '-' }}</td>
                        <td>{{ $d->jam_pulang ?? '-' }}</td>
                        <td>
                            @php $sc = match($d->status) {'hadir'=>'bg-emerald-50 text-emerald-700 border-emerald-200','terlambat'=>'bg-amber-50 text-amber-700 border-amber-200','izin'=>'bg-blue-50 text-blue-700 border-blue-200','sakit'=>'bg-gray-100 text-gray-600 border-gray-200','cuti'=>'bg-violet-50 text-violet-700 border-violet-200','dinas_luar'=>'bg-slate-100 text-slate-600 border-slate-200',default=>'bg-rose-50 text-rose-700 border-rose-200'}; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border capitalize {{ $sc }}">{{ ucfirst($d->status) }}</span>
                        </td>
                        <td class="text-gray-500 text-[11px]">{{ $d->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-12 text-center text-gray-400 text-xs">Tidak ada rincian data absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
