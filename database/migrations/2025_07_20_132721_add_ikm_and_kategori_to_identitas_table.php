<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('identitas', function (Blueprint $table) {
            $table->decimal('ikm', 5, 2)->nullable()->after('tanggal_survei');
            $table->string('kategori_mutu')->nullable()->after('ikm');
        });
    }

    public function down(): void
    {
        Schema::table('identitas', function (Blueprint $table) {
            $table->dropColumn(['ikm', 'kategori_mutu']);
        });
    }
};
