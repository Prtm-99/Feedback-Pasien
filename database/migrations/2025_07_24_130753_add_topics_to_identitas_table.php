<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('identitas', function (Blueprint $table) {
        $table->json('selected_topics')->nullable()->after('unit_layanan_id');
    });
}

public function down()
{
    Schema::table('identitas', function (Blueprint $table) {
        $table->dropColumn('selected_topics');
    });
}
};
