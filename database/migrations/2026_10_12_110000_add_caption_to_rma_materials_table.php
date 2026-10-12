<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rma_materials', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('foto_path');
        });

        Schema::table('rmas', function (Blueprint $table) {
            $table->string('role_penanda_tangan', 100)->nullable()->after('nama_pemohon');
        });
    }

    public function down(): void
    {
        Schema::table('rma_materials', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });

        Schema::table('rmas', function (Blueprint $table) {
            $table->dropColumn('role_penanda_tangan');
        });
    }
};
