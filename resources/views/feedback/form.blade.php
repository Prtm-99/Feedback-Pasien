<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-6 sm:p-10 rounded-xl shadow-lg mt-6">
        <h2 class="text-3xl font-extrabold mb-8 text-center text-blue-800">Form Feedback</h2>

        {{-- Pastikan variabel yang diperlukan ada --}}
        @isset($topics, $identitas, $topic_ids, $current_topic)
            @php
                $defaultTopic = $topics->firstWhere('is_default', true);
                // Pakai current_topic yang sudah dikirim dari controller langsung
                $currentTopic = $current_topic;
            @endphp

            @if($defaultTopic)
                {{-- Progress Tracker --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        @foreach($topics as $topic)
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                    {{ $currentTopic->id == $topic->id ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                                    {{ $loop->iteration }}
                                </div>
                                <span class="text-xs mt-1 text-center {{ $currentTopic->id == $topic->id ? 'font-bold text-blue-600' : 'text-gray-500' }}">
                                    {{ $topic->name }}
                                    @if($topic->is_default)
                                        <span class="block text-xs">(Wajib)</span>
                                    @endif
                                </span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                @if($currentTopic && $currentTopic->questions->isNotEmpty())
                    <form method="POST" action="{{ route('feedback.store', ['identitas' => $identitas->id, 'topic' => $currentTopic->id]) }}"
                        class="space-y-6 animate-fade-in">
                        @csrf
                        <input type="hidden" name="topic_ids" value="{{ $topic_ids }}">
                        <input type="hidden" name="default_topic_id" value="{{ $defaultTopic->id }}">

                        {{-- Judul Topik --}}
                        <div class="bg-blue-100 p-4 rounded-md border border-blue-300 shadow-sm">
                            <h3 class="text-xl font-semibold text-blue-900">
                                {{ $currentTopic->name }}
                                @if($currentTopic->is_default)
                                    <span class="text-sm font-normal">(Topik Wajib)</span>
                                @endif
                            </h3>
                            @if($currentTopic->description)
                                <p class="text-sm text-blue-700 mt-1">{{ $currentTopic->description }}</p>
                            @endif
                        </div>

                        {{-- Pertanyaan --}}
                        <div class="space-y-6">
                            @foreach($currentTopic->questions as $question)
                                @php
                                    $options = $question->answer_options 
                                        ? json_decode($question->answer_options, true)
                                        : ['Sangat Puas', 'Puas', 'Kurang', 'Sangat Kurang'];
                                @endphp

                                <div class="p-6 bg-gray-50 border border-gray-200 rounded-xl shadow-sm">
                                    <label class="block text-gray-900 font-semibold text-base mb-4">
                                        {{ $loop->iteration }}. {{ $question->text }}
                                        @if($question->is_required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach($options as $label)
                                @php
                                    $id = 'q'.$question->id.'_'.Str::slug($label);
                                @endphp
                                <div>
                                    <input type="radio" 
                                        name="answers[{{ $question->id }}][value]" 
                                        value="{{ $label }}" 
                                        id="{{ $id }}"
                                        class="hidden peer"
                                        {{ $question->is_required ? 'required' : '' }}>
                                    <label for="{{ $id }}" 
                                        class="block text-center px-4 py-3 border rounded-lg shadow-sm cursor-pointer 
                                                bg-white text-gray-800 border-gray-300 
                                                hover:bg-blue-50 transition-all 
                                                peer-checked:bg-blue-100 peer-checked:border-blue-500 peer-checked:text-blue-700">
                                        <span class="text-sm font-semibold">
                                            {{ $label }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                                    {{-- Kolom komentar selalu tampil --}}
                                    <label for="comment_{{ $question->id }}" class="block text-gray-700 mt-4 mb-1 text-sm font-medium">
                                        Komentar (opsional)
                                    </label>
                                    <input type="text" 
                                           id="comment_{{ $question->id }}"
                                           name="answers[{{ $question->id }}][comment]"
                                           placeholder="Tulis komentar (opsional)"
                                           class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none">
                                </div>
                            @endforeach
                        </div>

                        {{-- Tombol Navigasi --}}
                        <div class="flex flex-col sm:flex-row justify-between items-center pt-6 gap-4">
                            @if($currentTopic->id != $defaultTopic->id)
                                <a href="{{ route('feedback.start', [
                                        'identitas' => $identitas->id,
                                        'topic_ids' => $topic_ids
                                    ]) }}?prev_topic=1"
                                   class="inline-flex items-center gap-2 text-sm font-medium bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg shadow-md transition duration-200 ease-in-out">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            @else
                                <div></div>
                            @endif

                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition duration-200 ease-in-out">
                                {{ $currentTopic->id == $topics->last()->id ? 'Selesai' : 'Lanjutkan' }}
                            </button>
                        </div>
                    </form>
                @else
                    <div class="p-4 bg-red-50 text-red-700 rounded-lg">
                        <p class="font-bold">Tidak ada pertanyaan yang tersedia untuk topik ini.</p>
                        <p>Silakan hubungi administrator.</p>
                    </div>
                @endif
            @else
                <div class="p-4 bg-red-50 text-red-700 rounded-lg">
                    <p class="font-bold">Topik wajib Survei IKM tidak ditemukan.</p>
                    <p>Silakan hubungi administrator.</p>
                </div>
            @endif
        @else
            <div class="p-4 bg-red-50 text-red-700 rounded-lg">
                <p class="font-bold">Terjadi kesalahan sistem.</p>
                <p>Variabel yang diperlukan tidak tersedia. Silakan hubungi administrator.</p>
            </div>
        @endisset

    </div>
</x-app-layout>
