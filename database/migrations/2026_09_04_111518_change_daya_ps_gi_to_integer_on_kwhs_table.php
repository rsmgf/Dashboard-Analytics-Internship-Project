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
        Schema::table('kwhs', function (Blueprint $table) {
            $table->unsignedInteger('daya_ps_gi')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kwhs', function (Blueprint $table) {
            $table->string('daya_ps_gi')->change();
        });
    }
};
