<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class DashboardCacheObserver
{
    public function created($model)
    {
        $this->logActivity($model, 'create', 'menambahkan');
        $this->clearCache();
    }

    public function updated($model)
    {
        $this->logActivity($model, 'update', 'mengubah detail');
        $this->clearCache();
    }

    public function deleted($model)
    {
        $this->logActivity($model, 'delete', 'menghapus');
        $this->clearCache();
    }

    protected function logActivity($model, $action, $actionLabel)
    {
        $user = auth()->user();
        if (!$user) return;

        $type = strtolower(class_basename($model));
        
        if ($type === 'pop') {
            $popKode = $model->kode_pop ?? 'N/A';
            $popId = $model->id;
            $label = 'POP ' . $popKode;
        } else {
            $pop = $model->pop;
            $popKode = $pop ? $pop->kode_pop : 'N/A';
            $popId = $model->pop_id ?? null;
            $label = $type;

            // Try to find specific name or identifier for the device
            if (isset($model->nomor_recti)) $label = 'Rectifier ' . $model->nomor_recti;
            if (isset($model->nomor_kwh)) $label = 'Kwh ' . $model->nomor_kwh;
            if (isset($model->nomor_bank)) $label = 'Battery Bank ' . $model->nomor_bank;
            if (isset($model->nomor_ac)) $label = 'AC ' . $model->nomor_ac;
            if (isset($model->nomor_genset)) $label = 'Genset ' . $model->nomor_genset;
        }

        $title = ucfirst($type) . ' - ' . $popKode;
        $message = "<strong>{$user->name}</strong> {$actionLabel} {$label}" . ($type !== 'pop' ? " pada POP {$popKode}" : "");

        \App\Models\Notification::logActivity(
            $type,
            $model->id ?? 0,
            $title,
            $message,
            $popId,
            $popKode,
            $label,
            $action,
            $user->id,
            $user->name
        );
    }

    protected function clearCache()
    {
        Cache::forget('dashboard.deviceStatus');
        Cache::forget('dashboard.kpi');
    }
}
