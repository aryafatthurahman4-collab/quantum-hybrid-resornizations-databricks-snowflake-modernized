@extends('layouts.app')
@section('title', 'Payroll & Penggajian')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Penggajian & Payroll</h2>
        <p class="text-xs text-gray-500">Riwayat penggajian, kalkulasi Take Home Pay, dan cetak slip gaji.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('laporan.penggajian') }}" class="inline-flex items-center gap-1.5 h-9 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition-all">
            <i class="bi bi-file-earmark-text"></i> Export Laporan
        </a>
        @if(in_array(Auth::user()->role, ['admin','atasan']))
        <x-button variant="default" href="{{ route('penggajian.create') }}">
            <i class="bi bi-calculator"></i>
            <span>Hitung Gaji Individu</span>
        </x-button>
        <form action="{{ route('penggajian.hitung-semua') }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-2 h-9 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all"
                    onclick="return confirm('Kalkulasi ulang gaji seluruh karyawan aktif untuk periode bulan ini?')">
                <i class="bi bi-cpu"></i>
                <span>Hitung Semua Gaji Karyawan</span>
            </button>
        </form>
        @endif
    </div>
</div>

<!-- Filter Bar -->
<div class="card p-4 mb-6 bg-white border border-gray-200">
    <form method="GET" action="{{ route('penggajian.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div>
            <input type="month" name="periode" value="{{ request('periode') }}" 
                   class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div>
            <select name="status" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Status --</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
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
            @if(request('periode') || request('status') || request('karyawan_id'))
            <a href="{{ route('penggajian.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
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
                    <th class="table-head">Periode</th>
                    <th class="table-head">Take Home Pay</th>
                    <th class="table-head">Status Pembayaran</th>
                    <th class="table-head text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penggajian as $p)
                <tr class="table-row">
                    <td class="table-cell font-medium text-gray-900">
                        {{ $p->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="table-cell font-mono text-gray-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($p->periode.'-01')->format('F Y') }}
                    </td>
                    <td class="table-cell font-mono font-bold text-gray-900 text-sm">
                        Rp {{ number_format($p->total_diterima, 0, ',', '.') }}
                    </td>
                    <td class="table-cell">
                        @php
                            $badgeVariant = match($p->status) {
                                'dibayar' => 'default',
                                'dikonfirmasi' => 'default',
                                default => 'secondary'
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant" class="capitalize">
                            {{ $p->status }}
                        </x-badge>
                    </td>
                    <td class="table-cell text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('penggajian.show', $p) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Rincian Gaji">
                                <i class="bi bi-eye text-sm"></i>
                            </a>
                            <a href="{{ route('penggajian.slip', $p) }}" target="_blank"
                               class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors" title="Cetak Slip Gaji">
                                <i class="bi bi-printer text-sm"></i>
                            </a>
                            @if($p->status == 'draft' && in_array(Auth::user()->role, ['admin','atasan']))
                            <form action="{{ route('penggajian.konfirmasi', $p) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors" title="Konfirmasi Payroll" onclick="return confirm('Konfirmasi payroll ini?')">
                                    <i class="bi bi-check-lg text-base"></i>
                                </button>
                            </form>
                            @endif
                            @if($p->status == 'dikonfirmasi' && in_array(Auth::user()->role, ['admin','atasan']))
                            <form action="{{ route('penggajian.bayar', $p) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors" title="Tandai Sudah Dibayar" onclick="return confirm('Tandai gaji ini sudah dibayar?')">
                                    <i class="bi bi-cash text-base"></i>
                                </button>
                            </form>
                            @endif
                            @if(in_array(Auth::user()->role, ['admin','atasan']))
                            <form action="{{ route('penggajian.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data payroll ini?')">
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
                    <td colspan="5" class="table-cell text-center text-gray-500 py-12">Belum ada riwayat penggajian terproses.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $penggajian->links() }}
</div>
@endsection

