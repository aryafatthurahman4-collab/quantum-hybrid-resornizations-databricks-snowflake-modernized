@extends('layouts.app')
@section('title', 'Laporan Karyawan')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Data Karyawan</h2>
        <p class="text-xs text-gray-500">Seluruh data karyawan aktif dengan filter kustom</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('laporan.karyawan.excel', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-excel text-sm"></i> Excel</a>
        <a href="{{ route('laporan.karyawan.word', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-word text-sm"></i> Word</a>
        <a href="{{ route('laporan.karyawan.pdf', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-pdf text-sm"></i> PDF</a>
        <a href="{{ route('laporan.karyawan.pptx', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-slides text-sm"></i> PPTX</a>
        <button onclick="window.print()" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold shadow-sm transition-all"><i class="bi bi-printer text-sm"></i> Cetak</button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('laporan.karyawan') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jabatan</label>
            <select name="jabatan_id" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Jabatan --</option>
                @foreach($jabatans as $jab)
                    <option value="{{ $jab->id }}" {{ request('jabatan_id') == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Satuan Kerja</label>
            <select name="satuan_kerja_id" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Satuan Kerja --</option>
                @foreach($satuanKerjas as $sk)
                    <option value="{{ $sk->id }}" {{ request('satuan_kerja_id') == $sk->id ? 'selected' : '' }}>{{ $sk->nama_unit ?? $sk->nama_satuan }} ({{ $sk->singkatan }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 h-10 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('laporan.karyawan') }}" class="flex-1 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold shadow-sm transition-all flex items-center justify-center gap-1.5"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100/50 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                    <th class="py-3.5 px-4">NIP</th>
                    <th class="py-3.5 px-4">Nama</th>
                    <th class="py-3.5 px-4">Jabatan</th>
                    <th class="py-3.5 px-4">Unit</th>
                    <th class="py-3.5 px-4">Tgl Masuk</th>
                    <th class="py-3.5 px-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs">
                @forelse($karyawan as $k)
                <tr class="hover:bg-gray-100/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono font-semibold text-gray-500">{{ $k->nip }}</td>
                    <td class="py-3.5 px-4 font-semibold text-gray-900">{{ $k->nama_lengkap }}</td>
                    <td class="py-3.5 px-4 text-gray-600">{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
                    <td class="py-3.5 px-4"><span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-gray-100 text-gray-600">{{ $k->satuanKerja->singkatan ?? '-' }}</span></td>
                    <td class="py-3.5 px-4 text-gray-500">{{ $k->tanggal_masuk ?: '-' }}</td>
                    <td class="py-3.5 px-4">
                        @if($k->aktif)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200"><span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Nonaktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-xs">Tidak ada data karyawan yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
