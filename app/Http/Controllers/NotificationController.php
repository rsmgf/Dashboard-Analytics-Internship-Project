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

        $notificationsStatus = Notification::status()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
        $activities = Notification::aktivitas()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15, ['*'], 'activity_page', 1);
        $activityItems = $activities->getCollection()->map(fn (Notification $notification) => $this->activityPayload($notification));

        return view('notifikasi', [
            'notificationsStatus' => $notificationsStatus,
            'activityItems' => $activityItems,
            'activityHasMore' => $activities->hasMorePages(),
        ]);
    }

    public function activities(Request $request)
    {
        $user = Auth::user();
        $activeRole = session('active_role')
            ?? ($user?->hasRole('manajer') ? 'manajer' : 'super_admin');

        if (!in_array($activeRole, ['manajer', 'super_admin'], true)) {
            abort(403, 'Akses ditolak. Fitur notifikasi hanya untuk manajer dan super admin.');
        }

        $page = max(2, $request->integer('page', 2));
        $activities = Notification::aktivitas()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15, ['*'], 'activity_page', $page);

        return response()->json([
            'items' => $activities->getCollection()->map(fn (Notification $notification) => $this->activityPayload($notification)),
            'has_more' => $activities->hasMorePages(),
            'next_page' => $activities->hasMorePages() ? $page + 1 : null,
        ]);
    }

    private function activityPayload(Notification $notification): array
    {
        return [
            'id' => $notification->id,
            'is_read' => $notification->is_read,
            'icon_class' => $notification->icon_class,
            'icon_bg_class' => $notification->icon_bg_class,
            'title' => $notification->title,
            'message_html' => $notification->safe_message_html,
            'created_at' => $notification->created_at?->format('d M Y H:i'),
            'category' => $notification->category,
            'pop_id' => $notification->pop_id,
            'device_type' => $notification->device_type,
            'device_id' => $notification->device_id,
            'action' => $notification->action,
        ];
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
