<?php

namespace App\Http\Controllers\Admin;

use App\Models\Topic;
use App\Models\UnitLayanan;
use App\Models\Identitas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IdentitasController extends Controller
{
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
        $identitasList = $query->paginate(5);

        // Ambil semua tahun yang tersedia untuk dropdown filter
        $availableYears = Identitas::selectRaw('YEAR(tanggal_survei) as year')
                            ->distinct()
                            ->orderByDesc('year')
                            ->pluck('year');

        return view('admin.identitas.index', compact('identitasList', 'availableYears'));
    }

  public function create(UnitLayanan $unit)
    {
        $specialTopics = Topic::whereIn('name', ['Farmasi', 'Laboratorium', 'Radiologi', 'IGD', 'OPERASI', 'GIZI'])
                            ->where('is_default', false)
                            ->get();
        
        $defaultTopic = Topic::where('is_default', true)->first();

        return view('identitas.create', [
            'unit' => $unit,
            'specialTopics' => $specialTopics,
            'defaultTopic' => $defaultTopic
        ]);
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'no_hp' => 'required|string|max:15',
        'jenis_kelamin' => 'required|in:L,P',
        'usia' => 'required|integer|min:1|max:120',
        'pendidikan' => 'required|string|max:50',
        'pekerjaan' => 'required|string|max:50',
        'unit_id' => 'required|exists:unit_layanan,id',
        'topics' => 'nullable|array',
        'topics.*' => 'exists:topics,id'
    ]);

    DB::beginTransaction();
    try {
        $identitas = Identitas::create([
            'no_hp' => $validated['no_hp'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'usia' => $validated['usia'],
            'pendidikan' => $validated['pendidikan'],
            'pekerjaan' => $validated['pekerjaan'],
            'unit_layanan_id' => $validated['unit_id'],
            'tanggal_survei' => now()->toDateString(),
            'jam_survei' => now()->toTimeString(),
        ]);

        // Cari topik default (Survei IKM)
        $defaultTopic = Topic::where('is_default', true)->first();

        if (!$defaultTopic) {
            throw new \Exception('Topik wajib Survei IKM tidak ditemukan di database');
        }

        // Gabungkan topik default + topik pilihan user
        $topicsToAttach = isset($validated['topics']) 
            ? array_unique(array_merge([$defaultTopic->id], $validated['topics']))
            : [$defaultTopic->id];

        $identitas->topics()->attach($topicsToAttach);
        DB::commit();

        // Simpan urutan topik ke session
        $request->session()->put('selected_topics', $topicsToAttach);
        $request->session()->put('current_topic_index', 0);

        return redirect()->route('feedback.start', ['identitas' => $identitas->id]);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()
                   ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $identitas = Identitas::findOrFail($id);
        $identitas->delete();

        return redirect()->route('admin.identitas.index')
            ->with('success', 'Data identitas berhasil dihapus.');
    }
}