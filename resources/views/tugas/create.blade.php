@extends('layouts.app')
@section('title', 'Buat Tugas Baru')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-clipboard-plus text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Buat Tugas Karyawan</h2>
                <p class="text-xs text-gray-500">Delegasikan penugasan kerja baru kepada staf</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('tugas.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('tugas.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="label">Judul Tugas</label>
                    <x-input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Penyusunan Laporan Tahunan SDM" />
                </div>

                <div>
                    <label class="label">Penerima Tugas (Karyawan)</label>
                    <select name="karyawan_id" required class="input">
                        <option value="">- Pilih Karyawan -</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nip }} &mdash; {{ $k->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label">Tenggat Waktu (Deadline)</label>
                    <x-input type="date" name="tenggat" value="{{ old('tenggat') }}" />
                </div>

                <div>
                    <label class="label">Tingkat Prioritas</label>
                    <select name="prioritas" required class="input">
                        <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah (Low)</option>
                        <option value="sedang" {{ old('prioritas', 'sedang') == 'sedang' ? 'selected' : '' }}>Sedang (Medium)</option>
                        <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi (High)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="label">Deskripsi & Instruksi Tugas</label>
                    <textarea name="deskripsi" rows="4" placeholder="Detail instruksi penugasan..." class="input">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('tugas.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Penugasan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

