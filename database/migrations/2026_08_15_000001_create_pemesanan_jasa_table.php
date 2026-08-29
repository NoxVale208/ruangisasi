<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan_jasa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_jasa');
            $table->text('alamat');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedBigInteger('budget');
            $table->enum('status_persetujuan', ['menunggu', 'setuju', 'tidak_setuju'])->default('menunggu');
            $table->enum('status_proses', ['menunggu', 'menunggu_tim', 'pengerjaan', 'perbaikan', 'selesai', 'ditolak'])->default('menunggu');
            $table->enum('keputusan', ['setuju', 'tidak_setuju'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan_jasa');
    }
};
