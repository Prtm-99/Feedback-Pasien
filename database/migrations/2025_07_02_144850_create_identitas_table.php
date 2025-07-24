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
Schema::create('identitas', function (Blueprint $table) {
    $table->id();
    $table->string('no_hp');
    $table->string('jenis_kelamin');
    $table->integer('usia');
    $table->string('pendidikan');
    $table->string('pekerjaan');
    $table->date('tanggal_survei');
    $table->time('jam_survei');
    $table->foreignId('unit_layanan_id')->nullable()->constrained('unit_layanan')->onDelete('set null');
    $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('set null');
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identitas');
    }
};
