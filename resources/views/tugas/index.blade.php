@extends('layouts.app')
@section('title', 'Penugasan Kerja')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Penugasan Kerja</h2>
        <p class="text-xs text-gray-500">Daftar penugasan dan deadline pekerjaan karyawan.</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin','atasan']))
    <x-button variant="default" href="{{ route('tugas.create') }}">
        <i class="bi bi-plus-lg"></i>
        <span>Buat Tugas Baru</span>
    </x-button>
    @endif
</div>

<!-- Filter Bar -->
<div class="card p-4 mb-6 bg-white border border-gray-200">
    <form method="GET" action="{{ route('tugas.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Judul Tugas..." 
                   class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div>
            <select name="status" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Status --</option>
                <option value="diberikan" {{ request('status') == 'diberikan' ? 'selected' : '' }}>Diberikan</option>
                <option value="dikerjakan" {{ request('status') == 'dikerjakan' ? 'selected' : '' }}>Dalam Proses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div>
            <select name="prioritas" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Prioritas --</option>
                <option value="rendah" {{ request('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                <option value="sedang" {{ request('prioritas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="tinggi" {{ request('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 h-9 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all flex items-center justify-center gap-1">
                <i class="bi bi-search"></i> Cari
            </button>
            @if(request('q') || request('status') || request('prioritas'))
            <a href="{{ route('tugas.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
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
                    <th class="table-head">Judul Penugasan</th>
                    <th class="table-head">Penerima Tugas</th>
                    <th class="table-head">Pemberi Tugas</th>
                    <th class="table-head">Tenggat Waktu</th>
                    <th class="table-head">Status</th>
                    <th class="table-head text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tugas as $t)
                <tr class="table-row">
                    <td class="table-cell font-medium text-gray-900">
                        <div>
                            <span class="block font-semibold">{{ $t->judul }}</span>
                            @if($t->deskripsi)
                            <span class="block text-xs text-gray-500 truncate max-w-xs">{{ $t->deskripsi }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="table-cell font-medium text-gray-700">
                        {{ $t->karyawan->nama_lengkap ?? '-' }}
                    </td>
                    <td class="table-cell text-gray-500">
                        {{ $t->pemberi->name ?? '-' }}
                    </td>
                    <td class="table-cell font-mono text-gray-500 whitespace-nowrap">
                        {{ $t->tenggat ? \Carbon\Carbon::parse($t->tenggat)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="table-cell">
                        @php
                            $badgeVariant = match($t->status) {
                                'selesai' => 'default',
                                'dikerjakan' => 'default',
                                default => 'secondary'
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant" class="capitalize">
                            {{ $t->status }}
                        </x-badge>
                    </td>
                    <td class="table-cell text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            @if(Auth::user()->isKaryawan() && $t->status == 'diberikan')
                            <form action="{{ route('tugas.update-status', $t) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="dikerjakan">
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs shadow-sm transition-all">
                                    <i class="bi bi-play-fill"></i>
                                    <span>Kerjakan</span>
                                </button>
                            </form>
                            @endif

                            @if(($t->status != 'selesai'))
                            <form action="{{ route('tugas.update-status', $t) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm transition-all" onclick="return confirm('Tandai tugas ini selesai?')">
                                    <i class="bi bi-check-lg"></i>
                                    <span>Selesai</span>
                                </button>
                            </form>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin','atasan']))
                            <form action="{{ route('tugas.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
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
                    <td colspan="6" class="table-cell text-center text-gray-500 py-12">Belum ada tugas yang diberikan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $tugas->links() }}
</div>
@endsection

