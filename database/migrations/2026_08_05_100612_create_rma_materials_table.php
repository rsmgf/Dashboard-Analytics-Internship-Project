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
        Schema::create('rma_materials', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel rmas (jika RMA dihapus, foto ikut terhapus)
            $table->foreignId('rma_id')->constrained('rmas')->onDelete('cascade'); 
            $table->string('serial_number'); // Nomor seri per foto
            $table->string('foto_path');     // Lokasi file per foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_materials');
    }
};
