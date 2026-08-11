<?php
namespace App\Http\Controllers;

use App\Models\TugasKaryawan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = TugasKaryawan::with(['karyawan', 'pemberi']);
        if ($user->isKaryawan()) {
            $query->where('karyawan_id', $user->karyawan_id);
        } elseif ($user->isAtasan()) {
            $query->where('pemberi_tugas', $user->id);
        }

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $tugas = $query->latest()->paginate(10)->withQueryString();
        $karyawanList = Karyawan::where('aktif', true)->get();

        return view('tugas.index', compact('tugas', 'karyawanList'));
    }

    public function create()
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        return view('tugas.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'judul' => 'required|max:200',
            'deskripsi' => 'nullable',
            'tenggat' => 'nullable|date',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
        ]);

        $validated['pemberi_tugas'] = Auth::id();
        TugasKaryawan::create($validated);

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil diberikan.');
    }

    public function updateStatus(Request $request, TugasKaryawan $tugas)
    {
        $request->validate([
            'status' => 'required|in:diberikan,dikerjakan,selesai,ditolak',
            'catatan_penyelesaian' => 'nullable',
        ]);

        $tugas->update($request->only(['status', 'catatan_penyelesaian']));
        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function destroy(TugasKaryawan $tugas)
    {
        $tugas->delete();
        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
