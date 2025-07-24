<?php

namespace App\Exports;

use App\Models\Feedback;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

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
        return Feedback::with(['question', 'identitas', 'unit', 'dokter', 'topic'])
->whereHas('identitas', function ($query) {
    $query->when($this->bulan, fn($q) => $q->whereMonth('tanggal_survei', $this->bulan))
          ->when($this->tahun, fn($q) => $q->whereYear('tanggal_survei', $this->tahun));
})

            ->get()
            ->map(function ($item) {
                return [
                    'Tanggal Survei' => optional($item->identitas)->tanggal_survei,
                    'Nama Pasien' => optional($item->identitas)->nama,
                    'Unit' => optional($item->unit)->nama_unit,
                    'Dokter' => optional($item->dokter)->nama,
                    'Pertanyaan' => optional($item->question)->text,
                    'Jawaban' => $item->answer,
                    'Komentar' => $item->comment,
                    'Topik' => optional($item->topic)->name,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal Survei',
            'Nama Pasien',
            'Unit',
            'Dokter',
            'Pertanyaan',
            'Jawaban',
            'Komentar',
            'Topik',
        ];
    }
}
