<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Kategori: 'status' atau 'aktivitas'
            $table->enum('category', ['status', 'aktivitas']);

            // Tipe perangkat: rectifier, kwh, battery, ac, genset
            $table->enum('device_type', ['rectifier', 'kwh', 'battery', 'ac', 'genset']);

            // Referensi ke perangkat (polymorphic manual)
            $table->unsignedBigInteger('device_id');

            // Referensi POP
            $table->unsignedBigInteger('pop_id')->nullable();
            $table->string('pop_kode')->nullable(); // Simpan kode POP agar tetap bisa ditampilkan walau data berubah

            // Identitas perangkat (snapshot saat notif dibuat)
            $table->string('device_label')->nullable(); // e.g. "RECT-01", "KWH-001"

            // Severity/jenis status: warning, alert, belum_uji, jadwal_uji, belum_pm, jadwal_pm
            $table->string('severity')->nullable();

            // Pesan notifikasi
            $table->string('title');        // e.g. "Rectifier - POP-1KR8011"
            $table->string('message');      // e.g. "Utilisasi di atas 70%"

            // Untuk aktivitas: siapa yang melakukan, aksi apa (create/update/delete)
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_name')->nullable();
            $table->enum('action', ['create', 'update', 'delete'])->nullable();

            // Read status: null = belum dibaca
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['category', 'read_at']);
            $table->index(['device_type', 'device_id']);
            $table->index('pop_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
