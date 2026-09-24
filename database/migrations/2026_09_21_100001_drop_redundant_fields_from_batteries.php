<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batteries', function (Blueprint $table) {
            $table->dropColumn(['building', 'type_pop', 'recti', 'nomor_recti']);
        });
    }

    public function down(): void
    {
        Schema::table('batteries', function (Blueprint $table) {
            $table->string('building')->after('rectifier_id');
            $table->string('type_pop')->after('pic');
            $table->string('recti')->nullable()->after('type_pop');
            $table->string('nomor_recti')->after('recti');
        });
    }
};
