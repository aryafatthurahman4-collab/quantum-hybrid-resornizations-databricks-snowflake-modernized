@extends('layouts.app')
@section('title', 'Ajukan Izin / Cuti')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-envelope-open text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Form Pengajuan Izin / Cuti</h2>
                <p class="text-xs text-gray-500">Buat permohonan izin ketidakhadiran kerja baru</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('pengajuan-izin.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('pengajuan-izin.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(in_array(Auth::user()->role, ['admin', 'atasan']))
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
                @endif

                <div>
                    <label class="label">Jenis Pengajuan</label>
                    <select name="jenis" required class="input">
                        <option value="">- Pilih Jenis -</option>
                        <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin Keperluan Pribadi</option>
                        <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit Dengan Surat Dokter</option>
                        <option value="cuti" {{ old('jenis') == 'cuti' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="dinas_luar" {{ old('jenis') == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar Kantor</option>
                    </select>
                </div>

                <div>
                    <label class="label">Tanggal Mulai</label>
                    <x-input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required />
                </div>

                <div>
                    <label class="label">Tanggal Selesai</label>
                    <x-input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required />
                </div>

                <div class="md:col-span-2">
                    <label class="label">Alasan Permohonan</label>
                    <textarea name="alasan" rows="3" required placeholder="Tuliskan alasan pengajuan izin atau cuti..." class="input">{{ old('alasan') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('pengajuan-izin.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-send"></i> Kirim Permohonan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

