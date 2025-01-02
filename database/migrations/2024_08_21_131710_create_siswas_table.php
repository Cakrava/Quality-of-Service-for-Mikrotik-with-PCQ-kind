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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn');
            $table->string('nama');
            $table->string('jurusan');
            $table->string('alamat');
            $table->string('jeniskelamin');
            $table->string('agama');
            $table->date('tanggal-lahir');
            $table->string('nomor');
            $table->string('email');
            $table->string('password');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
