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
        Schema::create('gensets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pop_id')->constrained()->onDelete('cascade');

            // Nomor urut genset dalam POP, auto-generate
            $table->string('nomor_genset'); // Contoh: POP_1MBN10004_GEN01

            // Section 1 — General Info (zona & type_pop diambil dari tabel POPs)
            $table->string('pic');
            $table->string('bentuk_fisik');

            // Section 2 — Checklist Genset
            $table->string('merk_genset');   // Dropdown + "Others" free-text, disimpan ke sini
            $table->string('model');         // Dropdown + "Others" free-text, disimpan ke sini
            $table->string('sn_genset');
            $table->decimal('kapasitas_kva', 8, 2);
            $table->string('tipe_engine');   // Dropdown + "Others" free-text, disimpan ke sini
            $table->string('sn_engine');

            // Section 3 — Uji Genset
            $table->integer('tahun_pasang');                      // Tahun pemasangan: 2013-2045
            $table->date('tanggal_pm')->nullable();               // Tanggal PM terakhir
            $table->string('status_genset')->nullable();          // Sudah PM / Jadwal PM / Belum PM

            // Foto
            $table->string('photo_genset')->nullable();
            $table->string('keterangan_gambar_genset')->nullable();
            $table->string('photo_engine')->nullable();
            $table->string('keterangan_gambar_engine')->nullable();

            // Tracking
            $table->foreignId('diupdate_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Index
            $table->index('nomor_genset');
            $table->index('merk_genset');
            $table->index('status_genset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gensets');
    }
};
