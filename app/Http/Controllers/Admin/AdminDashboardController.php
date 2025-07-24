<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\UnitLayanan;
use App\Models\Identitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

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

        $feedbacks = Feedback::with('identitas')
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

        // Menghilangkan bagian perDokter

        $availableYears = Identitas::selectRaw('YEAR(tanggal_survei) as year')
            ->whereNotNull('tanggal_survei')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('admin.dashboard', compact(
            'units', 'feedbackCount', 'months', 'labels', 'data',
            'ikmScore', 'identitas', 'availableYears', 'filterBulan', 'filterTahun',
            'ikmLabels', 'ikmData'
        ));
    }
}
