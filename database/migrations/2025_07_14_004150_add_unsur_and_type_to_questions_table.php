<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('unsur')->nullable(); // Tambah kolom unsur
            $table->string('type')->nullable();  // Tambah kolom tipe (statis/dinamis)
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('unsur');
            $table->dropColumn('type');
        });
    }
};
