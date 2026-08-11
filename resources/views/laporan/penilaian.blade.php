@extends('layouts.app')
@section('title', 'Laporan Penilaian')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Penilaian Kinerja</h2>
        <p class="text-xs text-gray-500">Hasil evaluasi kinerja karyawan dengan nilai akhir dan grade</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('laporan.penilaian.excel', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-excel text-sm"></i> Excel</a>
        <a href="{{ route('laporan.penilaian.word', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-word text-sm"></i> Word</a>
        <a href="{{ route('laporan.penilaian.pdf', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-pdf text-sm"></i> PDF</a>
        <a href="{{ route('laporan.penilaian.pptx', request()->all()) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-white font-semibold text-xs shadow-sm transition-all"><i class="bi bi-file-earmark-slides text-sm"></i> PPTX</a>
        <button onclick="window.print()" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold shadow-sm transition-all"><i class="bi bi-printer text-sm"></i> Cetak</button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('laporan.penilaian') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Karyawan</label>
            <select name="karyawan_id" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Karyawan --</option>
                @foreach($karyawans as $kar)
                    <option value="{{ $kar->id }}" {{ request('karyawan_id') == $kar->id ? 'selected' : '' }}>{{ $kar->nama_lengkap }} ({{ $kar->nip }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Periode (Tahun-Bulan)</label>
            <input type="month" name="periode" value="{{ request('periode') }}" class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 h-10 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('laporan.penilaian') }}" class="flex-1 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold shadow-sm transition-all flex items-center justify-center gap-1.5"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100/50 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                    <th class="py-3.5 px-4">Nama</th><th>NIP</th><th>Penilai</th><th>Periode</th><th>Nilai Akhir</th><th>Grade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs">
                @forelse($penilaian as $p)
                @php $grade = $p->nilai_akhir >= 80 ? 'Sangat Baik' : ($p->nilai_akhir >= 60 ? 'Cukup' : 'Perlu Perbaikan'); $gc = $p->nilai_akhir >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($p->nilai_akhir >= 60 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200'); @endphp
                <tr class="hover:bg-gray-100/40 transition-colors">
                    <td class="py-3.5 px-4 font-semibold text-gray-900">{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                    <td class="py-3.5 px-4 font-mono text-gray-500">{{ $p->karyawan->nip ?? '-' }}</td>
                    <td class="py-3.5 px-4 text-gray-600">{{ $p->penilai->name ?? '-' }}</td>
                    <td class="py-3.5 px-4 text-gray-500">{{ $p->periode }}</td>
                    <td class="py-3.5 px-4 font-semibold text-gray-900">{{ number_format($p->nilai_akhir, 2) }}</td>
                    <td class="py-3.5 px-4"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $gc }}">{{ $grade }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-xs">Tidak ada data penilaian kinerja.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
