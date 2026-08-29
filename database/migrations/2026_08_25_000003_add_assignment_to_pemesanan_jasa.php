<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan_jasa', function (Blueprint $table) {
            $table->foreignId('jasa_id')->nullable()->after('user_id')->constrained('jasa')->nullOnDelete();
            $table->foreignId('tim_id')->nullable()->after('keputusan')->constrained('tim')->nullOnDelete();
            $table->foreignId('diputuskan_oleh')->nullable()->after('tim_id')->constrained('users')->nullOnDelete();
            $table->timestamp('diputuskan_pada')->nullable()->after('diputuskan_oleh');
            $table->text('catatan_admin')->nullable()->after('diputuskan_pada');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan_jasa', function (Blueprint $table) {
            $table->dropForeign(['jasa_id']);
            $table->dropForeign(['tim_id']);
            $table->dropForeign(['diputuskan_oleh']);
            $table->dropColumn(['jasa_id', 'tim_id', 'diputuskan_oleh', 'diputuskan_pada', 'catatan_admin']);
        });
    }
};
