<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // POST /api/checkout
    public function checkout(Request $request)
    {
        $userId    = $request->user()->id;
        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        // Check cart empty
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart ទទេ! សូមបន្ថែម Product ជាមុន.',
            ], 400);
        }

        // Check stock
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Product '{$item->product->name}' មិនគ្រប់ stock!",
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Create Order
            $order = Order::create([
                'user_id'      => $userId,
                'total_amount' => $total,
                'status'       => 'pending',
            ]);

            // Create Order Items + reduce stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);

                // Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear Cart
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            // ===== បន្ថែម Admin Notification =====
            $notifications = Cache::get('admin_notifications', []);
            $notifications[] = [
                'id'         => $order->id,
                'message'    => '🛍️ New Order #' . $order->id . ' from ' . $request->user()->name,
                'amount'     => $order->total_amount,
                'user'       => $request->user()->name,
                'user_id'    => $userId,
                'email'      => $request->user()->email,
                'created_at' => now()->toDateTimeString(),
                'read'       => false,
            ];
            Cache::put('admin_notifications', $notifications, 86400);
            // ===== End Notification =====

            return response()->json([
                'success' => true,
                'message' => 'Order បានបង្កើតដោយជោគជ័យ!',
                'data'    => $order->load('orderItems.product'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed!',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // GET /api/orders
    public function index(Request $request)
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    // GET /api/orders/{id}
    public function show(Request $request, $id)
    {
        $order = Order::with('orderItems.product')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        $order->orderItems->transform(function ($item) {
            if ($item->product) {
                $item->product->image_url = $item->product->image
                    ? Storage::url($item->product->image)
                    : null;
            }
            return $item;
        });

        return response()->json([
            'success' => true,
            'data'    => $order,
        ]);
    }
}