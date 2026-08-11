@extends('layouts.app')
@section('title', 'Data Absensi')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Data Presensi & Kehadiran</h2>
        <p class="text-xs text-gray-500">Catatan riwayat presensi harian karyawan.</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        @if(Auth::user()->isKaryawan())
        <form method="POST" action="{{ route('absensi.harian') }}" class="inline">
            @csrf
            <button type="submit" name="status" value="hadir" 
                    class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all">
                <i class="bi bi-check-circle-fill"></i>
                <span>Absen Hadir Hari Ini</span>
            </button>
        </form>
        @endif
        @if(Auth::user()->isAdmin() || Auth::user()->isAtasan())
        <x-button variant="default" href="{{ route('absensi.create') }}">
            <i class="bi bi-calendar-plus"></i>
            <span>Catat Absensi Manual</span>
        </x-button>
        <x-button variant="secondary" href="{{ route('absensi.rekap') }}">
            <i class="bi bi-table"></i>
            <span>Rekap Bulanan</span>
        </x-button>
        @endif
    </div>
</div>

<!-- Filter Bar -->
<div class="card p-4 mb-6 bg-white border border-gray-200">
    <form method="GET" action="{{ route('absensi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                   class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div>
            <select name="status" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Status --</option>
                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="alfa" {{ request('status') == 'alfa' ? 'selected' : '' }}>Alfa</option>
            </select>
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
            @if(request('tanggal') || request('status') || request('karyawan_id'))
            <a href="{{ route('absensi.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
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
                    <th class="table-head">Tanggal</th>
                    <th class="table-head">Nama Karyawan</th>
                    <th class="table-head">Jam Masuk</th>
                    <th class="table-head">Jam Pulang</th>
                    <th class="table-head">Status</th>
                    <th class="table-head text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $a)
                <tr class="table-row">
                    <td class="table-cell font-mono text-gray-900 whitespace-nowrap">
                        {{ $a->tanggal?->format('d M Y') }}
                    </td>
                    <td class="table-cell font-medium text-gray-900">
                        {{ $a->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="table-cell font-mono text-gray-500">
                        {{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}
                    </td>
                    <td class="table-cell font-mono text-gray-500">
                        {{ $a->jam_pulang ? \Carbon\Carbon::parse($a->jam_pulang)->format('H:i') : '-' }}
                    </td>
                    <td class="table-cell">
                        @php
                            $badgeVariant = match($a->status) {
                                'hadir' => 'default',
                                'terlambat' => 'secondary',
                                'sakit', 'izin', 'cuti' => 'default',
                                'alfa' => 'destructive',
                                default => 'outline'
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">
                            {{ str_replace('_', ' ', $a->status) }}
                        </x-badge>
                    </td>
                    <td class="table-cell text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('absensi.edit', $a) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                <i class="bi bi-pencil text-sm"></i>
                            </a>
                            <form action="{{ route('absensi.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Hapus catatan presensi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="table-row">
                    <td colspan="6" class="table-cell text-center text-gray-500 py-12">Belum ada data presensi teratatan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $absensi->links() }}
</div>
@endsection

