<?php
namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\DetailPenggajian;
use App\Models\Karyawan;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenggajianController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Penggajian::with(['karyawan', 'pembuat']);
        if ($user->isKaryawan()) {
            $query->where('karyawan_id', $user->karyawan_id);
        }

        if ($request->filled('periode')) {
            $query->where('periode', 'like', '%' . $request->periode . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $penggajian = $query->latest()->paginate(10)->withQueryString();
        $karyawanList = Karyawan::where('aktif', true)->get();
        return view('penggajian.index', compact('penggajian', 'karyawanList'));
    }

    public function create()
    {
        $karyawan = Karyawan::with('jabatan')->where('aktif', true)->get();
        $periode = now()->format('Y-m');
        return view('penggajian.create', compact('karyawan', 'periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|max:20',
        ]);

        $exists = Penggajian::where('karyawan_id', $request->karyawan_id)
            ->where('periode', $request->periode)->exists();

        if ($exists) {
            return back()->with('error', 'Gaji untuk karyawan dan periode tersebut sudah ada.');
        }

        $this->payrollService->hitungGaji($request->karyawan_id, $request->periode, Auth::id());
        return redirect()->route('penggajian.index')->with('success', 'Penggajian berhasil dihitung.');
    }

    public function hitungSemua(Request $request)
    {
        $periode = $request->periode ?? now()->format('Y-m');
        $this->payrollService->hitungSemuaGaji($periode, Auth::id());
        return redirect()->route('penggajian.index')->with('success', "Semua gaji periode $periode berhasil dihitung.");
    }

    public function show(Penggajian $penggajian)
    {
        $penggajian->load(['karyawan.jabatan', 'karyawan.satuanKerja', 'detail.komponenGaji', 'pembuat']);
        return view('penggajian.show', compact('penggajian'));
    }

    public function slipGaji(Penggajian $penggajian)
    {
        $penggajian->load(['karyawan.jabatan', 'karyawan.satuanKerja', 'detail', 'pembuat']);
        return view('penggajian.slip', compact('penggajian'));
    }

    public function konfirmasi(Penggajian $penggajian)
    {
        $penggajian->update(['status' => 'dikonfirmasi']);
        return back()->with('success', 'Penggajian dikonfirmasi.');
    }

    public function bayar(Penggajian $penggajian)
    {
        $penggajian->update(['status' => 'dibayar']);
        return back()->with('success', 'Penggajian ditandai sebagai dibayar.');
    }

    public function destroy(Penggajian $penggajian)
    {
        $penggajian->detail()->delete();
        $penggajian->delete();
        return redirect()->route('penggajian.index')->with('success', 'Data penggajian berhasil dihapus.');
    }
}
