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
        Schema::create('pops', function (Blueprint $table) {
            $table->id(); // Auto-increment ID standar untuk relasi backend (Primary Key)
            $table->string('kode_pop')->unique(); // Contoh: POP_00AAA00
            $table->string('nama_pop'); // Contoh: Selincah
            $table->string('provinsi'); // Contoh: Jambi
            $table->string('kota_kabupaten'); // Contoh: Kota Jambi
            $table->string('tipe_pop')->nullable(); // Contoh: POP-A, POP-B, POP-SB
            $table->string('jenis_bangunan')->nullable(); // Contoh: Shelter, ODC, Mini POP
            $table->text('lokasi')->nullable(); //contoh Muara bungo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pops');
    }
};
