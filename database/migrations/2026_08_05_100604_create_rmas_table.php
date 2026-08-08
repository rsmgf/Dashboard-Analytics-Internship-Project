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
        Schema::create('rmas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemohon'); 
            $table->string('nama_manager'); 
            $table->boolean('is_material_rusak')->default(false); // Kolom baru untuk pertanyaan utama
            $table->string('so_po');
            $table->enum('valuation_type', ['ex-project', 'dismantle', 'rusak-L', 'rusak-TL']);
            $table->date('tanggal');
            $table->string('lokasi_asal');
            $table->string('merk');
            $table->string('type');
            $table->string('material_number')->nullable();
            $table->text('description')->nullable();
            $table->json('kerusakan')->nullable(); // Nullable jika tidak rusak
            $table->text('alasan')->nullable();
            $table->string('ttd_pemohon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rmas');
    }
};
