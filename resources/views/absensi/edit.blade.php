@extends('layouts.app')
@section('title', 'Edit Absensi')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-pencil-square text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Edit Catatan Presensi</h2>
                <p class="text-xs text-gray-500">Perbarui status dan jam presensi karyawan</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('absensi.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('absensi.update', $absensi) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="label">Tanggal</label>
                    <x-input type="date" name="tanggal" value="{{ old('tanggal', $absensi->tanggal?->format('Y-m-d')) }}" required />
                </div>

                <div>
                    <label class="label">Status Kehadiran</label>
                    <select name="status" required class="input">
                        <option value="hadir" {{ old('status', $absensi->status) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ old('status', $absensi->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="sakit" {{ old('status', $absensi->status) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="izin" {{ old('status', $absensi->status) == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="cuti" {{ old('status', $absensi->status) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="dinas_luar" {{ old('status', $absensi->status) == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                        <option value="alfa" {{ old('status', $absensi->status) == 'alfa' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div>

                <div>
                    <label class="label">Jam Masuk</label>
                    <x-input type="time" name="jam_masuk" value="{{ old('jam_masuk', $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '') }}" />
                </div>

                <div>
                    <label class="label">Jam Pulang</label>
                    <x-input type="time" name="jam_pulang" value="{{ old('jam_pulang', $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '') }}" />
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="label">Catatan / Keterangan</label>
                    <textarea name="keterangan" rows="2" class="input">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('absensi.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

