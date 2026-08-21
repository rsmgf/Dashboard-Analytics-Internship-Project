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
        Schema::create('rectifier_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rectifier_id')->constrained('rectifiers')->onDelete('cascade');
            $table->string('nama_mcb');                  // Contoh: MCB 1, MCB 2
            $table->string('merk_mcb')->nullable();      // Contoh: Nader, CHNT
            $table->string('kapasitas_mcb')->nullable(); // Contoh: 64 A
            $table->string('peruntukan')->nullable();    // Contoh: BAT I, SPARE, BMS
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rectifier_outputs');
    }
};
