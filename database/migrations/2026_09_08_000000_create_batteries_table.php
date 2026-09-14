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
        Schema::create('batteries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pop_id')->constrained()->onDelete('cascade');
            $table->foreignId('rectifier_id')->nullable()->constrained('rectifiers')->nullOnDelete();

            // Section 1 — General Information
            $table->string('building');
            $table->string('pic');
            $table->string('type_pop');
            $table->string('recti')->nullable();

            // Section 2 — Checklist Baterai
            $table->string('nomor_recti');
            $table->string('nomor_bank');
            $table->string('merk_battery');
            $table->string('tipe_battery');
            $table->enum('jenis_battery', ['Lithium', 'VRLA'])->default('Lithium');
            $table->decimal('kapasitas_battery', 8, 2); // 20, 50, 100, 200 AH
            $table->decimal('kapasitas_uji', 8, 2)->nullable(); // manual input
            $table->decimal('kapasitas_battery_persen', 5, 2)->nullable(); // (kapasitas_uji / kapasitas_battery) * 100
            $table->string('performa_baterai')->nullable(); // 1-EXCELLENT, 2-GOOD ENOUGH, 3-WARNING, 4-ALERT, BLM UJI BATT
            $table->decimal('backup_timer', 8, 2)->nullable(); // Hours

            // Section 3 — Uji Baterai
            $table->date('tanggal_uji_terakhir')->nullable();
            $table->date('tanggal_penggantian')->nullable();
            $table->string('status_uji')->nullable(); // SUDAH UJI BATT, JADWAL UJI BATT, BLM UJI BATT
            $table->string('area_sti')->nullable();

            // Tracking
            $table->foreignId('diupdate_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexing for search & queries
            $table->index('nomor_recti');
            $table->index('nomor_bank');
            $table->index('merk_battery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batteries');
    }
};
