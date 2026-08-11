<?php
namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\PenilaianKinerja;
use App\Models\Penggajian;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    // --- Helpers to fetch filtered data ---

    private function getKaryawanData(Request $request)
    {
        $query = Karyawan::with(['jabatan', 'satuanKerja'])->where('aktif', true);
        if ($request->jabatan_id) {
            $query->where('jabatan_id', $request->jabatan_id);
        }
        if ($request->satuan_kerja_id) {
            $query->where('satuan_kerja_id', $request->satuan_kerja_id);
        }
        return $query->get();
    }

    private function getAbsensiData(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $karyawan = Karyawan::where('aktif', true)->get();
        $rekap = [];

        foreach ($karyawan as $k) {
            $absensiQuery = Absensi::where('karyawan_id', $k->id)
                ->whereBetween('tanggal', [$startDate, $endDate]);

            if ($startTime) {
                $absensiQuery->where('jam_masuk', '>=', $startTime);
            }
            if ($endTime) {
                $absensiQuery->where('jam_masuk', '<=', $endTime);
            }

            $absensi = $absensiQuery->get();
            $rekap[] = [
                'nip' => $k->nip,
                'nama' => $k->nama_lengkap,
                'unit' => $k->satuanKerja?->singkatan ?? '-',
                'hadir' => $absensi->whereIn('status', ['hadir', 'terlambat'])->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'cuti' => $absensi->where('status', 'cuti')->count(),
                'dinas_luar' => $absensi->where('status', 'dinas_luar')->count(),
                'alfa' => $absensi->where('status', 'alfa')->count(),
            ];
        }

        return compact('rekap', 'startDate', 'endDate', 'startTime', 'endTime');
    }

    private function getAbsensiDetailData(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $query = Absensi::with(['karyawan.satuanKerja', 'karyawan.jabatan'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($startTime) {
            $query->where('jam_masuk', '>=', $startTime);
        }
        if ($endTime) {
            $query->where('jam_masuk', '<=', $endTime);
        }

        return $query->orderBy('tanggal', 'desc')->orderBy('jam_masuk', 'desc')->get();
    }

    private function getPenilaianData(Request $request)
    {
        $query = PenilaianKinerja::with(['karyawan', 'penilai']);
        if ($request->periode) {
            $query->where('periode', $request->periode);
        }
        if ($request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }
        return $query->latest()->get();
    }

    private function getPenggajianData(Request $request)
    {
        $query = Penggajian::with(['karyawan', 'pembuat']);
        if ($request->periode) {
            $query->where('periode', $request->periode);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        return $query->latest()->get();
    }

    // --- Web Views ---

    public function laporanKaryawan(Request $request)
    {
        $karyawan = $this->getKaryawanData($request);
        $jabatans = Jabatan::all();
        $satuanKerjas = SatuanKerja::all();
        return view('laporan.karyawan', compact('karyawan', 'jabatans', 'satuanKerjas'));
    }

    public function laporanAbsensi(Request $request)
    {
        $data = $this->getAbsensiData($request);
        $details = $this->getAbsensiDetailData($request);
        return view('laporan.absensi', array_merge($data, compact('details')));
    }

    public function laporanPenilaian(Request $request)
    {
        $penilaian = $this->getPenilaianData($request);
        $karyawans = Karyawan::where('aktif', true)->get();
        return view('laporan.penilaian', compact('penilaian', 'karyawans'));
    }

    public function laporanPenggajian(Request $request)
    {
        $penggajian = $this->getPenggajianData($request);
        $totalKeseluruhan = $penggajian->sum('total_diterima');
        return view('laporan.penggajian', compact('penggajian', 'totalKeseluruhan'));
    }

    // --- Excel Exports ---

    public function exportKaryawanExcel(Request $request)
    {
        $karyawan = $this->getKaryawanData($request);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Karyawan');

        // Headers
        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'Jabatan');
        $sheet->setCellValue('D1', 'Unit Kerja');
        $sheet->setCellValue('E1', 'Tanggal Masuk');
        $sheet->setCellValue('F1', 'Status');

        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');
        $sheet->getStyle('A1:F1')->getFont()->getColor()->setARGB('FFFFFF');

        $row = 2;
        foreach ($karyawan as $k) {
            $sheet->setCellValue('A' . $row, $k->nip);
            $sheet->setCellValue('B' . $row, $k->nama_lengkap);
            $sheet->setCellValue('C' . $row, $k->jabatan->nama_jabatan ?? '-');
            $sheet->setCellValue('D' . $row, $k->satuanKerja->singkatan ?? '-');
            $sheet->setCellValue('E' . $row, $k->tanggal_masuk);
            $sheet->setCellValue('F' . $row, $k->aktif ? 'Aktif' : 'Nonaktif');
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->stream(
            fn() => $writer->save('php://output'),
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="laporan-karyawan.xlsx"',
            ]
        );
    }

    public function exportAbsensiExcel(Request $request)
    {
        $data = $this->getAbsensiData($request);
        $details = $this->getAbsensiDetailData($request);

        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Rekap
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Rekapitulasi');
        $sheet1->setCellValue('A1', 'NIP');
        $sheet1->setCellValue('B1', 'Nama');
        $sheet1->setCellValue('C1', 'Unit');
        $sheet1->setCellValue('D1', 'Hadir');
        $sheet1->setCellValue('E1', 'Terlambat');
        $sheet1->setCellValue('F1', 'Izin');
        $sheet1->setCellValue('G1', 'Sakit');
        $sheet1->setCellValue('H1', 'Cuti');
        $sheet1->setCellValue('I1', 'Dinas Luar');
        $sheet1->setCellValue('J1', 'Alfa');

        $sheet1->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet1->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');
        $sheet1->getStyle('A1:J1')->getFont()->getColor()->setARGB('FFFFFF');

        $row = 2;
        foreach ($data['rekap'] as $r) {
            $sheet1->setCellValue('A' . $row, $r['nip']);
            $sheet1->setCellValue('B' . $row, $r['nama']);
            $sheet1->setCellValue('C' . $row, $r['unit']);
            $sheet1->setCellValue('D' . $row, $r['hadir']);
            $sheet1->setCellValue('E' . $row, $r['terlambat']);
            $sheet1->setCellValue('F' . $row, $r['izin']);
            $sheet1->setCellValue('G' . $row, $r['sakit']);
            $sheet1->setCellValue('H' . $row, $r['cuti']);
            $sheet1->setCellValue('I' . $row, $r['dinas_luar']);
            $sheet1->setCellValue('J' . $row, $r['alfa']);
            $row++;
        }
        foreach (range('A', 'J') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 2: Rincian Kehadiran
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Rincian Detail');
        $sheet2->setCellValue('A1', 'Tanggal');
        $sheet2->setCellValue('B1', 'NIP');
        $sheet2->setCellValue('C1', 'Nama');
        $sheet2->setCellValue('D1', 'Jabatan');
        $sheet2->setCellValue('E1', 'Jam Masuk');
        $sheet2->setCellValue('F1', 'Jam Pulang');
        $sheet2->setCellValue('G1', 'Status');
        $sheet2->setCellValue('H1', 'Keterangan');

        $sheet2->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet2->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');
        $sheet2->getStyle('A1:H1')->getFont()->getColor()->setARGB('FFFFFF');

        $row2 = 2;
        foreach ($details as $d) {
            $sheet2->setCellValue('A' . $row2, $d->tanggal);
            $sheet2->setCellValue('B' . $row2, $d->karyawan->nip ?? '-');
            $sheet2->setCellValue('C' . $row2, $d->karyawan->nama_lengkap ?? '-');
            $sheet2->setCellValue('D' . $row2, $d->karyawan->jabatan->nama_jabatan ?? '-');
            $sheet2->setCellValue('E' . $row2, $d->jam_masuk ?? '-');
            $sheet2->setCellValue('F' . $row2, $d->jam_pulang ?? '-');
            $sheet2->setCellValue('G' . $row2, ucfirst($d->status));
            $sheet2->setCellValue('H' . $row2, $d->keterangan ?? '-');
            $row2++;
        }
        foreach (range('A', 'H') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->stream(
            fn() => $writer->save('php://output'),
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="laporan-absensi.xlsx"',
            ]
        );
    }

    public function exportPenilaianExcel(Request $request)
    {
        $penilaian = $this->getPenilaianData($request);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penilaian');

        $sheet->setCellValue('A1', 'Nama Karyawan');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Penilai');
        $sheet->setCellValue('D1', 'Periode');
        $sheet->setCellValue('E1', 'Nilai Akhir');
        $sheet->setCellValue('F1', 'Grade');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');
        $sheet->getStyle('A1:F1')->getFont()->getColor()->setARGB('FFFFFF');

        $row = 2;
        foreach ($penilaian as $p) {
            $grade = $p->nilai_akhir >= 80 ? 'Sangat Baik' : ($p->nilai_akhir >= 60 ? 'Cukup' : 'Perlu Perbaikan');
            $sheet->setCellValue('A' . $row, $p->karyawan->nama_lengkap ?? '-');
            $sheet->setCellValue('B' . $row, $p->karyawan->nip ?? '-');
            $sheet->setCellValue('C' . $row, $p->penilai->name ?? '-');
            $sheet->setCellValue('D' . $row, $p->periode);
            $sheet->setCellValue('E' . $row, $p->nilai_akhir);
            $sheet->setCellValue('F' . $row, $grade);
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->stream(
            fn() => $writer->save('php://output'),
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="laporan-penilaian.xlsx"',
            ]
        );
    }

    public function exportPenggajianExcel(Request $request)
    {
        $penggajian = $this->getPenggajianData($request);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penggajian');

        $sheet->setCellValue('A1', 'Nama Karyawan');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Periode');
        $sheet->setCellValue('D1', 'Gaji Pokok');
        $sheet->setCellValue('E1', 'Total Gaji');
        $sheet->setCellValue('F1', 'Status');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');
        $sheet->getStyle('A1:F1')->getFont()->getColor()->setARGB('FFFFFF');

        $row = 2;
        foreach ($penggajian as $p) {
            $sheet->setCellValue('A' . $row, $p->karyawan->nama_lengkap ?? '-');
            $sheet->setCellValue('B' . $row, $p->karyawan->nip ?? '-');
            $sheet->setCellValue('C' . $row, $p->periode);
            $sheet->setCellValue('D' . $row, $p->gaji_pokok);
            $sheet->setCellValue('E' . $row, $p->total_diterima);
            $sheet->setCellValue('F' . $row, ucfirst($p->status));
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->stream(
            fn() => $writer->save('php://output'),
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="laporan-penggajian.xlsx"',
            ]
        );
    }

    // --- PDF Exports ---

    public function exportKaryawanPdf(Request $request)
    {
        $karyawan = $this->getKaryawanData($request);
        $pdf = Pdf::loadView('laporan.export.karyawan_pdf', compact('karyawan'));
        return $pdf->download('laporan-karyawan.pdf');
    }

    public function exportKaryawanPptx(Request $request)
    {
        $karyawan = $this->getKaryawanData($request);
        $lines = [];
        foreach ($karyawan as $k) {
            $lines[] = $k->nip . ' | ' . $k->nama_lengkap . ' | ' . ($k->jabatan->nama_jabatan ?? '-') . ' | ' . ($k->satuanKerja->singkatan ?? '-');
        }
        return $this->buildPptxDownload(
            'Laporan Data Karyawan',
            array_merge(['Daftar karyawan aktif'], $lines),
            'laporan-karyawan.pptx'
        );
    }

    public function exportAbsensiPdf(Request $request)
    {
        $data = $this->getAbsensiData($request);
        $details = $this->getAbsensiDetailData($request);
        $pdf = Pdf::loadView('laporan.export.absensi_pdf', array_merge($data, compact('details')))->setPaper('a4', 'landscape');
        return $pdf->download('laporan-absensi.pdf');
    }

    public function exportAbsensiPptx(Request $request)
    {
        $data = $this->getAbsensiData($request);
        $lines = [];
        foreach ($data['rekap'] as $r) {
            $lines[] = $r['nip'] . ' | ' . $r['nama'] . ' | Hadir:' . $r['hadir'] . ' | Terlambat:' . $r['terlambat'] . ' | Izin:' . $r['izin'] . ' | Sakit:' . $r['sakit'] . ' | Cuti:' . $r['cuti'] . ' | Dinas Luar:' . $r['dinas_luar'] . ' | Alfa:' . $r['alfa'];
        }
        return $this->buildPptxDownload(
            'Laporan Absensi',
            array_merge(['Periode ' . $data['startDate'] . ' s/d ' . $data['endDate']], $lines),
            'laporan-absensi.pptx'
        );
    }

    public function exportPenilaianPdf(Request $request)
    {
        $penilaian = $this->getPenilaianData($request);
        $pdf = Pdf::loadView('laporan.export.penilaian_pdf', compact('penilaian'));
        return $pdf->download('laporan-penilaian.pdf');
    }

    public function exportPenilaianPptx(Request $request)
    {
        $penilaian = $this->getPenilaianData($request);
        $lines = [];
        foreach ($penilaian as $p) {
            $lines[] = ($p->karyawan->nama_lengkap ?? '-') . ' | ' . ($p->karyawan->nip ?? '-') . ' | ' . $p->periode . ' | ' . number_format($p->nilai_akhir, 2);
        }
        return $this->buildPptxDownload(
            'Laporan Penilaian Kinerja',
            array_merge(['Daftar penilaian kinerja'], $lines),
            'laporan-penilaian.pptx'
        );
    }

    public function exportPenggajianPdf(Request $request)
    {
        $penggajian = $this->getPenggajianData($request);
        $totalKeseluruhan = $penggajian->sum('total_diterima');
        $pdf = Pdf::loadView('laporan.export.penggajian_pdf', compact('penggajian', 'totalKeseluruhan'));
        return $pdf->download('laporan-penggajian.pdf');
    }

    public function exportPenggajianPptx(Request $request)
    {
        $penggajian = $this->getPenggajianData($request);
        $totalKeseluruhan = $penggajian->sum('total_diterima');
        $lines = [];
        foreach ($penggajian as $p) {
            $lines[] = ($p->karyawan->nama_lengkap ?? '-') . ' | ' . ($p->karyawan->nip ?? '-') . ' | ' . $p->periode . ' | Rp ' . number_format($p->total_diterima, 0, ',', '.');
        }
        return $this->buildPptxDownload(
            'Laporan Penggajian',
            array_merge(['Total keseluruhan: Rp ' . number_format($totalKeseluruhan, 0, ',', '.')], $lines),
            'laporan-penggajian.pptx'
        );
    }

    private function buildPptxDownload($title, array $lines, $filename)
    {
        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n"
            . "<pptx/>";

        $tempFile = tempnam(sys_get_temp_dir(), 'pptx');
        file_put_contents($tempFile, $content);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ])->deleteFileAfterSend(true);
    }

    // --- Word Exports (HTML-to-Word via headers) ---

    public function exportKaryawanWord(Request $request)
    {
        $karyawan = $this->getKaryawanData($request);
        return response()->view('laporan.export.karyawan_word', compact('karyawan'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="laporan-karyawan.doc"');
    }

    public function exportAbsensiWord(Request $request)
    {
        $data = $this->getAbsensiData($request);
        $details = $this->getAbsensiDetailData($request);
        return response()->view('laporan.export.absensi_word', array_merge($data, compact('details')))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="laporan-absensi.doc"');
    }

    public function exportPenilaianWord(Request $request)
    {
        $penilaian = $this->getPenilaianData($request);
        return response()->view('laporan.export.penilaian_word', compact('penilaian'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="laporan-penilaian.doc"');
    }

    public function exportPenggajianWord(Request $request)
    {
        $penggajian = $this->getPenggajianData($request);
        $totalKeseluruhan = $penggajian->sum('total_diterima');
        return response()->view('laporan.export.penggajian_word', compact('penggajian', 'totalKeseluruhan'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="laporan-penggajian.doc"');
    }
}