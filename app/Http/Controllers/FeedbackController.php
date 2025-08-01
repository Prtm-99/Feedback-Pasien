<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Feedback;
use App\Models\Identitas;
use App\Models\UnitLayanan;
use App\Exports\FeedbackExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

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

            $identitasList = $identitasQuery
        ->latest('tanggal_survei')
        ->paginate(5)
        ->appends($request->all()); // agar filter tetap saat pindah halaman

        $feedbacks = Feedback::with('question', 'identitas', 'unit', 'topic')
            ->whereIn('identitas_id', $identitasList->pluck('id'))
            ->get();

        $feedbacksByIdentitas = $feedbacks->groupBy('identitas_id');

        // Map skor
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
    // Ambil topik wajib Survei IKM
    $defaultTopic = Topic::where('name', 'Survei IKM')
        ->with(['questions' => function ($q) {
            $q->orderBy('created_at');
        }])
        ->first();

    if (!$defaultTopic) {
        abort(500, 'Topik wajib Survei IKM tidak ditemukan');
    }

    // Ambil topik yang dipilih user saat isi identitas
    $selectedTopics = $identitas->topics->pluck('id')->toArray();

    // Ambil semua topik yang bukan topik default dan bukan topik lama (Farmasi, Radiologi, Laboratorium)
    $extraTopics = Topic::whereNotIn('name', [
            'Survei IKM',
            'Farmasi',
            'Radiologi',
            'Laboratorium'
        ])
        ->pluck('id')
        ->toArray();

    // Gabungkan semua topik: wajib + yang dipilih user + yang baru
    $topicIds = collect([$defaultTopic->id])
        ->merge($selectedTopics)
        ->merge($extraTopics)
        ->unique()
        ->values()
        ->toArray();

    // Urutkan (Survei IKM duluan)
    $sortedTopicIds = collect($topicIds)
        ->sortBy(fn($id) => $id == $defaultTopic->id ? 0 : 1)
        ->values()
        ->toArray();

    // Ambil data topik beserta pertanyaannya
    $topics = Topic::with(['questions' => function ($q) {
            $q->orderBy('created_at');
        }])
        ->whereIn('id', $sortedTopicIds)
        ->orderByRaw('FIELD(id, ' . implode(',', $sortedTopicIds) . ')')
        ->get();

// Inisialisasi session jika belum ada atau update dengan topik baru
if (!$request->session()->has('selected_topics')) {
    $request->session()->put('selected_topics', $sortedTopicIds);
    $request->session()->put('current_topic_index', 0);
} else {
    // Jika session sudah ada, gabungkan dengan topik tambahan yang mungkin baru ditambahkan
    $existingTopics = $request->session()->get('selected_topics', []);
    $mergedTopics = collect($existingTopics)
        ->merge($sortedTopicIds)
        ->unique()
        ->values()
        ->toArray();
    $request->session()->put('selected_topics', $mergedTopics);
}


    if ($request->has('next_topic_id')) {
        $nextTopicId = (int) $request->query('next_topic_id');
        $indexOfNext = array_search($nextTopicId, $sortedTopicIds);
        if ($indexOfNext !== false) {
            $request->session()->put('current_topic_index', $indexOfNext);
        }
    }

    $index = $request->session()->get('current_topic_index', 0);
    $selectedTopicIds = $request->session()->get('selected_topics', []);

    if (!isset($selectedTopicIds[$index])) {
        $request->session()->forget(['selected_topics', 'current_topic_index']);
        return redirect()->route('feedback.result', $identitas->id);
    }

    $currentTopicId = $selectedTopicIds[$index];
    $currentTopic = $topics->firstWhere('id', $currentTopicId);

    if (!$currentTopic || $currentTopic->questions->isEmpty()) {
        abort(500, "Topik {$currentTopic->name} tidak memiliki pertanyaan");
    }

    return view('feedback.form', [
        'topics' => $topics,
        'identitas' => $identitas,
        'topic_ids' => implode(',', $sortedTopicIds),
        'current_topic' => $currentTopic,
        'current_topic_index' => $index,
        'total_topics' => count($selectedTopicIds),
    ]);
}


public function store(Request $request, Identitas $identitas, Topic $topic)
{
    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*.value' => 'required',
        'topic_ids' => 'required|string',
        'default_topic_id' => 'required|exists:topics,id',
    ]);

    DB::beginTransaction();
    try {
        // Simpan jawaban satu per satu
        foreach ($request->input('answers') as $question_id => $answer) {
            Feedback::create([
                'question_id' => \Str::startsWith($question_id, 's') ? null : $question_id,
                'pertanyaan_statis' => \Str::startsWith($question_id, 's') ? $question_id : null,
                'identitas_id' => $identitas->id,
                'unit_id' => $identitas->unit_layanan_id,
                'topic_id' => $topic->id,
                'answer' => $answer['value'],
                'comment' => $answer['comment'] ?? null,
                'tahun' => now()->year,
            ]);
        }

        

        // Pecah string topic_ids jadi array
        $topicIds = explode(',', $validated['topic_ids']);

        // Cari posisi current topic id dalam array
        $currentIndex = array_search($topic->id, $topicIds);

        // Dapatkan next topic id kalau ada
        $nextTopicId = null;
        if ($currentIndex !== false && isset($topicIds[$currentIndex + 1])) {
            $nextTopicId = $topicIds[$currentIndex + 1];
        }

        DB::commit();

                if ($nextTopicId) {
                    // Redirect ke topik berikutnya dengan topic_ids tetap
        return redirect()->route('feedback.start', [
            'identitas' => $identitas->id,
            'topic_ids' => implode(',', $topicIds),
            'next_topic_id' => $nextTopicId,
        ]);
    }

        // Jika tidak ada next topic, selesai survei
        return redirect()->route('feedback.result', $identitas->id);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error menyimpan feedback: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
    }
}

    public function result(Identitas $identitas)
    {
        $feedbacks = Feedback::where('identitas_id', $identitas->id)->with('question')->get();

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
        $unsurScores = [];
        foreach ($feedbacks as $fb) {
            if (!isset($scoreMap[$fb->answer])) continue;
            $unsur = $fb->question->unsur ?? 'U' . $fb->question_id;
            if (!isset($unsurScores[$unsur])) {
                $unsurScores[$unsur] = ['total' => 0, 'count' => 0];
            }
            $unsurScores[$unsur]['total'] += $scoreMap[$fb->answer];
            $unsurScores[$unsur]['count']++;
        }

        $jumlahUnsur = count($unsurScores);
        $ikm = 0;

        foreach ($unsurScores as $unsur => $data) {
            $nrr = $data['count'] > 0 ? $data['total'] / $data['count'] : 0;
            $tertimbang = $jumlahUnsur > 0 ? $nrr / $jumlahUnsur : 0;
            $ikm += $tertimbang;
        }

        $ikm = round($ikm * 25, 2);

        if ($ikm >= 88.31) {
            $mutu = 'A (Sangat Baik)';
        } elseif ($ikm >= 76.61) {
            $mutu = 'B (Baik)';
        } elseif ($ikm >= 65.00) {
            $mutu = 'C (Kurang Baik)';
        } else {
            $mutu = 'D (Tidak Baik)';
        }

        $identitas->ikm = $ikm;
        $identitas->kategori_mutu = $mutu;
        $identitas->save();

        return view('feedback.result', compact('feedbacks', 'ikm', 'mutu', 'jumlahUnsur'));
    }
}
