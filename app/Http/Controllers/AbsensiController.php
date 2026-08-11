<?php
namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Absensi::with('karyawan');
        if ($user->isKaryawan()) {
            $query->where('karyawan_id', $user->karyawan_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('karyawan_id') && !$user->isKaryawan()) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $absensi = $query->latest()->paginate(30)->withQueryString();
        $karyawanList = Karyawan::where('aktif', true)->get();

        return view('absensi.index', compact('absensi', 'karyawanList'));
    }

    public function create()
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        return view('absensi.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'jam_pulang' => 'nullable',
            'status' => 'required|in:hadir,terlambat,izin,sakit,cuti,dinas_luar,alfa',
            'keterangan' => 'nullable',
        ]);

        Absensi::updateOrCreate(
            ['karyawan_id' => $validated['karyawan_id'], 'tanggal' => $validated['tanggal']],
            $validated
        );

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dicatat.');
    }

    public function edit(Absensi $absensi)
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        return view('absensi.edit', compact('absensi', 'karyawan'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'jam_masuk' => 'nullable',
            'jam_pulang' => 'nullable',
            'status' => 'required|in:hadir,terlambat,izin,sakit,cuti,dinas_luar,alfa',
            'keterangan' => 'nullable',
        ]);

        $validated['verified_by'] = Auth::id();
        $absensi->update($validated);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }

    public function absenHarian(Request $request)
    {
        $karyawan = Auth::user()->karyawan;
        if (!$karyawan) return back()->with('error', 'Akun Anda tidak terhubung ke data karyawan.');

        $request->validate([
            'status' => 'required|in:hadir,terlambat,izin,sakit',
            'keterangan' => 'nullable',
        ]);

        Absensi::updateOrCreate(
            ['karyawan_id' => $karyawan->id, 'tanggal' => today()],
            [
                'jam_masuk' => now()->format('H:i:s'),
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]
        );

        return back()->with('success', 'Absensi hari ini berhasil dicatat.');
    }

    public function rekap(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('m');
        $tahun = $request->tahun ?? now()->format('Y');

        $karyawan = Karyawan::where('aktif', true)->get();
        $rekap = [];
        foreach ($karyawan as $k) {
            $absensi = Absensi::where('karyawan_id', $k->id)
                ->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)
                ->get();
            $rekap[] = [
                'karyawan' => $k,
                'hadir' => $absensi->whereIn('status', ['hadir', 'terlambat'])->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'cuti' => $absensi->where('status', 'cuti')->count(),
                'alfa' => $absensi->where('status', 'alfa')->count(),
                'total' => $absensi->count(),
            ];
        }

        return view('absensi.rekap', compact('rekap', 'bulan', 'tahun'));
    }
}
