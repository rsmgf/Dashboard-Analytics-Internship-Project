<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rectifiers', function (Blueprint $table) {
            $table->date('tanggal_pemasangan')->nullable()->after('tanggal_pemeriksaan');
        });

        Schema::table('kwhs', function (Blueprint $table) {
            $table->date('tanggal_pemasangan')->nullable()->after('tanggal_pemeriksaan');
        });

        Schema::table('batteries', function (Blueprint $table) {
            $table->date('tanggal_pemasangan')->nullable()->after('tanggal_uji_terakhir');
            $table->date('tanggal_pemeriksaan')->nullable()->after('tanggal_pemasangan');
        });

        Schema::table('acs', function (Blueprint $table) {
            $table->date('tanggal_pemeriksaan')->nullable()->after('tanggal_terakhir_pm');
        });

        Schema::table('gensets', function (Blueprint $table) {
            $table->date('tanggal_pemeriksaan')->nullable()->after('tanggal_pm');
        });
    }

    public function down(): void
    {
        Schema::table('rectifiers', fn (Blueprint $table) => $table->dropColumn('tanggal_pemasangan'));
        Schema::table('kwhs', fn (Blueprint $table) => $table->dropColumn('tanggal_pemasangan'));
        Schema::table('batteries', fn (Blueprint $table) => $table->dropColumn(['tanggal_pemasangan', 'tanggal_pemeriksaan']));
        Schema::table('acs', fn (Blueprint $table) => $table->dropColumn('tanggal_pemeriksaan'));
        Schema::table('gensets', fn (Blueprint $table) => $table->dropColumn('tanggal_pemeriksaan'));
    }
};
