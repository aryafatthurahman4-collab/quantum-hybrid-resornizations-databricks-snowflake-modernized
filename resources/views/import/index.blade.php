@extends('layouts.app')
@section('title', 'Import Data Excel')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Import Data Excel</h2>
        <p class="text-xs text-gray-500">Unggah berkas Excel (.xlsx/.xls) untuk mengimpor data karyawan dan absensi secara massal.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Import Karyawan Card -->
    <div class="card p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900">Import Data Karyawan</h3>
                    <p class="text-xs text-gray-500">Tambahkan atau perbarui data pegawai baru.</p>
                </div>
            </div>
            <x-button variant="ghost" href="{{ route('import.template-karyawan') }}" class="mb-4">
                <i class="bi bi-download"></i> Unduh Template Karyawan (.xlsx)
            </x-button>
        </div>

        <form action="{{ route('import.karyawan') }}" method="POST" enctype="multipart/form-data" id="formImportKaryawan">
            @csrf
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-indigo-500 transition-colors bg-slate-50/50 mb-4"
                 onclick="document.getElementById('fileKaryawan').click()">
                <input type="file" name="file" id="fileKaryawan" class="hidden" accept=".xlsx,.xls,.csv" required onchange="updateFileName(this, 'karyawanLabel', 'btnImportKaryawan')">
                <i class="bi bi-cloud-arrow-up text-3xl text-indigo-600 block mb-2"></i>
                <span id="karyawanLabel" class="text-xs font-semibold text-gray-600">Pilih berkas Excel karyawan</span>
                <p class="text-[10px] text-slate-400 mt-1">Format .xlsx, .xls, .csv (Maksimal 5MB)</p>
            </div>
            <x-button variant="default" type="submit" id="btnImportKaryawan" disabled class="w-full">
                Mulai Import Karyawan
            </x-button>
        </form>
    </div>

    <!-- Import Absensi Card -->
    <div class="card p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900">Import Data Presensi</h3>
                    <p class="text-xs text-gray-500">Unggah rekap absensi mesin/Excel.</p>
                </div>
            </div>
            <x-button variant="ghost" href="{{ route('import.template-absensi') }}" class="mb-4">
                <i class="bi bi-download"></i> Unduh Template Presensi (.xlsx)
            </x-button>
        </div>

        <form action="{{ route('import.absensi') }}" method="POST" enctype="multipart/form-data" id="formImportAbsensi">
            @csrf
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-emerald-500 transition-colors bg-slate-50/50 mb-4"
                 onclick="document.getElementById('fileAbsensi').click()">
                <input type="file" name="file" id="fileAbsensi" class="hidden" accept=".xlsx,.xls,.csv" required onchange="updateFileName(this, 'absensiLabel', 'btnImportAbsensi')">
                <i class="bi bi-cloud-arrow-up text-3xl text-emerald-600 block mb-2"></i>
                <span id="absensiLabel" class="text-xs font-semibold text-gray-600">Pilih berkas Excel absensi</span>
                <p class="text-[10px] text-slate-400 mt-1">Format .xlsx, .xls, .csv (Maksimal 5MB)</p>
            </div>
            <x-button variant="default" type="submit" id="btnImportAbsensi" disabled class="w-full">
                Mulai Import Presensi
            </x-button>
        </form>
    </div>
</div>

@if(isset($logs) && $logs->count())
<div class="card overflow-hidden">
    <div class="p-5 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-bold text-sm text-gray-900">Riwayat Log Import Terakhir</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="table-header">
                <tr class="table-row">
                    <th class="table-head">Waktu</th>
                    <th class="table-head">Modul</th>
                    <th class="table-head">Nama File</th>
                    <th class="table-head">Sukses</th>
                    <th class="table-head">Gagal</th>
                    <th class="table-head">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $l)
                <tr class="table-row">
                    <td class="table-cell font-mono text-gray-500">
                        {{ $l->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="table-cell font-semibold text-gray-900 capitalize">
                        {{ $l->tipe_import }}
                    </td>
                    <td class="table-cell text-slate-600 font-mono">
                        {{ $l->nama_file }}
                    </td>
                    <td class="table-cell font-bold text-emerald-600">
                        {{ $l->berhasil }}
                    </td>
                    <td class="table-cell font-bold text-rose-600">
                        {{ $l->gagal }}
                    </td>
                    <td class="table-cell">
                        @if($l->gagal == 0)
                        <x-badge variant="default">Sukses</x-badge>
                        @else
                        <x-badge variant="secondary">Sebagian Error</x-badge>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
function updateFileName(input, labelId, btnId) {
    const label = document.getElementById(labelId);
    const btn = document.getElementById(btnId);
    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        btn.disabled = false;
    }
}
</script>
@endsection

