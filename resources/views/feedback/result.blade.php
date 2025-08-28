<x-app-layout>
    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
        <h2 class="text-3xl font-extrabold text-center text-blue-700 mb-6">Terima Kasih!</h2>
        <p class="text-center text-gray-700 text-lg mb-8">
            Terima kasih sudah mengisi kuisioner / feedback RS Muhammadiyah Gresik.  
            Masukan Anda sangat berharga untuk peningkatan layanan kami.
        </p>

        <div class="text-center mb-8">
            <a href="{{ route('dashboard') }}"
                class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 ease-in-out">
                Kembali ke Dashboard
            </a>
        </div>

        <hr class="border-gray-300 mb-8">

        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Detail Feedback Anda:</h3>
        <ul class="space-y-6">
            @foreach ($feedbacks as $fb)
                <li class="bg-gray-50 p-4 rounded shadow-sm">
                    <p class="font-semibold text-gray-900">{{ $fb->question->text ?? 'Pertanyaan statis' }}</p>
                    <p class="mt-1 text-gray-700"><span class="font-medium">Jawaban:</span> {{ $fb->answer }}</p>
                    @if ($fb->comment)
                        <p class="mt-2 italic text-gray-600">💬 Komentar: "{{ $fb->comment }}"</p>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
