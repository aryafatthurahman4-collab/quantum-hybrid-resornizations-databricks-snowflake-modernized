@extends('layouts.app')
@section('title', 'Detail Karyawan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600">
            <i class="bi bi-person-vcard text-xl"></i>
        </span>
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Detail Karyawan</h2>
            <p class="text-xs text-gray-500">{{ $karyawan->nama_lengkap }} ({{ $karyawan->nip }})</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('karyawan.edit', $karyawan) }}" 
           class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs shadow-md shadow-amber-500/25 transition-all">
            <i class="bi bi-pencil text-sm"></i> Edit
        </a>
        <a href="{{ route('karyawan.index') }}" 
           class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold shadow-sm transition-all">
            <i class="bi bi-arrow-left text-sm"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Data Pribadi -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="bi bi-person text-indigo-500 text-lg"></i>
            <h3 class="font-bold text-sm text-gray-900">Data Pribadi</h3>
        </div>
        <div class="p-5 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">NIP</span>
                <span class="font-semibold text-gray-900">{{ $karyawan->nip }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Nama</span>
                <span class="font-semibold text-gray-900">{{ $karyawan->nama_lengkap }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tempat Lahir</span>
                <span class="text-gray-900">{{ $karyawan->tempat_lahir ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tgl Lahir</span>
                <span class="text-gray-900">{{ $karyawan->tanggal_lahir ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Jenis Kelamin</span>
                <span class="text-gray-900">{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : ($karyawan->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Agama</span>
                <span class="text-gray-900">{{ $karyawan->agama ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status</span>
                <span class="text-gray-900">{{ $karyawan->status_perkawinan ?: '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Data Kepegawaian -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="bi bi-briefcase text-indigo-500 text-lg"></i>
            <h3 class="font-bold text-sm text-gray-900">Data Kepegawaian</h3>
        </div>
        <div class="p-5 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Jabatan</span>
                <span class="font-semibold text-gray-900">{{ $karyawan->jabatan->nama_jabatan ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Satuan Kerja</span>
                <span class="text-gray-900">{{ $karyawan->satuanKerja->nama_unit ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tgl Masuk</span>
                <span class="text-gray-900">{{ $karyawan->tanggal_masuk ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status Kepeg.</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $karyawan->status_kepegawaian == 'tetap' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                    {{ ucfirst($karyawan->status_kepegawaian ?? 'kontrak') }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status Aktif</span>
                @if($karyawan->aktif)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                </span>
                @else
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Nonaktif
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Kontak -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="bi bi-envelope text-indigo-500 text-lg"></i>
            <h3 class="font-bold text-sm text-gray-900">Kontak</h3>
        </div>
        <div class="p-5 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Email</span>
                <span class="text-gray-900">{{ $karyawan->email ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Telepon</span>
                <span class="text-gray-900">{{ $karyawan->no_telepon ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Alamat</span>
                <span class="text-gray-900 text-right max-w-[200px]">{{ $karyawan->alamat ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Pendidikan</span>
                <span class="text-gray-900">{{ $karyawan->pendidikan_terakhir ?: '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
