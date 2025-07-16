<x-app-layout>
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-center mb-4">Hasil Feedback</h2>
    <p class="mt-2 text-gray-700">Skor Rata-rata: <strong>{{ $ikm }}</strong></p>
    
    <hr class="my-4">
    <ul>
        @foreach ($feedbacks as $fb)
            <li class="mb-2">
                <strong>{{ $fb->question->text ?? 'Pertanyaan statis' }}</strong>: {{ $fb->answer }}
                @if ($fb->comment)
                    <br><em>Komentar:</em> "{{ $fb->comment }}"
                @endif
            </li>
        @endforeach
    </ul>
</div>
</x-app-layout>
