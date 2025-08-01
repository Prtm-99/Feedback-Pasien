<x-app-layout>
    <div class="max-w-4xl mx-auto bg-gradient-to-br from-blue-100 via-blue-200 to-blue-50 
                p-8 sm:p-10 rounded-3xl shadow-2xl mt-8 border border-blue-200">
        
        <!-- Header -->
        <h2 class="text-4xl font-extrabold mb-10 text-center text-blue-800 tracking-wide drop-shadow-lg">
            📝 Form Feedback Pasien
        </h2>

        @isset($topics, $identitas, $topic_ids, $current_topic)
            @php
                $defaultTopic = $topics->firstWhere('is_default', true);
                $currentTopic = $current_topic;
            @endphp

            @if($defaultTopic)

                <!-- STEP Progress -->
                <div class="mb-10">
                    <div class="flex items-center justify-between">
                        @foreach($topics as $topic)
                            <div class="flex flex-col items-center flex-1 relative">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-base font-bold shadow-md
                                    {{ $currentTopic->id == $topic->id 
                                        ? 'bg-blue-600 text-white ring-4 ring-blue-300 animate-pulse' 
                                        : 'bg-blue-200 text-blue-700' }}">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="text-center text-sm mt-3 {{ $currentTopic->id == $topic->id ? 'text-blue-800 font-semibold' : 'text-blue-600' }}">
                                    {{ $topic->name }}
                                    @if($topic->is_default)
                                        <div class="text-[11px] text-blue-500">(Wajib)</div>
                                    @endif
                                </div>
                                @if(!$loop->last)
                                    <div class="absolute right-[-50%] top-6 h-1 w-full bg-blue-300 z-[-1]"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form -->
                @if($currentTopic && $currentTopic->questions->isNotEmpty())
                    <form method="POST" action="{{ route('feedback.store', ['identitas' => $identitas->id, 'topic' => $currentTopic->id]) }}" 
                          class="animate-fade-in">
                        @csrf
                        <input type="hidden" name="topic_ids" value="{{ $topic_ids }}">
                        <input type="hidden" name="default_topic_id" value="{{ $defaultTopic->id }}">

                        <!-- Judul Topik -->
                        <div class="mb-8 bg-blue-50 p-5 rounded-xl border-l-4 border-blue-500 shadow">
                            <h3 class="text-2xl font-bold text-blue-800">{{ $currentTopic->name }}</h3>
                            @if($currentTopic->description)
                                <p class="text-sm text-blue-700 mt-2">{{ $currentTopic->description }}</p>
                            @endif
                        </div>

                        <!-- Pertanyaan -->
                        <div class="space-y-6">
                            @foreach($currentTopic->questions as $question)
                                @php
                                    $options = $question->answer_options 
                                        ? json_decode($question->answer_options, true)
                                        : ['Sangat Puas', 'Puas', 'Kurang', 'Sangat Kurang'];
                                @endphp

                                <div class="p-6 bg-white/80 border border-blue-200 rounded-2xl shadow-md hover:shadow-lg transition transform hover:scale-[1.01]">
                                    <label class="block text-blue-900 font-semibold text-lg mb-4">
                                        {{ $loop->iteration }}. {{ $question->text }}
                                        @if($question->is_required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    <!-- Opsi Jawaban -->
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                        @foreach($options as $label)
                                            @php $id = 'q'.$question->id.'_'.Str::slug($label); @endphp
                                            <div>
                                                <input type="radio" 
                                                    name="answers[{{ $question->id }}][value]" 
                                                    value="{{ $label }}" 
                                                    id="{{ $id }}"
                                                    class="hidden peer"
                                                    {{ $question->is_required ? 'required' : '' }}>
                                                <label for="{{ $id }}" 
                                                    class="block text-center px-4 py-3 border rounded-xl shadow cursor-pointer
                                                           bg-white text-blue-700 border-blue-300 
                                                           hover:bg-blue-100 hover:border-blue-400
                                                           peer-checked:bg-blue-200 peer-checked:border-blue-600 peer-checked:text-blue-900 
                                                           transition duration-200">
                                                    <span class="text-sm font-medium">
                                                        {{ $label }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Komentar -->
                                    <label for="comment_{{ $question->id }}" class="block text-sm font-medium text-blue-700 mt-5 mb-1">
                                        Komentar (opsional)
                                    </label>
                                    <input type="text" 
                                           id="comment_{{ $question->id }}"
                                           name="answers[{{ $question->id }}][comment]"
                                           placeholder="💬 Berikan komentar atau saran..."
                                           class="w-full px-4 py-2 border border-blue-300 rounded-lg bg-blue-50
                                                  focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition">
                                </div>
                            @endforeach
                        </div>

                        <!-- Tombol Navigasi -->
                        <div class="flex justify-end items-center pt-10">
                            <button type="submit"
                                    class="inline-flex items-center gap-3 px-8 py-3 bg-blue-600 text-white text-lg font-semibold rounded-xl 
                                           hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 
                                           shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                {{ $currentTopic->id == $topics->last()->id ? '✅ Selesai' : 'Lanjutkan ➡' }}
                            </button>
                        </div>
                    </form>
                @else
                    <div class="p-5 bg-red-100 border border-red-300 text-red-700 rounded-xl shadow">
                        <strong>⚠️ Topik tidak memiliki pertanyaan.</strong><br>
                        Silakan hubungi admin untuk memperbarui data survei.
                    </div>
                @endif
            @else
                <div class="p-5 bg-red-100 border border-red-300 text-red-700 rounded-xl shadow">
                    <strong>⚠️ Topik wajib tidak ditemukan.</strong><br>
                    Silakan hubungi admin.
                </div>
            @endif
        @else
            <div class="p-5 bg-red-100 border border-red-300 text-red-700 rounded-xl shadow">
                <strong>⚠️ Data survei tidak tersedia.</strong><br>
                Silakan hubungi administrator.
            </div>
        @endisset
    </div>

    @push('styles')
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.5s ease-in-out; }
    </style>
    @endpush
</x-app-layout>
