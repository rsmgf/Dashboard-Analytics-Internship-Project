<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Rename nama_alias -> nomor_recti pada tabel rectifiers
        if (Schema::hasColumn('rectifiers', 'nama_alias') && !Schema::hasColumn('rectifiers', 'nomor_recti')) {
            Schema::table('rectifiers', function (Blueprint $table) {
                $table->renameColumn('nama_alias', 'nomor_recti');
            });
        }

        // 2. Tambah kolom nomor_kwh pada tabel kwhs
        if (!Schema::hasColumn('kwhs', 'nomor_kwh')) {
            Schema::table('kwhs', function (Blueprint $table) {
                $table->string('nomor_kwh')->nullable()->after('pop_id');
            });
        }

        // 3. Backfill nomor_kwh untuk data KWH yang sudah ada
        $pops = DB::table('pops')->get();
        foreach ($pops as $pop) {
            $kwhs = DB::table('kwhs')
                ->where('pop_id', $pop->id)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            foreach ($kwhs as $index => $kwh) {
                $nomorKwh = $pop->kode_pop . '_KWH' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                DB::table('kwhs')->where('id', $kwh->id)->update(['nomor_kwh' => $nomorKwh]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rectifiers', 'nomor_recti')) {
            Schema::table('rectifiers', function (Blueprint $table) {
                $table->renameColumn('nomor_recti', 'nama_alias');
            });
        }

        if (Schema::hasColumn('kwhs', 'nomor_kwh')) {
            Schema::table('kwhs', function (Blueprint $table) {
                $table->dropColumn('nomor_kwh');
            });
        }
    }
};
