@extends('layouts.app')
@section('title', 'Edit Karyawan')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-pencil-square text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Edit Data Karyawan</h2>
                <p class="text-xs text-gray-500">Perbarui biodata & status kepegawaian {{ $karyawan->nama_lengkap }}</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('karyawan.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('karyawan.update', $karyawan) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Kepegawaian -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <i class="bi bi-briefcase text-indigo-500"></i> Informasi Kepegawaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="label">NIK / NIP</label>
                        <x-input type="text" name="nip" value="{{ old('nip', $karyawan->nip) }}" required />
                    </div>

                    <div>
                        <label class="label">Nama Lengkap</label>
                        <x-input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required />
                    </div>

                    <div>
                        <label class="label">Tanggal Masuk</label>
                        <x-input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk ? \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('Y-m-d') : '') }}" required />
                    </div>

                    <div>
                        <label class="label">Jabatan</label>
                        <select name="jabatan_id" required class="input">
                            @foreach($jabatan as $j)
                                <option value="{{ $j->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Satuan Kerja / Unit</label>
                        <select name="satuan_kerja_id" required class="input">
@foreach($satuanKerja as $s)
                                <option value="{{ $s->id }}" {{ old('satuan_kerja_id', $karyawan->satuan_kerja_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Status Kepegawaian</label>
                        <select name="status_kepegawaian" class="input">
                            <option value="tetap" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="kontrak" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="magang" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'magang' ? 'selected' : '' }}>Magang</option>
                            <option value="honorer" {{ old('status_kepegawaian', $karyawan->status_kepegawaian) == 'honorer' ? 'selected' : '' }}>Honorer</option>
                        </select>
                    </div>

                    <div>
                        <label class="label">Status Aktif</label>
                        <select name="aktif" class="input">
                            <option value="1" {{ old('aktif', $karyawan->aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('aktif', $karyawan->aktif) == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Biodata Pribadi -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-indigo-500"></i> Biodata Pribadi & Kontak
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="label">Tempat Lahir</label>
                        <x-input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" />
                    </div>

                    <div>
                        <label class="label">Tanggal Lahir</label>
                        <x-input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir ? \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('Y-m-d') : '') }}" />
                    </div>

                    <div>
                        <label class="label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="input">
                            <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki' || old('jenis_kelamin', $karyawan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' || old('jenis_kelamin', $karyawan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="label">No. Telepon / WhatsApp</label>
                        <x-input type="text" name="no_telp" value="{{ old('no_telp', old('no_telepon', $karyawan->no_telp ?? $karyawan->no_telepon)) }}" />
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="input">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('karyawan.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

