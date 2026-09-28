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
        Schema::table('rmas', function (Blueprint $table) {
            if (!Schema::hasColumn('rmas', 'judul_rma')) {
                $table->string('judul_rma')->nullable()->after('id');
            }
            if (!Schema::hasColumn('rmas', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('judul_rma')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmas', function (Blueprint $table) {
            if (Schema::hasColumn('rmas', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('rmas', 'judul_rma')) {
                $table->dropColumn('judul_rma');
            }
        });
    }
};
