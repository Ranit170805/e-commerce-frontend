<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');
        $this->markOrderNotificationAsRead($order->id);
        return view('admin.orders.show', compact('order'));
    }

    // Update Status
    public function updateStatus(Order $order, $status)
    {
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Invalid status!');
        }

        $order->update(['status' => $status]);

        $this->markOrderNotificationAsRead($order->id);

        return back()->with('success', "Order #{$order->id} updated to {$status}!");
    }

    // View orders for specific user
    public function userOrders(User $user)
    {
        $orders = Order::where('user_id', $user->id)
                       ->with('orderItems.product')
                       ->latest()
                       ->paginate(10);
        $this->markUserNotificationsAsRead($user->id);
        return view('admin.orders.index', compact('orders', 'user'));
    }

    private function markOrderNotificationAsRead($orderId)
    {
        $notifications = Cache::get('admin_notifications', []);
        $updated = false;
        foreach ($notifications as &$n) {
            if (isset($n['id']) && $n['id'] == $orderId && !$n['read']) {
                $n['read'] = true;
                $updated = true;
            }
        }
        if ($updated) {
            Cache::put('admin_notifications', $notifications, 86400);
        }
    }

    private function markUserNotificationsAsRead($userId)
    {
        $notifications = Cache::get('admin_notifications', []);
        $updated = false;
        foreach ($notifications as &$n) {
            if (isset($n['user_id']) && $n['user_id'] == $userId && !$n['read']) {
                $n['read'] = true;
                $updated = true;
            }
        }
        if ($updated) {
            Cache::put('admin_notifications', $notifications, 86400);
        }
    }
}
