<?php
namespace App\Http\Controllers;

use App\Models\SatuanKerja;
use Illuminate\Http\Request;

class SatuanKerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = SatuanKerja::query();
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('nama_unit', 'like', "%{$q}%")
                    ->orWhere('singkatan', 'like', "%{$q}%");
            });
        }
        $satuanKerja = $query->latest()->paginate(10)->withQueryString();
        return view('satuan_kerja.index', compact('satuanKerja'));
    }

    public function create()
    {
        return view('satuan_kerja.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_unit' => 'required|max:100',
            'singkatan' => 'nullable|max:20',
            'keterangan' => 'nullable',
        ]);

        SatuanKerja::create($validated);
        return redirect()->route('satuan-kerja.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function edit(SatuanKerja $satuanKerja)
    {
        return view('satuan_kerja.edit', compact('satuanKerja'));
    }

    public function update(Request $request, SatuanKerja $satuanKerja)
    {
        $validated = $request->validate([
            'nama_unit' => 'required|max:100',
            'singkatan' => 'nullable|max:20',
            'keterangan' => 'nullable',
        ]);

        $satuanKerja->update($validated);
        return redirect()->route('satuan-kerja.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(SatuanKerja $satuanKerja)
    {
        if ($satuanKerja->karyawan()->count() > 0) {
            return back()->with('error', 'Unit kerja tidak dapat dihapus karena masih memiliki karyawan.');
        }
        $satuanKerja->delete();
        return redirect()->route('satuan-kerja.index')->with('success', 'Unit kerja berhasil dihapus.');
    }
}
