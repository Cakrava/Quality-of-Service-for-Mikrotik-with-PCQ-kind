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
        Schema::create('data_keuangans', function (Blueprint $table) {
            $table->id();
            $table->decimal('pembayaran_uang_sekolah', 15, 2);
            $table->decimal('pembayaran_ekstrakurikuler', 15, 2);
            $table->decimal('pembayaran_seragam_buku', 15, 2);
            $table->text('riwayat_pembayaran');
            $table->decimal('tagihan_belum_terbayar', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_keuangans');
    }
};
