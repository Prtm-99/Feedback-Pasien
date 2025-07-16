<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Topic;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $topic = Topic::firstOrCreate([
            'name' => 'Survei IKM'
        ]);

        // Daftar pertanyaan statis lengkap dengan opsi jawaban khusus
        $staticQuestions = [
            ['unsur' => 'U1', 'text' => 'Adakah kesesuaian persyaratan pelayanan dengan jenis pelayanan (Khusus pasien asuransi)', 'options' => ['Sangat sesuai', 'Sesuai', 'Kurang sesuai', 'Tidak sesuai']],
            ['unsur' => 'U2', 'text' => 'Apakah anda mendapatkan kemudahan prosedur pelayanan di unit ini ?', 'options' => ['Sangat mudah', 'Mudah', 'Kurang mudah', 'Tidak mudah']],
            ['unsur' => 'U3', 'text' => 'Bagaimana pendapat anda tentang kecepatan waktu dalam memberikan pelayanan', 'options' => ['Sangat cepat', 'Cepat', 'Kurang cepat', 'Lambat']],
            ['unsur' => 'U4', 'text' => 'Bagaimana pendapat anda tentang kewajaran biaya/tarif dalam pelayanan', 'options' => ['Gratis', 'Murah', 'Cukup mahal', 'Sangat mahal']],
            ['unsur' => 'U5', 'text' => 'Adakah kesesuaian produk pelayanan antara yang tercantum dengan pelayanan yang diberikan', 'options' => ['Sangat sesuai', 'Sesuai', 'Kurang sesuai', 'Tidak sesuai']],
            ['unsur' => 'U6', 'text' => 'Bagaimana pendapat anda tentang kompetensi/kemampuan petugas dalam pelayanan', 'options' => ['Sangat kompeten', 'Kompeten', 'Kurang kompeten', 'Tidak kompeten']],
            ['unsur' => 'U7', 'text' => 'Bagaimana pendapat anda terkait kesopanan dan keramahan petugas', 'options' => ['Sangat sopan dan ramah', 'Sopan dan ramah', 'Kurang sopan dan ramah', 'Tidak sopan dan ramah']],
            ['unsur' => 'U8', 'text' => 'Bagaimana pendapat anda tentang penanganan komplain di unit', 'options' => ['Dikelola dengan baik', 'Kurang maksimal', 'Tidak berfungsi', 'Tidak ada']],
            ['unsur' => 'U9', 'text' => 'Bagaimana pendapat anda tentang kualitas sarana dan prasarana', 'options' => ['Sangat baik', 'Baik', 'Cukup', 'Buruk']],
            ['unsur' => 'U10', 'text' => 'Apakah petugas menyampaikan informasi tentang kondisi pasien dengan jelas', 'options' => ['Ya', 'Tidak, karena ...']],
            ['unsur' => 'U11', 'text' => 'Apakah petugas kasir menyampaikan rincian biaya dengan jelas', 'options' => ['Ya', 'Tidak, karena ...']],
            ['unsur' => 'U12', 'text' => 'Apakah diet yang diberikan sesuai dengan kondisi pasien', 'options' => ['Ya', 'Tidak, karena ...']],
        ];

        foreach ($staticQuestions as $q) {
            Question::create([
                'topic_id'       => $topic->id,
                'unsur'          => $q['unsur'],
                'text'           => $q['text'],
                'type'           => 'statis',
                'answer_options' => json_encode($q['options']),
            ]);
        }
    }
}
