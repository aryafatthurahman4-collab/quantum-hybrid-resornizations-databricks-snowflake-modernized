<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\SatuanKerjaController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KomponenGajiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengajuanIzinController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::fallback(function () {
    return view('landing');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('jabatan', JabatanController::class);
        Route::resource('satuan-kerja', SatuanKerjaController::class)->parameters(['satuan-kerja' => 'satuanKerja']);
        Route::resource('komponen-gaji', KomponenGajiController::class)->parameters(['komponen-gaji' => 'komponenGaji']);
    });

    Route::middleware(['role:admin,atasan'])->group(function () {
        Route::resource('karyawan', KaryawanController::class);
        Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
        Route::post('pengajuan-izin/{pengajuanIzin}/approve', [PengajuanIzinController::class, 'approve'])->name('pengajuan-izin.approve');
        Route::resource('tugas', TugasController::class)->except(['edit', 'update']);
        Route::post('tugas/{tugas}/status', [TugasController::class, 'updateStatus'])->name('tugas.update-status');
        Route::resource('penilaian', PenilaianController::class);
        Route::post('penggajian/hitung-semua', [PenggajianController::class, 'hitungSemua'])->name('penggajian.hitung-semua');
        Route::post('penggajian/{penggajian}/konfirmasi', [PenggajianController::class, 'konfirmasi'])->name('penggajian.konfirmasi');
        Route::post('penggajian/{penggajian}/bayar', [PenggajianController::class, 'bayar'])->name('penggajian.bayar');
        Route::delete('penggajian/{penggajian}', [PenggajianController::class, 'destroy'])->name('penggajian.destroy');
        Route::get('import', [ImportController::class, 'index'])->name('import.index');
        Route::post('import/karyawan', [ImportController::class, 'importKaryawan'])->name('import.karyawan');
        Route::post('import/absensi', [ImportController::class, 'importAbsensi'])->name('import.absensi');
        Route::get('import/template-karyawan', [ImportController::class, 'downloadTemplateKaryawan'])->name('import.template-karyawan');
        Route::get('import/template-absensi', [ImportController::class, 'downloadTemplateAbsensi'])->name('import.template-absensi');
    });

    Route::middleware(['role:admin,atasan,karyawan'])->group(function () {
        Route::resource('absensi', AbsensiController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::post('absensi/harian', [AbsensiController::class, 'absenHarian'])->name('absensi.harian');
        Route::resource('pengajuan-izin', PengajuanIzinController::class)->except(['edit', 'update']);
        Route::resource('penggajian', PenggajianController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('penggajian/{penggajian}/slip', [PenggajianController::class, 'slipGaji'])->name('penggajian.slip');
    });

    Route::prefix('laporan')->name('laporan.')->middleware(['role:admin,atasan'])->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        
        Route::get('/karyawan', [LaporanController::class, 'laporanKaryawan'])->name('karyawan');
        Route::get('/karyawan/excel', [LaporanController::class, 'exportKaryawanExcel'])->name('karyawan.excel');
        Route::get('/karyawan/pdf', [LaporanController::class, 'exportKaryawanPdf'])->name('karyawan.pdf');
        Route::get('/karyawan/word', [LaporanController::class, 'exportKaryawanWord'])->name('karyawan.word');
        Route::get('/karyawan/pptx', [LaporanController::class, 'exportKaryawanPptx'])->name('karyawan.pptx');

        Route::get('/absensi', [LaporanController::class, 'laporanAbsensi'])->name('absensi');
        Route::get('/absensi/excel', [LaporanController::class, 'exportAbsensiExcel'])->name('absensi.excel');
        Route::get('/absensi/pdf', [LaporanController::class, 'exportAbsensiPdf'])->name('absensi.pdf');
        Route::get('/absensi/word', [LaporanController::class, 'exportAbsensiWord'])->name('absensi.word');
        Route::get('/absensi/pptx', [LaporanController::class, 'exportAbsensiPptx'])->name('absensi.pptx');

        Route::get('/penilaian', [LaporanController::class, 'laporanPenilaian'])->name('penilaian');
        Route::get('/penilaian/excel', [LaporanController::class, 'exportPenilaianExcel'])->name('penilaian.excel');
        Route::get('/penilaian/pdf', [LaporanController::class, 'exportPenilaianPdf'])->name('penilaian.pdf');
        Route::get('/penilaian/word', [LaporanController::class, 'exportPenilaianWord'])->name('penilaian.word');
        Route::get('/penilaian/pptx', [LaporanController::class, 'exportPenilaianPptx'])->name('penilaian.pptx');

        Route::get('/penggajian', [LaporanController::class, 'laporanPenggajian'])->name('penggajian');
        Route::get('/penggajian/excel', [LaporanController::class, 'exportPenggajianExcel'])->name('penggajian.excel');
        Route::get('/penggajian/pdf', [LaporanController::class, 'exportPenggajianPdf'])->name('penggajian.pdf');
        Route::get('/penggajian/word', [LaporanController::class, 'exportPenggajianWord'])->name('penggajian.word');
        Route::get('/penggajian/pptx', [LaporanController::class, 'exportPenggajianPptx'])->name('penggajian.pptx');
    });
});

Route::get('/install-db', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true]);
        return "Database migration and seeding completed successfully!";
    } catch (\Exception $e) {
        return "Error migrating database: " . $e->getMessage();
    }
});
