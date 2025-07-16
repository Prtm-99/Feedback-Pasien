<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-xl shadow-lg mt-6">

        {{-- Tombol Kembali --}}
        <div class="mb-4">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
                ← Kembali
            </a>
        </div>

        <h2 class="text-2xl font-bold mb-6 text-blue-800 text-center">
            📝 Feedback untuk {{ $identitas->nama ?? 'Pasien Tidak Dikenal' }}
        </h2>

        @php
            $nextTopicId = session('next_topic_id');
            if (!isset($topics) || $topics->isEmpty()) {
                echo "<p class='text-red-600 text-center'>⚠️ Data topik tidak tersedia.</p>";
            } else {
                $topics = $topics->where('id', $nextTopicId ?? $topics->first()->id);
            }
        @endphp

        @foreach ($topics as $index => $topic)
            <form method="POST" action="{{ route('feedback.store', ['identitas' => $identitas->id, 'topic' => $topic->id]) }}"
                  class="space-y-6 animate-fade-in">
                @csrf

                <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-700">{{ $topic->name }}</h3>
                </div>

                {{-- PERTANYAAN DINAMIS --}}
                <div class="space-y-4">
@foreach ($topic->questions as $question)
    @php
        $isStatis = $question->type === 'statis';
        $options = $isStatis && $question->answer_options
            ? json_decode($question->answer_options, true)
            : ['Sangat Puas', 'Puas','Kurang', 'Sangat Kurang'];
    @endphp

    <div class="p-4 bg-gray-50 border rounded-md shadow-sm">
        <label class="block text-gray-800 font-medium mb-2">
            {{ $question->text }}
        </label>

        <div class="flex flex-wrap gap-3">
            @foreach ($options as $label)
                <label class="flex items-center gap-2 bg-white border px-4 py-2 rounded cursor-pointer hover:bg-blue-50 transition">
                    <input type="radio" name="answers[{{ $question->id }}][value]" value="{{ $label }}" required>
                    <span class="text-sm font-medium">{{ $label }}</span>
                </label>
            @endforeach
        </div>

        <input type="text" name="answers[{{ $question->id }}][comment]"
               placeholder="🗣️ Komentar (opsional)"
               class="mt-3 w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none">
    </div>
@endforeach

                </div>

                {{-- PERTANYAAN STATIS --}}
                @if ($loop->first && isset($staticQuestions) && count($staticQuestions))
                    <div class="border-t pt-6 mt-6">
                        <h4 class="font-semibold text-gray-700 mb-4">📋 Pertanyaan Tambahan (Statis)</h4>

@foreach ($staticQuestions as $sq)
    @php
        $options = json_decode($sq->answer_options, true);
    @endphp

    <div class="mb-4 bg-gray-50 p-4 rounded border">
        <label class="block text-gray-800 font-medium mb-2">{{ $sq->text }}</label>
        <select name="answers[{{ $sq->id }}][value]" required
                class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
            <option disabled selected>-- Pilih jawaban --</option>
            @foreach ($options as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
        </select>
        <textarea name="answers[{{ $sq->id }}][comment]" rows="2"
                  placeholder="Komentar (opsional)"
                  class="mt-2 w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none"></textarea>
    </div>
@endforeach

                    </div>
                @endif

                <div class="flex justify-end pt-4">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition duration-200 ease-in-out">
                        Lanjut ➡️
                    </button>
                </div>
            </form>
        @endforeach
    </div>

    {{-- Animasi Fade --}}
    <style>
        .animate-fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>
