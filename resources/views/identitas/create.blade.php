<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fas fa-id-card text-blue-600"></i> 
            Formulir Identitas Responden
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-100 via-white to-blue-50 min-h-screen">
        <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-2xl hover:shadow-blue-200 transition-all duration-300">
            
            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('identitas.store') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $fields = [
                            ['id' => 'no_hp', 'label' => 'No HP', 'type' => 'text', 'icon' => 'fas fa-phone'],
                            ['id' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan'], 'icon' => 'fas fa-venus-mars'],
                            ['id' => 'usia', 'label' => 'Usia', 'type' => 'number', 'icon' => 'fas fa-user-clock'],
                            ['id' => 'pendidikan', 'label' => 'Pendidikan', 'type' => 'text', 'icon' => 'fas fa-graduation-cap'],
                            ['id' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text', 'icon' => 'fas fa-briefcase'],
                        ];
                    @endphp

                    @foreach($fields as $field)
                        <div class="group relative">
                            <label for="{{ $field['id'] }}" class="block text-sm font-semibold text-gray-700 mb-1">
                                {{ $field['label'] }}
                            </label>
                            <div class="flex items-center">
                                <span class="absolute left-3 top-10 text-gray-400 group-hover:text-blue-500">
                                    <i class="{{ $field['icon'] }}"></i>
                                </span>
                                
                                @if ($field['type'] === 'select')
                                    <select id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                                        class="w-full pl-10 px-4 py-2 border rounded-lg bg-gray-50 focus:bg-white 
                                               focus:ring-2 focus:ring-blue-400 focus:border-blue-400 
                                               transition transform focus:scale-105"
                                        required>
                                        <option value="">--Pilih--</option>
                                        @foreach($field['options'] as $val => $label)
                                            <option value="{{ $val }}" {{ old($field['id']) == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="{{ $field['type'] }}" id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                                           value="{{ old($field['id']) }}"
                                           class="w-full pl-10 px-4 py-2 border rounded-lg bg-gray-50 focus:bg-white
                                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 
                                                  transition transform focus:scale-105"
                                           {{ $field['type'] === 'number' ? 'min=1 max=120' : '' }}
                                           required>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Informasi -->
                <div class="p-4 bg-blue-50 rounded-lg shadow-inner">
                    <p class="font-medium text-blue-700 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pilihan topik survei opsional. Bisa pilih satu, beberapa, atau tidak sama sekali.
                    </p>
                </div>

                <!-- Topik Tambahan -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Topik Survei Tambahan (opsional)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($specialTopics as $topic)
                            <label for="topic_{{ $topic->id }}" 
                                   class="flex items-start bg-gray-50 hover:bg-blue-100 border border-gray-300 
                                          rounded-lg p-4 cursor-pointer transition transform hover:scale-105">
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
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 
                                   focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 
                                   transition transform hover:scale-105 flex items-center gap-2">
                        Lanjut <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
