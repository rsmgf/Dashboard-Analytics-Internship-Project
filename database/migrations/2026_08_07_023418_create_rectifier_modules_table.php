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
        Schema::create('rectifier_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rectifier_id')->constrained('rectifiers')->onDelete('cascade');
            $table->string('sn_modul')->unique();
            $table->string('kapasitas_ampere');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rectifier_modules');
    }
};
