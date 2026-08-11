@extends('layouts.app')
@section('title', 'Komponen Gaji')

@section('content')
<div x-data="{ showModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Komponen Gaji</h2>
            <p class="text-xs text-gray-500">Master data tunjangan, bonus, dan potongan payroll.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showModal = true" class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm transition-all">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Komponen</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4 mb-6 bg-white border border-gray-200">
        <form method="GET" action="{{ route('komponen-gaji.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Kode / Nama..." 
                       class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            </div>
            <div>
                <select name="tipe" class="w-full h-9 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">-- Semua Tipe --</option>
                    <option value="penghasilan" {{ request('tipe') == 'penghasilan' ? 'selected' : '' }}>Penghasilan (Tunjangan/Bonus)</option>
                    <option value="potongan" {{ request('tipe') == 'potongan' ? 'selected' : '' }}>Potongan</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 h-9 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all flex items-center justify-center gap-1">
                    <i class="bi bi-search"></i> Cari
                </button>
                @if(request('q') || request('tipe'))
                <a href="{{ route('komponen-gaji.index') }}" class="h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-all flex items-center justify-center" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3.5 px-4">Kode</th>
                        <th class="py-3.5 px-4">Nama Komponen</th>
                        <th class="py-3.5 px-4">Tipe</th>
                        <th class="py-3.5 px-4">Sifat</th>
                        <th class="py-3.5 px-4">Nilai Standar</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($komponen as $k)
                    <tr class="hover:bg-gray-100/40 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-gray-500">
                            {{ $k->kode }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-gray-900">
                            {{ $k->nama }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($k->tipe == 'penghasilan')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 capitalize">
                                Penghasilan
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 capitalize">
                                Potongan
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-gray-500 capitalize">
                            {{ $k->sifat }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-semibold text-gray-600">
                            Rp {{ number_format($k->nilai, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('komponen-gaji.edit', $k) }}" 
                                   class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                    <i class="bi bi-pencil text-sm"></i>
                                </a>
                                <form action="{{ route('komponen-gaji.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus komponen gaji ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 text-xs">Belum ada komponen gaji terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $komponen->links() }}
    </div>

    <!-- Modal Quick Create Komponen Gaji -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" @click.away="showModal = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-200" @click.stop>
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                    <i class="bi bi-cash-stack text-indigo-600"></i> Tambah Komponen Gaji Baru
                </h3>
                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('komponen-gaji.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Komponen</label>
                        <input type="text" name="kode" required placeholder="TNJ_MAKAN" 
                               class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Komponen</label>
                        <input type="text" name="nama" required placeholder="Tunjangan Makan" 
                               class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe</label>
                        <select name="tipe" required class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="penghasilan">Penghasilan (Tunjangan/Bonus)</option>
                            <option value="potongan">Potongan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sifat</label>
                        <select name="sifat" required class="w-full h-10 px-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="tetap">Tetap</option>
                            <option value="variable">Variable</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nilai Standar (Rp)</label>
                    <input type="number" name="nilai" min="0" required placeholder="500000" 
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

