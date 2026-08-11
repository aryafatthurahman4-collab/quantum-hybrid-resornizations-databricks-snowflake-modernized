<?php
namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::query();
        if ($request->filled('q')) {
            $query->where('nama_jabatan', 'like', '%' . $request->q . '%');
        }
        $jabatan = $query->latest()->paginate(10)->withQueryString();
        return view('jabatan.index', compact('jabatan'));
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|max:100',
            'level' => 'nullable|max:50',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        Jabatan::create($validated);
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|max:100',
            'level' => 'nullable|max:50',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        $jabatan->update($validated);
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->karyawan()->count() > 0) {
            return back()->with('error', 'Jabatan tidak dapat dihapus karena masih memiliki karyawan.');
        }
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
