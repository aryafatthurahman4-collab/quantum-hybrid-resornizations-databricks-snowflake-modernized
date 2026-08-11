@extends('layouts.app')
@section('title', 'Data Jabatan')

@section('content')
<div x-data="{ showModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Data Jabatan</h2>
            <p class="text-xs text-gray-500">Daftar tingkatan jabatan dan standar gaji pokok karyawan.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showModal = true" class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm transition-all">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Jabatan</span>
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card p-4 mb-6 bg-white border border-gray-200">
        <form method="GET" action="{{ route('jabatan.index') }}" class="flex items-center gap-3">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama jabatan..." 
                       class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            </div>
            <button type="submit" class="h-9 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all flex items-center gap-1.5">
                <i class="bi bi-search"></i> Cari
            </button>
            @if(request('q'))
            <a href="{{ route('jabatan.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            @endif
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="table-header">
                    <tr class="table-row">
                        <th class="table-head">Nama Jabatan</th>
                        <th class="table-head">Level</th>
                        <th class="table-head">Gaji Pokok</th>
                        <th class="table-head text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jabatan as $j)
                    <tr class="table-row">
                        <td class="table-cell font-medium text-gray-900">
                            {{ $j->nama_jabatan }}
                        </td>
                        <td class="table-cell">
                            <x-badge variant="default">Level {{ $j->level }}</x-badge>
                        </td>
                        <td class="table-cell font-mono text-gray-600">
                            Rp {{ number_format($j->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td class="table-cell text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('jabatan.edit', $j) }}" 
                                   class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                    <i class="bi bi-pencil text-sm"></i>
                                </a>
                                <form action="{{ route('jabatan.destroy', $j) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jabatan ini?')">
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
                        <td colspan="4" class="table-cell text-center text-gray-500 py-12">Belum ada data jabatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Quick Create Jabatan -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" @click.away="showModal = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-200" @click.stop>
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                    <i class="bi bi-award text-indigo-600"></i> Tambah Jabatan Baru
                </h3>
                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('jabatan.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" required placeholder="Contoh: Senior Manager / Supervisor" 
                           class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Level Hirarki</label>
                    <select name="level" required class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">- Pilih Level -</option>
                        <option value="Direksi">Direksi</option>
                        <option value="Manager">Manager</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Gaji Pokok Standar (Rp)</label>
                    <input type="number" name="gaji_pokok" min="0" required placeholder="5000000" 
                           class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-sm flex items-center gap-1.5">
                        <i class="bi bi-check2-circle"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

