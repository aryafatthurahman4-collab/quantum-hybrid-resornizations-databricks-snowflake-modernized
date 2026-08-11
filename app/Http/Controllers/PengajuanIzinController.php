<?php
namespace App\Http\Controllers;

use App\Models\PengajuanIzin;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanIzinController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PengajuanIzin::with('karyawan');
        if ($user->isKaryawan()) {
            $query->where('karyawan_id', $user->karyawan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('karyawan_id') && !$user->isKaryawan()) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $pengajuan = $query->latest()->paginate(10)->withQueryString();
        $karyawanList = Karyawan::where('aktif', true)->get();

        return view('pengajuan_izin.index', compact('pengajuan', 'karyawanList'));
    }

    public function create()
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        return view('pengajuan_izin.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => auth()->user()->isKaryawan() ? 'prohibited' : 'required|exists:karyawan,id',
            'jenis' => 'required|in:izin,sakit,cuti,dinas_luar',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required',
        ]);

        if (auth()->user()->isKaryawan()) {
            $validated['karyawan_id'] = auth()->user()->karyawan_id;
        }

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-izin', 'public');
        }

        PengajuanIzin::create($validated);
        $msg = auth()->user()->isKaryawan() ? 'Pengajuan izin berhasil dikirim.' : 'Pengajuan izin berhasil dicatat.';
        return redirect()->route('pengajuan-izin.index')->with('success', $msg);
    }

    public function approve(PengajuanIzin $pengajuanIzin, Request $request)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak', 'catatan_approval' => 'nullable']);

        $pengajuanIzin->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        $msg = $request->status === 'disetujui' ? 'Pengajuan izin disetujui.' : 'Pengajuan izin ditolak.';
        return back()->with('success', $msg);
    }

    public function destroy(PengajuanIzin $pengajuanIzin)
    {
        if ($pengajuanIzin->status !== 'menunggu') {
            return back()->with('error', 'Hanya pengajuan dengan status menunggu yang dapat dihapus.');
        }
        $pengajuanIzin->delete();
        return redirect()->route('pengajuan-izin.index')->with('success', 'Pengajuan izin berhasil dihapus.');
    }
}
