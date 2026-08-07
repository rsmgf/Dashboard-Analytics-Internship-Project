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
            $table->string('nama_alias'); // Contoh: Rectifier 1
            $table->text('deskripsi')->nullable();
            $table->string('merk');
            $table->string('type');
            $table->string('sn_rectifier')->unique();
            $table->integer('kapasitas_slot'); // Jumlah total slot yang tersedia (misal: 5)
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
