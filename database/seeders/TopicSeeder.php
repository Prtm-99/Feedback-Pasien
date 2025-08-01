<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run()
    {
$topics = [
    [
        'name' => 'Survei IKM',
        'is_default' => true,
        'category' => 'default',
        'is_active' => true
    ],
];


        foreach ($topics as $topic) {
            Topic::updateOrCreate(
                ['name' => $topic['name']],
                $topic
            );
        }
    }
}