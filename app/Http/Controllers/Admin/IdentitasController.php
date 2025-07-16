<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dokter;
use App\Models\UnitLayanan;
use App\Models\Identitas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IdentitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Identitas::with(['dokter', 'unit'])->latest('tanggal_survei');

    // Filter berdasarkan bulan
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_survei', $request->bulan);
    }

    // Filter berdasarkan tahun
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_survei', $request->tahun);
    }

    // Ambil data identitas sesuai filter
    $identitasList = $query->get();

    // Ambil semua tahun yang tersedia untuk dropdown filter
    $availableYears = Identitas::selectRaw('YEAR(tanggal_survei) as year')
                        ->distinct()
                        ->orderByDesc('year')
                        ->pluck('year');

    return view('admin.identitas.index', compact('identitasList', 'availableYears'));
}


public function create($dokterId)
{
    $dokter = Dokter::findOrFail($dokterId);
    return view('admin.identitas.create', compact('dokter'));
}
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'alamat' => 'nullable',
            'jenis_kelamin' => 'required',
            'usia' => 'required|numeric',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'tanggal_survei' => 'required|date',
            'jam_survei' => 'required',
            'dokter_id' => 'required|exists:dokters,id',
            'unit_layanan_id' => 'required|exists:unit_layanan,id',
        ]);

        $identitas = Identitas::create($request->all());

        
        return redirect()->route('feedback.form', [
            'identitas_id' => $identitas->id,
            'dokter_id' => $request->dokter_id,
            'unit_id' => $request->unit_layanan_id,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $identitas = Identitas::findOrFail($id);
    $identitas->delete();

    return redirect()->route('admin.identitas.index')
        ->with('success', 'Data identitas berhasil dihapus.');
}

}
