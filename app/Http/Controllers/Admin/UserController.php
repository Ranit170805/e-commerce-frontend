<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    // មើល Users ទាំងអស់
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // មើល User តែមួយ (Detail)
    public function show(User $user)
    {
        $user->load('orders');
        $this->markUserNotificationsAsRead($user->id);
        return view('admin.users.show', compact('user'));
    }

    // Confirm/Verify user
    public function verify(User $user)
    {
        $user->update(['email_verified_at' => now()]);

        // Mark existing notifications as read
        $this->markUserNotificationsAsRead($user->id);

        // Add notification
        $notifications = Cache::get('admin_notifications', []);
        $notifications[] = [
            'id' => uniqid(),
            'message' => "User {$user->name} has been verified",
            'user' => $user->name,
            'user_id' => $user->id,
            'amount' => 0,
            'read' => false,
            'created_at' => now()->toDateTimeString()
        ];
        Cache::put('admin_notifications', $notifications, 86400);

        return back()->with('success', 'User verified successfully!');
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
