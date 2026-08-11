@extends('layouts.app')
@section('title', 'Tambah Jabatan')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-award text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Tambah Jabatan</h2>
                <p class="text-xs text-gray-500">Buat data posisi dan standar gaji pokok jabatan baru</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('jabatan.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('jabatan.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Nama Jabatan</label>
                    <x-input type="text" name="nama_jabatan" value="{{ old('nama_jabatan') }}" required placeholder="Contoh: Senior Software Engineer" />
                </div>

                <div>
                    <label class="label">Level Hirarki</label>
                    <select name="level" required class="input">
                        <option value="">- Pilih Level -</option>
                        <option value="Direksi" {{ old('level') == 'Direksi' ? 'selected' : '' }}>Direksi</option>
                        <option value="Manager" {{ old('level') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Supervisor" {{ old('level') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Staff" {{ old('level') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="label">Gaji Pokok Standar (Rp)</label>
                    <x-input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', 0) }}" min="0" required placeholder="0" />
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('jabatan.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Jabatan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

