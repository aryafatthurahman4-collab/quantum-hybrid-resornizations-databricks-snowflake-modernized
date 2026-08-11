@extends('layouts.app')
@section('title', 'Tambah Karyawan Baru')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-person-plus text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Tambah Karyawan Baru</h2>
                <p class="text-xs text-gray-500">Lengkapi formulir biodata dan data kepegawaian baru</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('karyawan.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('karyawan.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section 1: Identitas Kepegawaian -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <i class="bi bi-briefcase text-indigo-500"></i> Informasi Kepegawaian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="label">NIK / NIP</label>
                        <x-input type="text" name="nip" value="{{ old('nip') }}" required placeholder="Contoh: 3174012304950001" />
                    </div>

                    <div>
                        <label class="label">Nama Lengkap</label>
                        <x-input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Nama sesuai KTP" />
                    </div>

                    <div>
                        <label class="label">Tanggal Masuk</label>
                        <x-input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required />
                    </div>

                    <div>
                        <label class="label">Jabatan</label>
                        <select name="jabatan_id" required class="input">
                            <option value="">- Pilih Jabatan -</option>
                            @foreach($jabatan as $j)
                                <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Satuan Kerja / Unit</label>
                        <select name="satuan_kerja_id" required class="input">
                            <option value="">- Pilih Unit -</option>
@foreach($satuanKerja as $s)
                                <option value="{{ $s->id }}" {{ old('satuan_kerja_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Status Kepegawaian</label>
                        <select name="status_kepegawaian" class="input">
                            <option value="tetap" {{ old('status_kepegawaian') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="kontrak" {{ old('status_kepegawaian', 'kontrak') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="magang" {{ old('status_kepegawaian') == 'magang' ? 'selected' : '' }}>Magang</option>
                            <option value="honorer" {{ old('status_kepegawaian') == 'honorer' ? 'selected' : '' }}>Honorer</option>
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
                        <x-input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota kelahiran" />
                    </div>

                    <div>
                        <label class="label">Tanggal Lahir</label>
                        <x-input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" />
                    </div>

                    <div>
                        <label class="label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="input">
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' || old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' || old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="label">No. Telepon / WhatsApp</label>
                        <x-input type="text" name="no_telp" value="{{ old('no_telp', old('no_telepon')) }}" placeholder="081234567890" />
                    </div>

                    <div>
                        <label class="label">Alamat Email</label>
                        <x-input type="email" name="email" value="{{ old('email') }}" placeholder="karyawan@hr.com" />
                    </div>

                    <div>
                        <label class="label">Agama</label>
                        <x-input type="text" name="agama" value="{{ old('agama') }}" placeholder="Islam / Kristen / Hindu / Buddha" />
                    </div>

                    <div>
                        <label class="label">Pendidikan Terakhir</label>
                        <x-input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}" placeholder="SMA / D3 / S1 / S2" />
                    </div>

                    <div>
                        <label class="label">Status Perkawinan</label>
                        <x-input type="text" name="status_perkawinan" value="{{ old('status_perkawinan') }}" placeholder="Belum Menikah / Menikah" />
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" placeholder="Alamat domisili lengkap..." class="input">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('karyawan.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Karyawan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

