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
            // Drop fields yang sudah tidak dipakai lagi
            if (Schema::hasColumn('batteries', 'area_sti')) {
                $table->dropColumn('area_sti');
            }
            if (Schema::hasColumn('batteries', 'backup_timer')) {
                $table->dropColumn('backup_timer');
            }

            // Tambahkan field pendukung yang dipakai di form & detail
            if (!Schema::hasColumn('batteries', 'tegangan')) {
                $table->decimal('tegangan', 5, 2)->nullable()->default(48)->after('tipe_battery');
            }
            if (!Schema::hasColumn('batteries', 'photo_battery')) {
                $table->string('photo_battery')->nullable()->after('status_uji');
            }
            if (!Schema::hasColumn('batteries', 'keterangan_gambar')) {
                $table->string('keterangan_gambar')->nullable()->after('photo_battery');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batteries', function (Blueprint $table) {
            $table->string('area_sti')->nullable();
            $table->decimal('backup_timer', 8, 2)->nullable();
            $table->dropColumn(['tegangan', 'photo_battery', 'keterangan_gambar']);
        });
    }
};
