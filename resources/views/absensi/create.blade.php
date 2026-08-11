@extends('layouts.app')
@section('title', 'Catat Absensi')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-calendar-check text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Catat Absensi Manual</h2>
                <p class="text-xs text-gray-500">Input catatan kehadiran dan waktu kerja harian karyawan</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('absensi.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('absensi.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="label">Pilih Karyawan</label>
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
                    <label class="label">Tanggal</label>
                    <x-input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required />
                </div>

                <div>
                    <label class="label">Status Kehadiran</label>
                    <select name="status" required class="input">
                        <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir (On Time)</option>
                        <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat (Late)</option>
                        <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit (Sick)</option>
                        <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin (Permit)</option>
                        <option value="cuti" {{ old('status') == 'cuti' ? 'selected' : '' }}>Cuti (Leave)</option>
                        <option value="dinas_luar" {{ old('status') == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                        <option value="alfa" {{ old('status') == 'alfa' ? 'selected' : '' }}>Alfa (Absent)</option>
                    </select>
                </div>

                <div>
                    <label class="label">Jam Masuk</label>
                    <x-input type="time" name="jam_masuk" value="{{ old('jam_masuk', '08:00') }}" />
                </div>

                <div>
                    <label class="label">Jam Pulang</label>
                    <x-input type="time" name="jam_pulang" value="{{ old('jam_pulang', '17:00') }}" />
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="label">Catatan / Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan opsional..." class="input">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('absensi.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Presensi
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

