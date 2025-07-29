<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Formulir Identitas Responden
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-blue-50 to-white min-h-screen">
        <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-2xl transition-all duration-300">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg animate-pulse">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg animate-bounce">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.identitas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    @php
                        $fields = [
                            ['id' => 'no_hp', 'label' => 'No HP', 'type' => 'text'],
                            ['id' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan']],
                            ['id' => 'usia', 'label' => 'Usia', 'type' => 'number'],
                            ['id' => 'pendidikan', 'label' => 'Pendidikan', 'type' => 'text'],
                            ['id' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text'],
                        ];
                    @endphp

                    @foreach($fields as $field)
                        <div class="group">
                            <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 mb-1 group-hover:text-blue-700 transition">
                                {{ $field['label'] }}
                            </label>

                            @if ($field['type'] === 'select')
                                <select id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition transform focus:scale-105"
                                        required>
                                    <option value="">Pilih</option>
                                    @foreach($field['options'] as $val => $label)
                                        <option value="{{ $val }}" {{ old($field['id']) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ $field['type'] }}" id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                                       value="{{ old($field['id']) }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition transform focus:scale-105"
                                       {{ $field['type'] === 'number' ? 'min=1 max=120' : '' }}
                                       required>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Informasi Topik -->
                <div class="mb-6 p-4 bg-blue-100 rounded-lg shadow">
                    <p class="font-medium text-blue-800 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pilihan topik survei bersifat opsional. Anda bisa memilih satu, beberapa, atau tidak memilih sama sekali.
                    </p>
                </div>

                <!-- Topik Tambahan -->
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Topik Survei Tambahan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($specialTopics as $topic)
                            <label for="topic_{{ $topic->id }}" class="flex items-start bg-gray-50 hover:bg-blue-50 border border-gray-300 rounded-lg p-4 cursor-pointer transition">
                                <input type="checkbox" id="topic_{{ $topic->id }}" name="topics[]" value="{{ $topic->id }}"
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-3"
                                       {{ in_array($topic->id, old('topics', [])) ? 'checked' : '' }}>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $topic->name }}</p>
                                    @if($topic->description)
                                        <p class="text-xs text-gray-500">{{ $topic->description }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex justify-between items-center">
                    <a href="{{ url()->previous() }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition transform hover:scale-105">
                        Lanjut <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
