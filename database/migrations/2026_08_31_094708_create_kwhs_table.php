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
        Schema::create('kwhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pop_id')->constrained()->onDelete('cascade');

            // Section 1 — General Information
            $table->string('building');
            $table->string('pic');
            $table->string('type_pop');
            $table->string('id_customer_pln');
            $table->date('tanggal_pemeriksaan');

            // Section 2 — Spesifikasi Panel
            $table->string('daya_ps_gi');
            $table->string('mcb_utama');
            $table->enum('jumlah_phasa', ['1 Phasa', '3 Phasa'])->default('3 Phasa');
            $table->enum('keberadaan_arrester', ['ADA', 'TIDAK ADA'])->default('ADA');
            $table->string('merk_type_arrester')->nullable();
            $table->enum('status_utilisasi', ['Good', 'Warning', 'Alert'])->default('Good');
            
            // Section 3 — Pengukuran Tegangan & Arus
            $table->decimal('teg_rn', 8, 2)->nullable();
            $table->decimal('arus_r', 8, 2)->nullable();
            $table->decimal('teg_sn', 8, 2)->nullable();
            $table->decimal('arus_s', 8, 2)->nullable();
            $table->decimal('teg_tn', 8, 2)->nullable();
            $table->decimal('arus_t', 8, 2)->nullable();
            $table->decimal('teg_rs', 8, 2)->nullable();
            $table->decimal('teg_st', 8, 2)->nullable();
            $table->decimal('teg_rt', 8, 2)->nullable();
            $table->decimal('teg_ng', 8, 2)->nullable();
            $table->decimal('total_daya_terpakai', 10, 2)->nullable();
            $table->decimal('persentase_utilisasi', 5, 2)->nullable();
            $table->decimal('total_beban', 8, 2)->nullable();

            // Section 4 — Kabel Output
            $table->string('warna_r')->nullable();
            $table->string('warna_s')->nullable();
            $table->string('warna_t')->nullable();
            $table->string('warna_n')->nullable();
            $table->string('warna_g')->nullable();
            $table->string('ukuran_r')->nullable();
            $table->string('ukuran_s')->nullable();
            $table->string('ukuran_t')->nullable();
            $table->string('ukuran_n')->nullable();
            $table->string('ukuran_g')->nullable();

            $table->foreignId('diupdate_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwhs');
    }
};
