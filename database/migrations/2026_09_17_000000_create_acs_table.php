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
        Schema::create('acs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pop_id')->constrained()->onDelete('cascade');

            // Nomor urut AC dalam POP, auto-generate
            $table->string('nomor_ac'); // Contoh: POP_1MBN10004_AC01

            // Detail Air Conditioner
            $table->string('jenis_freon');        // Dropdown: R134a, R22, R32, R410, R410A + Others
            $table->string('merk_ac');            // Dropdown + Others
            $table->integer('tahun_manufaktur');  // Year picker (2013–sekarang)
            $table->string('type_ac');            // Inverter / Non Inverter / Others
            $table->string('pk');                 // 0.5, 1, 1.5, 2, 2.5, 5 PK

            // Uji / Jadwal PM
            $table->date('tanggal_instalasi')->nullable();
            $table->date('tanggal_terakhir_pm')->nullable();
            $table->string('status_ac')->nullable(); // Auto: Sudah PM / Jadwal PM / Belum PM

            // Foto
            $table->string('photo_ac')->nullable();
            $table->string('keterangan_gambar_ac')->nullable();

            // Tracking
            $table->foreignId('diupdate_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes
            $table->index('nomor_ac');
            $table->index('merk_ac');
            $table->index('status_ac');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acs');
    }
};
