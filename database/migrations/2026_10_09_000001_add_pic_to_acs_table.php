<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acs', function (Blueprint $table) {
            $table->string('pic')->nullable()->after('nomor_ac');
        });
    }

    public function down(): void
    {
        Schema::table('acs', fn (Blueprint $table) => $table->dropColumn('pic'));
    }
};
