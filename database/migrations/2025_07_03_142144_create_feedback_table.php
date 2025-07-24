<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('identitas_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('unit_layanan')->onDelete('set null');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('set null');

            $table->enum('answer', ['Sangat Puas', 'Puas', 'Kurang', 'Sangat Kurang']);
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
