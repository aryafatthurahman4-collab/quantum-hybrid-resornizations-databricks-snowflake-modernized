@extends('layouts.app')
@section('title', 'Edit Satuan Kerja')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-pencil-square text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Edit Satuan Kerja</h2>
                <p class="text-xs text-gray-500">Perbarui data unit {{ $satuanKerja->nama_divisi ?? $satuanKerja->nama_unit }}</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('satuan-kerja.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('satuan-kerja.update', $satuanKerja) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Nama Unit / Divisi</label>
                    <x-input type="text" name="nama_unit" value="{{ old('nama_unit', $satuanKerja->nama_divisi ?? $satuanKerja->nama_unit) }}" required />
                </div>

                <div>
                    <label class="label">Kode Singkatan</label>
                    <x-input type="text" name="singkatan" value="{{ old('singkatan', $satuanKerja->singkatan) }}" required />
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('satuan-kerja.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

