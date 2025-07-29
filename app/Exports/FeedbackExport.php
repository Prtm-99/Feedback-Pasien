<?php

namespace App\Exports;

use App\Models\Feedback;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class FeedbackExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan = null, $tahun = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Feedback::with(['question', 'identitas', 'unit', 'topic'])
            ->whereHas('identitas', function ($query) {
                $query->when($this->bulan, fn($q) => $q->whereMonth('tanggal_survei', $this->bulan))
                      ->when($this->tahun, fn($q) => $q->whereYear('tanggal_survei', $this->tahun));
            })
            ->get()
            ->map(function ($item) {
                $tanggal = optional($item->identitas)->tanggal_survei;
                $jam = optional($item->identitas)->jam_survei;

$tanggalFormatted = '-';
if ($tanggal) {
    // Ambil hanya tanggal (tanpa jam) dari field tanggal_survei
    $onlyDate = Carbon::parse($tanggal)->format('Y-m-d');

    if ($jam) {
        // Gabungkan tanggal saja + jam survei
        $datetimeString = $onlyDate . ' ' . $jam;
        $tanggalFormatted = Carbon::parse($datetimeString)->translatedFormat('d F Y H:i');
    } else {
        $tanggalFormatted = Carbon::parse($onlyDate)->translatedFormat('d F Y');
    }
}


                return [
                    'Tanggal & Jam Survei' => $tanggalFormatted,
                    'NO HandPhone'         => optional($item->identitas)->no_hp,
                    'Unit'                 => optional($item->unit)->nama_unit,
                    'Pertanyaan'           => optional($item->question)->text,
                    'Jawaban'              => $item->answer,
                    'Komentar'             => $item->comment,
                    'Topik'                => optional($item->topic)->name,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal & Jam Survei',
            'NO HandPhone',
            'Unit',
            'Pertanyaan',
            'Jawaban',
            'Komentar',
            'Topik',
        ];
    }
}
