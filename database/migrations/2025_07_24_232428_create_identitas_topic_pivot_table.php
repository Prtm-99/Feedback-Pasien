<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identitas_topic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identitas_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('topic_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->timestamps();
            
            // Tambahkan index unik untuk mencegah duplikasi
            $table->unique(['identitas_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identitas_topic');
    }
};