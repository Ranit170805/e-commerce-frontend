<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    // GET /admin/notifications
    public function index()
    {
        $notifications = Cache::get('admin_notifications', []);
        $notifications = array_values(array_filter(
            $notifications,
            fn($notification) => empty($notification['read'])
        ));
        $notifications = array_reverse($notifications);

        return response()->json($notifications);
    }

    // POST /admin/notifications/read
    public function markAllRead()
    {
        $notifications = Cache::get('admin_notifications', []);
        foreach ($notifications as &$n) {
            $n['read'] = true;
        }
        Cache::put('admin_notifications', $notifications, 86400);
        return response()->json(['success' => true]);
    }

    // POST /admin/notifications/{order}/read
    public function markOrderRead(Order $order)
    {
        $notifications = Cache::get('admin_notifications', []);
        $updated = false;

        foreach ($notifications as &$n) {
            if (isset($n['id']) && (int) $n['id'] === (int) $order->id && empty($n['read'])) {
                $n['read'] = true;
                $updated = true;
            }
        }

        if ($updated) {
            Cache::put('admin_notifications', $notifications, 86400);
        }

        return response()->json(['success' => true]);
    }

    // GET /admin/notifications/count
    public function unreadCount()
    {
        $notifications = Cache::get('admin_notifications', []);
        $count = count(array_filter($notifications, fn($n) => empty($n['read'])));
        return response()->json(['count' => $count]);
    }
}
