<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rmas', function (Blueprint $table) {
            if (!Schema::hasColumn('rmas', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('type');
            }
        });

        // Backfill serial_number dari tabel rma_materials jika ada
        $rmas = DB::table('rmas')->whereNull('serial_number')->get(['id']);
        foreach ($rmas as $rma) {
            $mat = DB::table('rma_materials')
                ->where('rma_id', $rma->id)
                ->whereNotNull('serial_number')
                ->where('serial_number', '!=', '')
                ->first();

            if ($mat && !empty($mat->serial_number)) {
                DB::table('rmas')->where('id', $rma->id)->update([
                    'serial_number' => $mat->serial_number,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmas', function (Blueprint $table) {
            if (Schema::hasColumn('rmas', 'serial_number')) {
                $table->dropColumn('serial_number');
            }
        });
    }
};
