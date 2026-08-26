<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rectifier_modules', function (Blueprint $table) {
            $table->string('kapasitas_ampere')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rectifier_modules', function (Blueprint $table) {
            $table->string('kapasitas_ampere')->nullable(false)->change();
        });
    }
};
