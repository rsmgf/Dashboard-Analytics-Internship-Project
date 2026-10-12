<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('rmas', 'customer_name')) {
            Schema::table('rmas', function (Blueprint $table) {
                $table->string('customer_name')->nullable()->after('lokasi_asal');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rmas', 'customer_name')) {
            Schema::table('rmas', function (Blueprint $table) {
                $table->dropColumn('customer_name');
            });
        }
    }
};
