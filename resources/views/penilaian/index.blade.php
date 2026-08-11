@extends('layouts.app')
@section('title', 'Penilaian Kinerja')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Penilaian Kinerja (Performance)</h2>
        <p class="text-xs text-gray-500">Evaluasi kinerja berkala karyawan ITK berbasis indikator terukur.</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('laporan.penilaian') }}" class="inline-flex items-center gap-1.5 h-9 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition-all">
            <i class="bi bi-file-earmark-text"></i> Export Laporan
        </a>
        @if(in_array(Auth::user()->role, ['admin','atasan']))
        <x-button variant="default" href="{{ route('penilaian.create') }}">
            <i class="bi bi-star-fill"></i>
            <span>Buat Penilaian Baru</span>
        </x-button>
        @endif
    </div>
</div>

<!-- Filter Bar -->
<div class="card p-4 mb-6 bg-white border border-gray-200">
    <form method="GET" action="{{ route('penilaian.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
            <input type="text" name="periode" value="{{ request('periode') }}" placeholder="Cari Periode (misal: 2026-Q1 / 2026-08)..." 
                   class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        @if(!Auth::user()->isKaryawan())
        <div>
            <select name="karyawan_id" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Karyawan --</option>
                @foreach($karyawanList as $k)
                    <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }} ({{ $k->nip }})</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 h-9 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all flex items-center justify-center gap-1">
                <i class="bi bi-search"></i> Cari
            </button>
            @if(request('periode') || request('karyawan_id'))
            <a href="{{ route('penilaian.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="table-header">
                <tr class="table-row">
                    <th class="table-head">Nama Karyawan</th>
                    <th class="table-head">Penilai</th>
                    <th class="table-head">Periode</th>
                    <th class="table-head">Skor Akhir</th>
                    <th class="table-head">Predikat / Status</th>
                    <th class="table-head text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaian as $p)
                <tr class="table-row">
                    <td class="table-cell font-medium text-gray-900">
                        {{ $p->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="table-cell text-gray-500">
                        {{ $p->penilai->name ?? '-' }}
                    </td>
                    <td class="table-cell font-mono text-gray-500">
                        {{ $p->periode }}
                    </td>
                    <td class="table-cell font-mono font-bold text-indigo-600 text-sm">
                        {{ number_format($p->nilai_akhir, 2) ?? '-' }}
                    </td>
                    <td class="table-cell">
                        @php
                            $badgeVariant = match(true) {
                                $p->nilai_akhir >= 80 => 'default',
                                $p->nilai_akhir >= 60 => 'secondary',
                                default => 'destructive'
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">
                            {{ $p->nilai_akhir >= 80 ? 'Sangat Baik' : ($p->nilai_akhir >= 60 ? 'Baik' : 'Cukup') }}
                        </x-badge>
                    </td>
                    <td class="table-cell text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('penilaian.show', $p) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Lihat Rincian">
                                <i class="bi bi-eye text-sm"></i>
                            </a>
                            @if(in_array(Auth::user()->role, ['admin','atasan']))
                            <form action="{{ route('penilaian.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data penilaian ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="table-row">
                    <td colspan="6" class="table-cell text-center text-gray-500 py-12">Belum ada data penilaian kinerja.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $penilaian->links() }}
</div>
@endsection

