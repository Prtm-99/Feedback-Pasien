<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-6 sm:p-10 rounded-xl shadow-lg mt-6">

        <h2 class="text-3xl font-extrabold mb-8 text-center text-blue-800">Form Feedback</h2>

        @php
            $nextTopicId = session('next_topic_id') ?? $topics->first()?->id;
            $currentTopic = $topics->firstWhere('id', $nextTopicId);

            if (!$currentTopic) {
                echo "<p class='text-red-600 text-center font-medium'>Data topik tidak ditemukan.</p>";
            }
        @endphp

        @if ($currentTopic)
            <form method="POST" action="{{ route('feedback.store', ['identitas' => $identitas->id, 'topic' => $currentTopic->id]) }}"
                    class="space-y-6 animate-fade-in">
                @csrf

                {{-- Judul Topik --}}
                <div class="bg-blue-100 p-4 rounded-md border border-blue-300 shadow-sm">
                    <h3 class="text-xl font-semibold text-blue-900">
                        {{ $currentTopic->name }}
                    </h3>
                </div>

                {{-- Pertanyaan Dinamis --}}
                <div class="space-y-6">
                    @foreach ($currentTopic->questions as $question)
                        @php
                            $isStatis = $question->type === 'statis';
                            $options = $isStatis && $question->answer_options
                                ? json_decode($question->answer_options, true)
                                : ['Sangat Puas', 'Puas', 'Kurang', 'Sangat Kurang'];
                        @endphp

                        <div class="p-6 bg-gray-50 border border-gray-200 rounded-xl shadow-sm">
                            <label class="block text-gray-900 font-semibold text-base mb-4">
                                {{ $question->text }}
                            </label>

                            <div class="flex flex-wrap gap-4">
                                @foreach ($options as $label)
                                    <label class="flex items-center gap-2 px-4 py-2 border rounded-lg cursor-pointer bg-white shadow-sm hover:bg-blue-50 transition-all ring-1 ring-transparent peer-checked:ring-blue-500">
                                        <input type="radio" name="answers[{{ $question->id }}][value]" value="{{ $label }}" required class="hidden peer">
                                        <span class="text-sm font-medium peer-checked:text-blue-600">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <input type="text" name="answers[{{ $question->id }}][comment]"
                                   placeholder="Tulis komentar (opsional)"
                                   class="mt-4 w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none"
                            >
                        </div>
                    @endforeach
                </div>
                {{-- Tombol Navigasi --}}
                <div class="flex flex-col sm:flex-row justify-between items-center pt-6 gap-4">
                    {{-- Tombol Kembali --}}
                    <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition duration-200 ease-in-out">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>

                    {{-- Tombol Lanjutkan --}}
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition duration-200 ease-in-out">
                        Lanjutkan
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- Animasi Fade In --}}
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

    {{-- FontAwesome (untuk ikon tombol kembali) --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</x-app-layout>
