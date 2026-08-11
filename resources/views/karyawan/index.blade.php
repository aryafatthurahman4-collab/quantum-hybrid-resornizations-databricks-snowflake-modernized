@extends('layouts.app')
@section('title', 'Data Karyawan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Data Karyawan</h2>
        <p class="text-xs text-gray-500">Kelola informasi profil, jabatan, unit kerja, dan status pegawai ITK.</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('laporan.karyawan') }}" class="inline-flex items-center gap-1.5 h-9 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition-all">
            <i class="bi bi-file-earmark-text"></i> Export Laporan
        </a>
        <x-button variant="default" href="{{ route('karyawan.create') }}">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Karyawan Baru</span>
        </x-button>
    </div>
</div>

<!-- Filter Bar -->
<div class="card p-4 mb-6 bg-white border border-gray-200">
    <form method="GET" action="{{ route('karyawan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama / NIP / Email..." 
                   class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div>
            <select name="jabatan_id" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Jabatan --</option>
                @foreach($jabatanList as $j)
                    <option value="{{ $j->id }}" {{ request('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="satuan_kerja_id" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">-- Semua Unit Kerja --</option>
                @foreach($satuanKerjaList as $sk)
                    <option value="{{ $sk->id }}" {{ request('satuan_kerja_id') == $sk->id ? 'selected' : '' }}>{{ $sk->nama_unit ?? $sk->nama_satuan }} ({{ $sk->singkatan }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 h-9 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all flex items-center justify-center gap-1">
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="{{ route('karyawan.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="table-header">
                <tr class="table-row">
                    <th class="table-head">NIP</th>
                    <th class="table-head">Nama Lengkap</th>
                    <th class="table-head">Jabatan</th>
                    <th class="table-head">Satuan Kerja</th>
                    <th class="table-head">Status</th>
                    <th class="table-head text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawan as $k)
                <tr class="table-row">
                    <td class="table-cell font-mono text-gray-500">
                        {{ $k->nip }}
                    </td>
                    <td class="table-cell font-medium text-gray-900">
                        <div class="flex items-center gap-2.5">
                            <div class="h-7 w-7 rounded-full bg-gray-100 text-indigo-600 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                            </div>
                            <span>{{ $k->nama_lengkap }}</span>
                        </div>
                    </td>
                    <td class="table-cell text-gray-600">
                        {{ $k->jabatan->nama_jabatan ?? '-' }}
                    </td>
                    <td class="table-cell">
                        <x-badge variant="secondary">{{ $k->satuanKerja->singkatan ?? '-' }}</x-badge>
                    </td>
                    <td class="table-cell">
                        @if($k->aktif)
                        <x-badge variant="default"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-1"></span> Aktif</x-badge>
                        @else
                        <x-badge variant="destructive"><span class="h-1.5 w-1.5 rounded-full bg-rose-500 mr-1"></span> Nonaktif</x-badge>
                        @endif
                    </td>
                    <td class="table-cell text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('karyawan.show', $k) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Detail">
                                <i class="bi bi-eye text-sm"></i>
                            </a>
                            <a href="{{ route('karyawan.edit', $k) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                <i class="bi bi-pencil text-sm"></i>
                            </a>
                            <form action="{{ route('karyawan.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan ini?')">
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
                    <td colspan="6" class="table-cell text-center text-gray-500 py-12">Belum ada data karyawan terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $karyawan->links() }}
</div>
@endsection

