@extends('layouts.app')
@section('title', 'Izin & Cuti')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Pengajuan Izin & Cuti</h2>
        <p class="text-xs text-gray-500">Pengajuan serta status persetujuan alokasi izin/cuti karyawan.</p>
    </div>
    <x-button variant="default" href="{{ route('pengajuan-izin.create') }}">
        <i class="bi bi-plus-lg"></i>
        <span>Buat Pengajuan Baru</span>
    </x-button>
</div>

<!-- Filter Bar -->
<div class="card p-4 mb-6 bg-white border border-gray-200">
    <form method="GET" action="{{ route('pengajuan-izin.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div>
            <select name="status" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Status --</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div>
            <select name="jenis" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Jenis Izin --</option>
                <option value="izin" {{ request('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="cuti" {{ request('jenis') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="dinas_luar" {{ request('jenis') == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
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
            @if(request('status') || request('jenis') || request('karyawan_id'))
            <a href="{{ route('pengajuan-izin.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
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
                    <th class="table-head">Jenis Izin</th>
                    <th class="table-head">Periode Tanggal</th>
                    <th class="table-head">Alasan</th>
                    <th class="table-head">Status</th>
                    <th class="table-head text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $p)
                <tr class="table-row">
                    <td class="table-cell font-medium text-gray-900">
                        {{ $p->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="table-cell">
                        <x-badge variant="default" class="capitalize">{{ str_replace('_', ' ', $p->jenis) }}</x-badge>
                    </td>
                    <td class="table-cell font-mono text-gray-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                    </td>
                    <td class="table-cell text-gray-500 max-w-xs truncate">
                        {{ $p->alasan ?: '-' }}
                    </td>
                    <td class="table-cell">
                        @php
                            $badgeVariant = match($p->status) {
                                'disetujui' => 'default',
                                'ditolak' => 'destructive',
                                default => 'secondary'
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant" class="capitalize">
                            {{ $p->status }}
                        </x-badge>
                    </td>
                    <td class="table-cell text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            @if(in_array(Auth::user()->role, ['admin','atasan']) && $p->status == 'menunggu')
                            <form action="{{ route('pengajuan-izin.approve', $p) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="disetujui">
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all" onclick="return confirm('Setujui pengajuan ini?')">
                                    <i class="bi bi-check-lg"></i>
                                    <span>Setujui</span>
                                </button>
                            </form>
                            <form action="{{ route('pengajuan-izin.approve', $p) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs shadow-sm transition-all" onclick="return confirm('Tolak pengajuan ini?')">
                                    <i class="bi bi-x-lg"></i>
                                    <span>Tolak</span>
                                </button>
                            </form>
                            @endif

                            @if($p->status == 'menunggu' || Auth::user()->isAdmin())
                            <form action="{{ route('pengajuan-izin.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengajuan ini?')">
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
                    <td colspan="6" class="table-cell text-center text-gray-500 py-12">Belum ada data pengajuan izin/cuti.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $pengajuan->links() }}
</div>
@endsection

