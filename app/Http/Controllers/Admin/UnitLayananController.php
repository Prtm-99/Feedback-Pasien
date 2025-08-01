<?php

namespace App\Http\Controllers\Admin;  

use App\Http\Controllers\Controller;
use App\Models\UnitLayanan;
use Illuminate\Http\Request;

class UnitLayananController extends Controller
{
    public function index()
    {
        $units = UnitLayanan::all();

        return view('admin.unit.index', compact('units'));
    }

    public function create()
    {
        return view('admin.unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required|string|max:255',
        ]);

        UnitLayanan::create($request->all());

        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function show($id)
    {
        $unit = UnitLayanan::findOrFail($id);
        return view('admin.unit.show', compact('unit'));
    }

    public function edit(UnitLayanan $unit)
    {
        return view('admin.unit.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = UnitLayanan::findOrFail($id);
        $unit->update($request->only(['nama_unit', 'deskripsi']));
        
        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil diupdate.');
    }

    public function destroy(UnitLayanan $unit)
    {
        $unit->delete();
        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil dihapus.');
    }
}
