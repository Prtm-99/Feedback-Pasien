<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use App\Models\UnitLayanan;
use App\Models\Identitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Question;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $units = UnitLayanan::count();
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('filter_tahun');

        $identitasQuery = Identitas::query();
        if ($filterBulan) {
            $identitasQuery->whereMonth('tanggal_survei', $filterBulan);
        }
        if ($filterTahun) {
            $identitasQuery->whereYear('tanggal_survei', $filterTahun);
        }
        $identitas = $identitasQuery->get();

        $feedbacks = Feedback::with(['identitas', 'question', 'unit'])
            ->whereHas('identitas', function ($query) use ($filterTahun, $filterBulan) {
                if ($filterTahun) {
                    $query->whereYear('tanggal_survei', $filterTahun);
                }
                if ($filterBulan) {
                    $query->whereMonth('tanggal_survei', $filterBulan);
                }
            })
            ->get();

        $feedbackCount = $feedbacks->count();

        $ikmAvg = $identitas->whereNotNull('ikm')->avg('ikm');
        $ikmScore = $ikmAvg ? round($ikmAvg, 2) : 0;

        // Chart jumlah feedback
        $grouped = $feedbacks->groupBy(function ($item) {
            return Carbon::parse($item->identitas->tanggal_survei)->format('Y-m');
        });

        $chartYear = $filterTahun ?? date('Y');
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::createFromDate($chartYear, $i, 1);
            $key = $date->format('Y-m');
            $label = $date->translatedFormat('F Y');

            $months[] = [
                'key' => $key,
                'label' => $label,
                'total' => isset($grouped[$key]) ? count($grouped[$key]) : 0
            ];
        }

        $labels = array_column($months, 'label');
        $data = array_column($months, 'total');

        // Chart rata-rata IKM
        $ikmBulanan = Identitas::selectRaw('MONTH(tanggal_survei) as bulan, ROUND(AVG(ikm), 2) as ikm')
            ->when($filterTahun, fn($q) => $q->whereYear('tanggal_survei', $filterTahun))
            ->whereNotNull('ikm')
            ->groupByRaw('MONTH(tanggal_survei)')
            ->orderBy('bulan')
            ->get()
            ->map(fn($item) => [
                'bulan' => Carbon::create()->month($item->bulan)->translatedFormat('F'),
                'ikm' => $item->ikm,
            ]);

        $ikmLabels = $ikmBulanan->pluck('bulan');
        $ikmData = $ikmBulanan->pluck('ikm');

        // Tahun tersedia
        $availableYears = Identitas::selectRaw('YEAR(tanggal_survei) as year')
            ->whereNotNull('tanggal_survei')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // === ✅ Parameter terbaik & terburuk + komentar ===
$feedbackScores = Feedback::select(
        'unit_id',
        'question_id',
        DB::raw('AVG(CASE 
            WHEN answer IN ("Sangat Puas","Sangat sesuai","Sangat cepat","Gratis","Sangat kompeten","Sangat baik","Sangat sopan dan ramah","Dikelola dengan baik","Ya") THEN 4
            WHEN answer IN ("Puas","Sesuai","Cepat","Murah","Kompeten","Baik","Sopan dan ramah","Kurang maksimal") THEN 3
            WHEN answer IN ("Kurang","Kurang sesuai","Kurang cepat","Cukup mahal","Kurang kompeten","Cukup","Kurang sopan dan ramah","Tidak berfungsi") THEN 2
            ELSE 1 END
        ) as avg_score'),
        DB::raw('GROUP_CONCAT(comment SEPARATOR " | ") as comments')
    )
    ->whereHas('identitas', function($q) use ($filterBulan, $filterTahun) {
        if ($filterBulan) $q->whereMonth('tanggal_survei', $filterBulan);
        if ($filterTahun) $q->whereYear('tanggal_survei', $filterTahun);
    })
    ->groupBy('unit_id', 'question_id')
    ->with(['question', 'unit'])
    ->get()
    ->groupBy('unit_id');

$bestWorst = [];
foreach ($feedbackScores as $unitId => $scores) {
    // Ambil semua pertanyaan, urutkan
    $sortedScores = $scores->sortByDesc('avg_score');

    // Top 3 terbaik
    $bestList = $sortedScores->map(function ($item) {
        return [
            'question' => $item->question->text ?? 'N/A',
            'score' => round($item->avg_score, 2),
            'comments' => $item->comments ?? '-'
        ];
    });

    // Top 3 terburuk (nilai rata-rata terendah)
    $worstList = $scores->sortBy('avg_score')->map(function ($item) {
        return [
            'question' => $item->question->text ?? 'N/A',
            'score' => round($item->avg_score, 2),
            'comments' => $item->comments ?? '-'
        ];
    });

    $bestWorst[$unitId] = [
        'unit' => $scores->first()->unit->nama_unit ?? 'N/A',
        'best' => $bestList,
        'worst' => $worstList,
    ];
}

    // Setelah loop bestWorst selesai
$bestWorstCollection = collect($bestWorst);

// Tambahkan pagination manual
$page = $request->input('page', 1);
$perPage = 2;
$bestWorstPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
    $bestWorstCollection->forPage($page, $perPage),
    $bestWorstCollection->count(),
    $perPage,
    $page,
    ['path' => $request->url(), 'query' => $request->query()]
);


        return view('admin.dashboard', compact(
            'units', 'feedbackCount', 'months', 'labels', 'data',
            'ikmScore', 'identitas', 'availableYears', 'filterBulan', 'filterTahun',
            'ikmLabels', 'ikmData', 'bestWorst', 'bestWorstPaginated'
        ));
    }
}
