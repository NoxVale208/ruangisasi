<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan_jasa', function (Blueprint $table) {
            $table->timestamp('ditugaskan_pada')
                ->nullable()
                ->after('tim_id');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan_jasa', function (Blueprint $table) {
            $table->dropColumn('ditugaskan_pada');
        });
    }
};