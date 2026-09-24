<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill nama_alias untuk semua Rectifier yang sudah ada.
     * Format: {kode_pop}_RECT01, _RECT02, dst. (ordered by created_at, id)
     */
    public function up(): void
    {
        $pops = DB::table('pops')->get();

        foreach ($pops as $pop) {
            $rectifiers = DB::table('rectifiers')
                ->where('pop_id', $pop->id)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            foreach ($rectifiers as $index => $rectifier) {
                $namaAlias = $pop->kode_pop . '_RECT' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                DB::table('rectifiers')
                    ->where('id', $rectifier->id)
                    ->update(['nama_alias' => $namaAlias]);
            }
        }
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan ke nilai manual lama — cukup set null
        DB::table('rectifiers')->update(['nama_alias' => null]);
    }
};
