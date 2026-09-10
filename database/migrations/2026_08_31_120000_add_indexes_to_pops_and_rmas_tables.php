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
        Schema::table('pops', function (Blueprint $table) {
            $table->index('nama_pop');
            $table->index('kota_kabupaten');
            $table->index('provinsi');
        });

        Schema::table('rmas', function (Blueprint $table) {
            $table->index('tanggal');
            $table->index('so_po');
            $table->index('lokasi_asal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pops', function (Blueprint $table) {
            $table->dropIndex(['nama_pop']);
            $table->dropIndex(['kota_kabupaten']);
            $table->dropIndex(['provinsi']);
        });

        Schema::table('rmas', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['so_po']);
            $table->dropIndex(['lokasi_asal']);
        });
    }
};
