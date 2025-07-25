<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Feedback;
use App\Models\Identitas;
use App\Models\UnitLayanan;
use Illuminate\Support\Str;
use App\Exports\FeedbackExport;
use Maatwebsite\Excel\Facades\Excel;

class FeedbackController extends Controller
{
    public function exportExcel(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        return Excel::download(new FeedbackExport($bulan, $tahun), 'data_feedback.xlsx');
    }

    public function index(Request $request)
    {
        $units = UnitLayanan::all();
        $topics = Topic::with('questions')->get();
        $questions = Question::all();

        // Ambil data tahun unik dari tabel identitas
        $availableYears = Identitas::selectRaw('YEAR(tanggal_survei) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Filter identitas berdasarkan bulan & tahun
        $identitasQuery = Identitas::query();

        if ($request->filled('bulan')) {
            $identitasQuery->whereMonth('tanggal_survei', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $identitasQuery->whereYear('tanggal_survei', $request->tahun);
        }

        $identitasList = $identitasQuery->paginate(5);

        $feedbacks = Feedback::with('question', 'identitas', 'unit', 'topic')
            ->whereIn('identitas_id', $identitasList->pluck('id'))
            ->get();

        $feedbacksByIdentitas = $feedbacks->groupBy('identitas_id');

        // Map skor
        $scoreMap = [
            'Sangat Puas' => 4, 'Puas' => 3, 'Kurang' => 2, 'Sangat Kurang' => 1,
            'Sangat sesuai' => 4, 'Sesuai' => 3, 'Kurang sesuai' => 2, 'Tidak sesuai' => 1,
            'Sangat mudah' => 4, 'Mudah' => 3, 'Kurang mudah' => 2, 'Tidak mudah' => 1,
            'Gratis' => 4, 'Murah' => 3, 'Cukup mahal' => 2, 'Sangat mahal' => 1,
            'Sangat kompeten' => 4, 'Kompeten' => 3, 'Kurang kompeten' => 2, 'Tidak kompeten' => 1,
            'Sangat baik' => 4, 'Baik' => 3, 'Cukup' => 2, 'Buruk' => 1,
            'Sangat sopan dan ramah' => 4, 'Sopan dan ramah' => 3, 'Kurang sopan dan ramah' => 2, 'Tidak sopan dan ramah' => 1,
            'Dikelola dengan baik' => 4, 'Berfungsi kurang maksimal' => 3, 'Ada tetapi tidak berfungsi' => 2, 'Tidak ada' => 1,
            'Ya' => 4, 'Tidak, karena ...' => 1,
        ];

        $summary = [];

        foreach ($identitasList as $identitas) {
            $userFeedbacks = $feedbacks->where('identitas_id', $identitas->id);
            $unsurScores = [];

            foreach ($userFeedbacks as $fb) {
                if (!isset($scoreMap[$fb->answer])) continue;

                $unsur = $fb->question->unsur ?? 'U' . $fb->question_id;

                if (!isset($unsurScores[$unsur])) {
                    $unsurScores[$unsur] = ['total' => 0, 'count' => 0];
                }

                $unsurScores[$unsur]['total'] += $scoreMap[$fb->answer];
                $unsurScores[$unsur]['count']++;
            }

            // Perbaikan: Bobot dibagi berdasarkan jumlah unsur
            $jumlahUnsur = count($unsurScores);
            $ikm = 0;

            foreach ($unsurScores as $unsur => $data) {
                $nrr = $data['count'] > 0 ? $data['total'] / $data['count'] : 0;
                $tertimbang = $jumlahUnsur > 0 ? $nrr / $jumlahUnsur : 0;
                $ikm += $tertimbang;
            }

            $ikm = round($ikm * 25, 2);

            $feedbackCount = $userFeedbacks->count();
            $totalScore = $userFeedbacks->sum(function ($fb) use ($scoreMap) {
                return $scoreMap[$fb->answer] ?? 0;
            });

            // Kategori mutu
            if ($ikm >= 88.31) {
                $kategori = 'A (Sangat Baik)';
            } elseif ($ikm >= 76.61) {
                $kategori = 'B (Baik)';
            } elseif ($ikm >= 65.00) {
                $kategori = 'C (Kurang Baik)';
            } else {
                $kategori = 'D (Tidak Baik)';
            }

            $summary[] = [
                'identitas' => $identitas,
                'feedback_count' => $feedbackCount,
                'score' => $totalScore,
                'ikm' => $ikm,
                'kategori' => $kategori,
            ];
        }

        return view('feedback.index', compact(
            'units', 'identitasList',
            'topics', 'questions', 'feedbacks',
            'summary', 'availableYears', 'feedbacksByIdentitas'
        ));
    }

    public function start(Request $request, Identitas $identitas)
    {
        $topicIds = explode(',', $request->query('topic_ids', ''));

        if (empty($topicIds) || $topicIds === ['']) {
            $topics = Topic::with('questions')->get();
        } else {
            $topics = Topic::with('questions')->whereIn('id', $topicIds)->get();
        }

        // Ambil pertanyaan statis yang tipe-nya 'statis'
        $staticQuestions = Question::where('type', 'statis')->get();

        return view('feedback.form', compact('topics', 'identitas', 'staticQuestions'));
    }

public function form(Request $request)
{
    $identitas = Identitas::findOrFail($request->identitas_id);
    $unit = UnitLayanan::findOrFail($request->unit_id);

    $topicIds = explode(',', $request->topic_ids ?? '');

    if (empty($topicIds) || $topicIds === ['']) {
        $topics = Topic::with('questions')->get();
    } else {
        $topics = Topic::with('questions')->whereIn('id', $topicIds)->get();
    }

    $staticQuestions = Question::where('type', 'statis')->get();

    return view('feedback.form', compact('identitas', 'unit', 'topics', 'staticQuestions'));
}


    public function store(Request $request, Identitas $identitas, Topic $topic)
    {
        foreach ($request->input('answers') as $question_id => $answer) {
            if (Str::startsWith($question_id, 's')) {
                // Pertanyaan statis
                Feedback::create([
                    'question_id'        => null,
                    'pertanyaan_statis'  => $question_id,
                    'identitas_id'       => $identitas->id,
                    'unit_id'            => $identitas->unit_layanan_id,
                    'topic_id'           => $topic->id,
                    'answer'             => $answer['value'] ?? null,
                    'comment'            => $answer['comment'] ?? null,
                    'tahun'              => now()->year,
                ]);
            } else {
                // Pertanyaan dinamis dari database
                Feedback::create([
                    'question_id'        => $question_id,
                    'pertanyaan_statis'  => null,
                    'identitas_id'       => $identitas->id,
                    'unit_id'            => $identitas->unit_layanan_id,
                    'topic_id'           => $topic->id,
                    'answer'             => $answer['value'] ?? null,
                    'comment'            => $answer['comment'] ?? null,
                    'tahun'              => now()->year,
                ]);
            }
        }

        $nextTopic = Topic::where('id', '>', $topic->id)->orderBy('id')->first();

        if ($nextTopic) {
            return redirect()->route('feedback.start', $identitas->id)
                ->with('next_topic_id', $nextTopic->id);
        }

        return redirect()->route('feedback.result', $identitas->id);
    }

    public function result(Identitas $identitas)
    {
        // Ambil semua feedback terkait identitas beserta relasi question
        $feedbacks = Feedback::where('identitas_id', $identitas->id)->with('question')->get();

        // Mapping skor jawaban ke angka
        $scoreMap = [
            'Sangat Puas' => 4,
            'Puas' => 3,
            'Kurang' => 2,
            'Sangat Kurang' => 1,

            'Sangat sesuai' => 4,
            'Sesuai' => 3,
            'Kurang sesuai' => 2,
            'Tidak sesuai' => 1,

            'Sangat mudah' => 4,
            'Mudah' => 3,
            'Kurang mudah' => 2,
            'Tidak mudah' => 1,

            'Gratis' => 4,
            'Murah' => 3,
            'Cukup mahal' => 2,
            'Sangat mahal' => 1,

            'Sangat kompeten' => 4,
            'Kompeten' => 3,
            'Kurang kompeten' => 2,
            'Tidak kompeten' => 1,

            'Sangat baik' => 4,
            'Baik' => 3,
            'Cukup' => 2,
            'Buruk' => 1,

            'Sangat sopan dan ramah' => 4,
            'Sopan dan ramah' => 3,
            'Kurang sopan dan ramah' => 2,
            'Tidak sopan dan ramah' => 1,

            'Dikelola dengan baik' => 4,
            'Berfungsi kurang maksimal' => 3,
            'Ada tetapi tidak berfungsi' => 2,
            'Tidak ada' => 1,

            'Ya' => 4,
            'Tidak, karena ...' => 1,
        ];

        // Hitung total skor dan jumlah pertanyaan per unsur
        $unsurScores = [];
        foreach ($feedbacks as $fb) {
            // Lewati jawaban yang tidak ada di mapping skor
            if (!isset($scoreMap[$fb->answer])) continue;

            // Ambil kode unsur dari pertanyaan, fallback ke 'U' + id pertanyaan
            $unsur = $fb->question->unsur ?? 'U' . $fb->question_id;

            if (!isset($unsurScores[$unsur])) {
                $unsurScores[$unsur] = ['total' => 0, 'count' => 0];
            }

            $unsurScores[$unsur]['total'] += $scoreMap[$fb->answer];
            $unsurScores[$unsur]['count']++;
        }

        $jumlahUnsur = count($unsurScores);
        $ikm = 0;

        // Hitung nilai rata-rata tertimbang
        foreach ($unsurScores as $unsur => $data) {
            $nrr = $data['count'] > 0 ? $data['total'] / $data['count'] : 0;
            $tertimbang = $jumlahUnsur > 0 ? $nrr / $jumlahUnsur : 0;
            $ikm += $tertimbang;
        }

        $ikm = round($ikm * 25, 2); // Skala 100

        // Tentukan kategori mutu pelayanan
        if ($ikm >= 88.31) {
            $mutu = 'A (Sangat Baik)';
        } elseif ($ikm >= 76.61) {
            $mutu = 'B (Baik)';
        } elseif ($ikm >= 65.00) {
            $mutu = 'C (Kurang Baik)';
        } else {
            $mutu = 'D (Tidak Baik)';
        }

        // Simpan nilai IKM dan kategori ke tabel identitas
        $identitas->ikm = $ikm;
        $identitas->kategori_mutu = $mutu;
        $identitas->save();

        return view('feedback.result', compact(
            'feedbacks',
            'ikm',
            'mutu',
            'jumlahUnsur'
        ));
    }
}
