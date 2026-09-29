<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BackfillJudulRmaSeeder extends Seeder
{
    public function run(): void
    {
        $rmas = DB::table('rmas')->whereNull('judul_rma')->get(['id', 'merk', 'lokasi_asal']);

        foreach ($rmas as $rma) {
            $merk   = $rma->merk     ?? 'Device';
            $lokasi = $rma->lokasi_asal ?? 'POP';
            $judul  = "RMA {$merk} - {$lokasi}";

            DB::table('rmas')->where('id', $rma->id)->update(['judul_rma' => $judul]);

            $this->command->info("Updated RMA #{$rma->id} → {$judul}");
        }

        $this->command->info("Done! {$rmas->count()} records backfilled.");
    }
}
