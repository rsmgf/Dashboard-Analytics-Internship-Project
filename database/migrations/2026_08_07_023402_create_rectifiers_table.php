<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rectifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pop_id')->constrained('pops')->onDelete('cascade');

            // Informasi dasar
            $table->string('nama_alias');           // Label card, contoh: Rectifier Utama 1
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_pemeriksaan')->nullable();
            $table->string('pic')->nullable();       // Penanggung jawab pemeriksaan

            // Data teknis utama
            $table->string('merk');
            $table->string('type');
            $table->string('sn_rectifier')->unique();
            $table->integer('kapasitas_slot');       // Jumlah total slot tersedia

            // Data teknis tambahan
            $table->string('couple')->nullable();                   // Couple / tidak
            $table->string('type_modul_controller')->nullable();    // Type Modul Controller
            $table->string('type_modul_power')->nullable();         // Type Modul Power
            $table->string('kapasitas_rectifier')->nullable();      // Kapasitas Rectifier (A)
            $table->string('beban')->nullable();                    // Beban (A)
            $table->string('utilisasi')->nullable();                // Utilisasi Rectifier (%)
            $table->string('foto_rectifier')->nullable();           // Path foto upload

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rectifiers');
    }
};
