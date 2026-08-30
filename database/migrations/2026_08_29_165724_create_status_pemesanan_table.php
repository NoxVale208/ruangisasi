<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_jasa_id')
                ->constrained('pemesanan_jasa')
                ->cascadeOnDelete();
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->foreignId('diubah_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_pemesanan');
    }
};
