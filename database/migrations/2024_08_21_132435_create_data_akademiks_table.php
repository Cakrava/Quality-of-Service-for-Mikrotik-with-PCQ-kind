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
        Schema::create('data_akademiks', function (Blueprint $table) {
            $table->id();
            $table->string('kelas');
            $table->integer('nomor_absen');
            $table->json('mata_pelajaran_diambil');
            $table->json('nilai_tugas');
            $table->json('nilai_ujian');
            $table->decimal('nilai_akhir', 5, 2);
            $table->text('rapor');
            $table->string('status_kenaikan_kelas');
            $table->text('riwayat_prestasi_akademik');
            $table->json('kehadiran_absensi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_akademiks');
    }
};
