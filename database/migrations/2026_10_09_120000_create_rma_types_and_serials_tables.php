<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rma_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained('rmas')->cascadeOnDelete();
            $table->string('merk');
            $table->string('type');
            $table->string('material_number')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('rma_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_type_id')->constrained('rma_types')->cascadeOnDelete();
            $table->string('serial_number');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('rma_materials', function (Blueprint $table) {
            $table->foreignId('rma_serial_id')->nullable()->after('rma_id')->constrained('rma_serials')->nullOnDelete();
        });

        DB::table('rmas')->orderBy('id')->chunk(200, function ($rmas) {
            foreach ($rmas as $rma) {
                $typeId = DB::table('rma_types')->insertGetId([
                    'rma_id' => $rma->id,
                    'merk' => $rma->merk ?? '',
                    'type' => $rma->type ?? '',
                    'material_number' => $rma->material_number,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $serialId = DB::table('rma_serials')->insertGetId([
                    'rma_type_id' => $typeId,
                    'serial_number' => $rma->serial_number ?: (DB::table('rma_materials')->where('rma_id', $rma->id)->value('serial_number') ?: ''),
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('rma_materials')->where('rma_id', $rma->id)->update(['rma_serial_id' => $serialId]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('rma_materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rma_serial_id');
        });
        Schema::dropIfExists('rma_serials');
        Schema::dropIfExists('rma_types');
    }
};
