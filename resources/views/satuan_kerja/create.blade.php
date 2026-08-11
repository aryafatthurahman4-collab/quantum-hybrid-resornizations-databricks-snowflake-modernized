@extends('layouts.app')
@section('title', 'Tambah Satuan Kerja')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-building text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Tambah Satuan Kerja</h2>
                <p class="text-xs text-gray-500">Buat divisi atau unit kerja organisasi baru</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('satuan-kerja.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('satuan-kerja.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Nama Unit / Divisi</label>
                    <x-input type="text" name="nama_unit" value="{{ old('nama_unit') }}" required placeholder="Contoh: Divisi IT & Pengembangan" />
                </div>

                <div>
                    <label class="label">Kode Singkatan</label>
                    <x-input type="text" name="singkatan" value="{{ old('singkatan') }}" required placeholder="Contoh: IT-DEV" />
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('satuan-kerja.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Unit Kerja
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

