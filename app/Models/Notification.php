<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    protected $fillable = [
        'category',
        'device_type',
        'device_id',
        'pop_id',
        'pop_kode',
        'device_label',
        'severity',
        'title',
        'message',
        'actor_id',
        'actor_name',
        'action',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    //  Scopes
    // ──────────────────────────────────────────────

    public function scopeUnread(Builder $q): Builder
    {
        return $q->whereNull('read_at');
    }

    public function scopeStatus(Builder $q): Builder
    {
        return $q->where('category', 'status');
    }

    public function scopeAktivitas(Builder $q): Builder
    {
        return $q->where('category', 'aktivitas');
    }

    // ──────────────────────────────────────────────
    //  Accessors
    // ──────────────────────────────────────────────

    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Bootstrap-icon class sesuai device_type
     */
    public function getIconClassAttribute(): string
    {
        return match ($this->device_type) {
            'rectifier' => 'bi bi-lightning-charge-fill',
            'kwh'       => 'bi bi-plug-fill',
            'battery'   => 'bi bi-battery-charging',
            'ac'        => 'bi bi-snow',
            'genset'    => 'bi bi-gear-fill',
            'pop'       => 'bi bi-building',
            default     => 'bi bi-bell-fill',
        };
    }

    /**
     * CSS background-color class untuk icon box sesuai severity
     */
    public function getIconBgClassAttribute(): string
    {
        return match ($this->severity) {
            'alert'      => 'bg-icon-red',
            'warning'    => 'bg-icon-orange',
            'belum_uji', 'belum_pm'    => 'bg-icon-blue',
            'jadwal_uji', 'jadwal_pm'  => 'bg-icon-purple',
            'create'     => 'bg-icon-green',
            'update'     => 'bg-icon-blue',
            'delete'     => 'bg-icon-red',
            default      => 'bg-icon-gray',
        };
    }

    /**
     * Badge class CSS sesuai severity
     */
    public function getBadgeClassAttribute(): string
    {
        return match ($this->severity) {
            'alert'      => 'badge-alert',
            'warning'    => 'badge-warning',
            'belum_uji'  => 'badge-belum-uji',
            'jadwal_uji' => 'badge-jadwal-uji',
            'belum_pm'   => 'badge-belum-uji',
            'jadwal_pm'  => 'badge-jadwal-uji',
            default      => '',
        };
    }

    /**
     * Label badge untuk status
     */
    public function getBadgeLabelAttribute(): string
    {
        return match ($this->severity) {
            'alert'      => 'ALERT',
            'warning'    => 'WARNING',
            'belum_uji'  => 'BELUM UJI',
            'jadwal_uji' => 'JADWAL UJI',
            'belum_pm'   => 'BELUM PM',
            'jadwal_pm'  => 'JADWAL PM',
            default      => '',
        };
    }

    // ──────────────────────────────────────────────
    //  Static helper: generate status notifications from live data
    // ──────────────────────────────────────────────

    /**
     * Regenerate semua notifikasi status dari data terkini.
     * Dipanggil oleh Observer saat ada perubahan data perangkat.
     */
    public static function regenerateStatus(): void
    {
        $now = now();
        $activeItems = collect();

        // ── RECTIFIER: utilisasi warning / alert ──
        \App\Models\Rectifier::with('pop')
            ->whereNotNull('utilisasi')
            ->where('utilisasi', '>', 50)
            ->get()
            ->each(function ($r) use (&$activeItems) {
                $severity = $r->utilisasi > 70 ? 'alert' : 'warning';
                $activeItems->push([
                    'device_type' => 'rectifier',
                    'device_id'   => $r->id,
                    'pop_id'      => $r->pop_id,
                    'pop_kode'    => $r->pop?->kode_pop,
                    'device_label'=> $r->nomor_recti ?? 'Rectifier #' . $r->id,
                    'severity'    => $severity,
                    'title'       => 'Rectifier - ' . ($r->pop?->kode_pop ?? 'N/A'),
                    'message'     => 'Utilisasi ' . number_format($r->utilisasi, 1) . '% — ' . ($severity === 'alert' ? 'di atas batas kritis' : 'melebihi ambang normal'),
                ]);
            });

        // ── BATTERY: performa warning/alert ──
        \App\Models\Battery::with('pop')
            ->whereNotNull('performa_baterai')
            ->whereIn('performa_baterai', ['3-WARNING', '4-ALERT'])
            ->get()
            ->each(function ($b) use (&$activeItems) {
                $severity = $b->performa_baterai === '4-ALERT' ? 'alert' : 'warning';
                $activeItems->push([
                    'device_type' => 'battery',
                    'device_id'   => $b->id,
                    'pop_id'      => $b->pop_id,
                    'pop_kode'    => $b->pop?->kode_pop,
                    'device_label'=> 'Bank ' . ($b->nomor_bank ?? $b->id),
                    'severity'    => $severity,
                    'title'       => 'Battery - ' . ($b->pop?->kode_pop ?? 'N/A'),
                    'message'     => 'Performa baterai: ' . str_replace(['1-','2-','3-','4-'], '', $b->performa_baterai),
                ]);
            });

        // ── BATTERY: status uji belum uji / jadwal uji ──
        \App\Models\Battery::with('pop')->get()
            ->filter(fn($b) => in_array($b->status_uji_live, ['BLM UJI BATT', 'JADWAL UJI BATT']))
            ->each(function ($b) use (&$activeItems) {
                $severity = $b->status_uji_live === 'BLM UJI BATT' ? 'belum_uji' : 'jadwal_uji';
                $activeItems->push([
                    'device_type' => 'battery',
                    'device_id'   => $b->id,
                    'pop_id'      => $b->pop_id,
                    'pop_kode'    => $b->pop?->kode_pop,
                    'device_label'=> 'Bank ' . ($b->nomor_bank ?? $b->id),
                    'severity'    => $severity,
                    'title'       => 'Battery - ' . ($b->pop?->kode_pop ?? 'N/A'),
                    'message'     => $b->status_uji_label . ($b->tanggal_uji_terakhir ? ' · Uji terakhir: ' . $b->tanggal_uji_terakhir->format('d M Y') : ''),
                ]);
            });

        // ── AC: belum PM / jadwal PM ──
        \App\Models\Ac::with('pop')->get()
            ->filter(fn($ac) => in_array($ac->status_pm['status'], ['Belum Preventive Maintenance', 'Jadwal Preventive Maintenance']))
            ->each(function ($ac) use (&$activeItems) {
                $severity = $ac->status_pm['status'] === 'Belum Preventive Maintenance' ? 'belum_pm' : 'jadwal_pm';
                $activeItems->push([
                    'device_type' => 'ac',
                    'device_id'   => $ac->id,
                    'pop_id'      => $ac->pop_id,
                    'pop_kode'    => $ac->pop?->kode_pop,
                    'device_label'=> 'AC-' . ($ac->nomor_ac ?? $ac->id),
                    'severity'    => $severity,
                    'title'       => 'AC - ' . ($ac->pop?->kode_pop ?? 'N/A'),
                    'message'     => $ac->status_pm['status'] . ($ac->status_pm['text'] !== '-' ? ' ' . $ac->status_pm['text'] : ''),
                ]);
            });

        // ── GENSET: belum PM / jadwal PM ──
        \App\Models\Genset::with('pop')->get()
            ->filter(fn($g) => in_array($g->status_pm['status'], ['Belum Preventive Maintenance', 'Jadwal Preventive Maintenance']))
            ->each(function ($g) use (&$activeItems) {
                $severity = $g->status_pm['status'] === 'Belum Preventive Maintenance' ? 'belum_pm' : 'jadwal_pm';
                $activeItems->push([
                    'device_type' => 'genset',
                    'device_id'   => $g->id,
                    'pop_id'      => $g->pop_id,
                    'pop_kode'    => $g->pop?->kode_pop,
                    'device_label'=> 'Genset-' . ($g->nomor_genset ?? $g->id),
                    'severity'    => $severity,
                    'title'       => 'Genset - ' . ($g->pop?->kode_pop ?? 'N/A'),
                    'message'     => $g->status_pm['status'] . ($g->status_pm['text'] !== '-' ? ' ' . $g->status_pm['text'] : ''),
                ]);
            });

        $existingNotifs = static::where('category', 'status')->get();
        $toKeepIds = [];

        foreach ($activeItems as $item) {
            $matched = $existingNotifs->first(function ($n) use ($item) {
                return $n->device_type === $item['device_type']
                    && $n->device_id == $item['device_id']
                    && $n->severity === $item['severity'];
            });

            if ($matched) {
                $toKeepIds[] = $matched->id;
                if ($matched->message !== $item['message']) {
                    $matched->update(['message' => $item['message']]);
                }
            } else {
                $newNotif = static::create(array_merge($item, [
                    'category'   => 'status',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
                $toKeepIds[] = $newNotif->id;
            }
        }

        static::where('category', 'status')
            ->whereNotIn('id', $toKeepIds)
            ->delete();
    }

    /**
     * Tambah notifikasi aktivitas (CRUD)
     */
    public static function logActivity(
        string $deviceType,
        int    $deviceId,
        string $title,
        string $message,
        ?int   $popId,
        ?string $popKode,
        ?string $deviceLabel,
        string $action,
        int    $actorId,
        string $actorName,
    ): void {
        static::create([
            'category'     => 'aktivitas',
            'device_type'  => $deviceType,
            'device_id'    => $deviceId,
            'pop_id'       => $popId,
            'pop_kode'     => $popKode,
            'device_label' => $deviceLabel,
            'severity'     => $action, // create/update/delete
            'title'        => $title,
            'message'      => $message,
            'actor_id'     => $actorId,
            'actor_name'   => $actorName,
            'action'       => $action,
            'read_at'      => null,
        ]);
    }
}
