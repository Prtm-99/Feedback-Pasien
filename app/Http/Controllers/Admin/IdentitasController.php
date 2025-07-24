<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
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
    $query = Identitas::with(['unit'])->latest('tanggal_survei');

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


public function create()
{
    $units = UnitLayanan::all();
    return view('identitas.create', compact('units'));
}
    public function store(Request $request)
    {
        $request->validate([
            'no_hp' => 'required',
            'jenis_kelamin' => 'required',
            'usia' => 'required|numeric',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'unit_layanan_id' => 'required|exists:unit_layanan,id',
        ]);

                // Isi otomatis tanggal dan jam saat ini
        $requestData = $request->all();

        $requestData['tanggal_survei'] = now()->toDateString(); // Format YYYY-MM-DD
        $requestData['jam_survei'] = now()->toTimeString();     // Format HH:MM:SS

        $identitas = Identitas::create($requestData);

        
        return redirect()->route('feedback.form', [
            'identitas_id' => $identitas->id,
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
