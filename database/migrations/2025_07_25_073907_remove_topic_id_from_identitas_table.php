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
        if (Schema::hasColumn('identitas', 'topic_id')) {
            Schema::table('identitas', function (Blueprint $table) {
                $table->dropColumn('topic_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('identitas', function (Blueprint $table) {
            $table->unsignedBigInteger('topic_id')->nullable();
            // Sesuaikan jika butuh foreign key:
            // $table->foreign('topic_id')->references('id')->on('topics')->onDelete('set null');
        });
    }
};
