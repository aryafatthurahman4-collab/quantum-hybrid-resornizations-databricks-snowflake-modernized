@extends('layouts.app')
@section('title', 'Satuan Kerja')

@section('content')
<div x-data="{ showModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Satuan Kerja</h2>
            <p class="text-xs text-gray-500">Daftar unit kerja dan departemen di lingkungan ITK.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showModal = true" class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm transition-all">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Unit Kerja</span>
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card p-4 mb-6 bg-white border border-gray-200">
        <form method="GET" action="{{ route('satuan-kerja.index') }}" class="flex items-center gap-3">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari unit kerja / singkatan..." 
                       class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            </div>
            <button type="submit" class="h-9 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all flex items-center gap-1.5">
                <i class="bi bi-search"></i> Cari
            </button>
            @if(request('q'))
            <a href="{{ route('satuan-kerja.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
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
                        <th class="table-head">Nama Unit Kerja</th>
                        <th class="table-head">Singkatan / Kode</th>
                        <th class="table-head text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($satuanKerja as $s)
                    <tr class="table-row">
                        <td class="table-cell font-medium text-gray-900">
                            {{ $s->nama_unit }}
                        </td>
                        <td class="table-cell">
                            <x-badge variant="secondary" class="font-mono">{{ $s->singkatan }}</x-badge>
                        </td>
                        <td class="table-cell text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('satuan-kerja.edit', $s) }}" 
                                   class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                    <i class="bi bi-pencil text-sm"></i>
                                </a>
                                <form action="{{ route('satuan-kerja.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Hapus unit kerja ini?')">
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
                        <td colspan="3" class="table-cell text-center text-gray-500 py-12">Belum ada unit kerja terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $satuanKerja->links() }}
    </div>

    <!-- Modal Quick Create Satuan Kerja -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" @click.away="showModal = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-200" @click.stop>
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                    <i class="bi bi-building text-indigo-600"></i> Tambah Unit Kerja Baru
                </h3>
                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('satuan-kerja.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Unit Kerja</label>
                    <input type="text" name="nama_unit" required placeholder="Contoh: Teknologi Informasi / Keuangan" 
                           class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Singkatan / Kode Singkat</label>
                    <input type="text" name="singkatan" placeholder="Contoh: IT / FIN" 
                           class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="2" placeholder="Keterangan unit kerja..." 
                              class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
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

