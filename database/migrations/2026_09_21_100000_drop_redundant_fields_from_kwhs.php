<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kwhs', function (Blueprint $table) {
            $table->dropColumn(['building', 'type_pop']);
        });
    }

    public function down(): void
    {
        Schema::table('kwhs', function (Blueprint $table) {
            $table->string('building')->after('pop_id');
            $table->string('type_pop')->after('pic');
        });
    }
};
