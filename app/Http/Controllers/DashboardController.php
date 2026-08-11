<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use App\Models\Absensi;
use App\Models\PengajuanIzin;
use App\Models\TugasKaryawan;
use App\Models\PenilaianKinerja;
use App\Models\Penggajian;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $data = [
                'total_karyawan' => Karyawan::where('aktif', true)->count(),
                'total_jabatan' => Jabatan::count(),
                'total_satuan_kerja' => SatuanKerja::count(),
                'total_absensi_hari_ini' => Absensi::whereDate('tanggal', today())->count(),
                'total_pengajuan_menunggu' => PengajuanIzin::where('status', 'menunggu')->count(),
                'total_tugas_aktif' => TugasKaryawan::whereIn('status', ['diberikan', 'dikerjakan'])->count(),
                'total_penilaian' => PenilaianKinerja::count(),
                'total_penggajian_draft' => Penggajian::where('status', 'draft')->count(),
                'absensi_terbaru' => Absensi::with('karyawan')->whereDate('tanggal', today())->latest()->take(10)->get(),
                'pengajuan_terbaru' => PengajuanIzin::with('karyawan')->where('status', 'menunggu')->latest()->take(5)->get(),
            ];
        } elseif ($user->isAtasan()) {
            $data = [
                'total_karyawan' => Karyawan::where('aktif', true)->count(),
                'total_tugas_diberikan' => TugasKaryawan::where('pemberi_tugas', $user->id)->count(),
                'tugas_aktif' => TugasKaryawan::where('pemberi_tugas', $user->id)->whereIn('status', ['diberikan', 'dikerjakan'])->count(),
                'total_penilaian_dibuat' => PenilaianKinerja::where('penilai_id', $user->id)->count(),
                'pengajuan_menunggu' => PengajuanIzin::where('status', 'menunggu')->latest()->take(5)->get(),
                'absensi_terbaru' => Absensi::with('karyawan')->whereDate('tanggal', today())->latest()->take(5)->get(),
            ];
        } else {
            $karyawan = $user->karyawan;
            $data = [
                'absensi_hari_ini' => Absensi::where('karyawan_id', $karyawan?->id)->whereDate('tanggal', today())->first(),
                'total_tugas' => TugasKaryawan::where('karyawan_id', $karyawan?->id)->count(),
                'tugas_aktif' => TugasKaryawan::where('karyawan_id', $karyawan?->id)->whereIn('status', ['diberikan', 'dikerjakan'])->count(),
                'penilaian_terbaru' => PenilaianKinerja::where('karyawan_id', $karyawan?->id)->latest()->first(),
                'tagihan_terbaru' => Penggajian::where('karyawan_id', $karyawan?->id)->latest()->take(3)->get(),
            ];
        }

        return view('dashboard', compact('data'));
    }
}
