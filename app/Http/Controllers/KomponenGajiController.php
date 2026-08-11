<?php
namespace App\Http\Controllers;

use App\Models\KomponenGaji;
use Illuminate\Http\Request;

class KomponenGajiController extends Controller
{
    public function index(Request $request)
    {
        $query = KomponenGaji::query();
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('kode', 'like', "%{$q}%");
            });
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        $komponen = $query->latest()->paginate(10)->withQueryString();
        return view('komponen_gaji.index', compact('komponen'));
    }

    public function create()
    {
        return view('komponen_gaji.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:komponen_gaji,kode|max:30',
            'nama' => 'required|max:100',
            'tipe' => 'required|in:penghasilan,potongan',
            'sifat' => 'required|in:tetap,variable',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable',
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);
        KomponenGaji::create($validated);
        return redirect()->route('komponen-gaji.index')->with('success', 'Komponen gaji berhasil ditambahkan.');
    }

    public function edit(KomponenGaji $komponenGaji)
    {
        return view('komponen_gaji.edit', compact('komponenGaji'));
    }

    public function update(Request $request, KomponenGaji $komponenGaji)
    {
        $validated = $request->validate([
            'kode' => 'required|max:30|unique:komponen_gaji,kode,' . $komponenGaji->id,
            'nama' => 'required|max:100',
            'tipe' => 'required|in:penghasilan,potongan',
            'sifat' => 'required|in:tetap,variable',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable',
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);
        $komponenGaji->update($validated);
        return redirect()->route('komponen-gaji.index')->with('success', 'Komponen gaji berhasil diperbarui.');
    }

    public function destroy(KomponenGaji $komponenGaji)
    {
        $komponenGaji->delete();
        return redirect()->route('komponen-gaji.index')->with('success', 'Komponen gaji berhasil dihapus.');
    }
}
