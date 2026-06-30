<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    // GET /api/cart
    public function index(Request $request)
    {
        $cart = Cart::with('product.category')
            ->where('user_id', $request->user()->id)
            ->get();

        $cart->transform(function ($item) {
            if ($item->product) {
                $item->product->image_url = $item->product->image
                    ? Storage::url($item->product->image)
                    : null;
                $item->subtotal = $item->product->price * $item->quantity;
            }
            return $item;
        });

        $total = $cart->sum('subtotal');

        return response()->json([
            'success' => true,
            'data'    => $cart,
            'total'   => $total,
        ]);
    }

    // POST /api/cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = Cart::updateOrCreate(
            [
                'user_id'    => $request->user()->id,
                'product_id' => $request->product_id,
            ],
            ['quantity' => $request->quantity]
        );

        return response()->json([
            'success' => true,
            'message' => 'បានបន្ថែមទៅ Cart!',
            'data'    => $cart,
        ], 201);
    }

    // PUT /api/cart/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Cart បានកែដោយជោគជ័យ!',
            'data'    => $cart,
        ]);
    }

    // DELETE /api/cart/{id}
    public function destroy(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'បានដកចេញពី Cart!',
        ]);
    }
}