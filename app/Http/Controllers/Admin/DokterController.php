<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\UnitLayanan;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::with('unit')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $units = UnitLayanan::all();
        return view('admin.dokter.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'unit_layanan_id' => 'required|exists:unit_layanan,id',
        ]);

        Dokter::create($request->all());

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

        public function show($unitId)
    {
        $unit = UnitLayanan::with('dokters')->findOrFail($unitId);
        return view('admin.dokter.show', compact('unit'));
    }

    public function edit($id)
    {
        $dokter = Dokter::findOrFail($id);
        $units = UnitLayanan::all();
        return view('admin.dokter.edit', compact('dokter', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'unit_layanan_id' => 'required|exists:unit_layanan,id',
        ]);

        $dokter = Dokter::findOrFail($id);
        $dokter->update($request->all());

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter diperbarui.');
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter dihapus.');
    }
}
