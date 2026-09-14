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
        Schema::table('batteries', function (Blueprint $table) {
            if (!Schema::hasColumn('batteries', 'vrla_1')) {
                $table->decimal('vrla_1', 8, 2)->nullable()->after('kapasitas_uji');
                $table->decimal('vrla_2', 8, 2)->nullable()->after('vrla_1');
                $table->decimal('vrla_3', 8, 2)->nullable()->after('vrla_2');
                $table->decimal('vrla_4', 8, 2)->nullable()->after('vrla_3');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batteries', function (Blueprint $table) {
            $table->dropColumn(['vrla_1', 'vrla_2', 'vrla_3', 'vrla_4']);
        });
    }
};
