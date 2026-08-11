@extends('layouts.app')
@section('title', 'Pusat Laporan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Pusat Laporan & Analytics</h2>
        <p class="text-xs text-gray-500">Pilih jenis laporan dan format ekspor dokumen yang dibutuhkan (Excel, PDF, Word, PPTX).</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Laporan Karyawan -->
    <a href="{{ route('laporan.karyawan') }}" class="group">
        <div class="card p-6 hover:shadow-lg hover:border-indigo-500/50 transition-all duration-300 flex flex-col justify-between h-full">
            <div>
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3 class="font-bold text-base text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">
                    Laporan Data Karyawan
                </h3>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">
                    Rekap demografi, status kepegawaian, jabatan, dan satuan kerja.
                </p>
            </div>
            <div class="flex items-center gap-1 text-xs font-semibold text-indigo-600">
                <span>Buka Laporan</span>
                <i class="bi bi-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </div>
    </a>

    <!-- Laporan Absensi -->
    <a href="{{ route('laporan.absensi') }}" class="group">
        <div class="card p-6 hover:shadow-lg hover:border-indigo-500/50 transition-all duration-300 flex flex-col justify-between h-full">
            <div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <h3 class="font-bold text-base text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors">
                    Laporan Presensi & Kehadiran
                </h3>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">
                    Rekapitulasi kehadiran, keterlambatan, dan jam kerja pegawai.
                </p>
            </div>
            <div class="flex items-center gap-1 text-xs font-semibold text-emerald-600">
                <span>Buka Laporan</span>
                <i class="bi bi-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </div>
    </a>

    <!-- Laporan Penilaian Kinerja -->
    <a href="{{ route('laporan.penilaian') }}" class="group">
        <div class="card p-6 hover:shadow-lg hover:border-indigo-500/50 transition-all duration-300 flex flex-col justify-between h-full">
            <div>
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h3 class="font-bold text-base text-gray-900 mb-1 group-hover:text-amber-600 transition-colors">
                    Laporan Penilaian Kinerja
                </h3>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">
                    Evaluasi pencapaian skor kinerja dan peringkat pegawai.
                </p>
            </div>
            <div class="flex items-center gap-1 text-xs font-semibold text-amber-600">
                <span>Buka Laporan</span>
                <i class="bi bi-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </div>
    </a>

    <!-- Laporan Penggajian -->
    <a href="{{ route('laporan.penggajian') }}" class="group">
        <div class="card p-6 hover:shadow-lg hover:border-indigo-500/50 transition-all duration-300 flex flex-col justify-between h-full">
            <div>
                <div class="h-12 w-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="bi bi-wallet2"></i>
                </div>
                <h3 class="font-bold text-base text-gray-900 mb-1 group-hover:text-violet-600 transition-colors">
                    Laporan Payroll & Gaji
                </h3>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">
                    Rincian total anggaran penggajian dan status pencairan.
                </p>
            </div>
            <div class="flex items-center gap-1 text-xs font-semibold text-violet-600">
                <span>Buka Laporan</span>
                <i class="bi bi-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </div>
    </a>
</div>
@endsection

