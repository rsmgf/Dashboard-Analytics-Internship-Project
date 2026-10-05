<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeRole = session('active_role')
            ?? ($user?->hasRole('manajer') ? 'manajer' : 'super_admin');

        if (!in_array($activeRole, ['manajer', 'super_admin'], true)) {
            abort(403, 'Akses ditolak. Fitur notifikasi hanya untuk manajer dan super admin.');
        }

        // Generate status terbaru sebelum menampilkan (bisa dipindah ke scheduler nantinya)
        Notification::regenerateStatus();

        $notificationsStatus = Notification::status()->orderBy('created_at', 'desc')->get();
        $notificationsAktivitas = Notification::aktivitas()->orderBy('created_at', 'desc')->get();

        return view('notifikasi', compact('notificationsStatus', 'notificationsAktivitas'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
