<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WishlistController extends Controller
{
    // GET /api/wishlist
    public function index(Request $request)
    {
        $wishlist = Wishlist::with('product.category')
            ->where('user_id', $request->user()->id)
            ->get();

        $wishlist->transform(function ($item) {
            if ($item->product) {
                $item->product->image_url = $item->product->image
                    ? Storage::url($item->product->image)
                    : null;
            }
            return $item;
        });

        return response()->json([
            'success' => true,
            'data'    => $wishlist,
        ]);
    }

    // POST /api/wishlist
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Check already in wishlist
        $exists = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product នេះមានក្នុង Wishlist ស្រាប់ហើយ!',
            ], 400);
        }

        $wishlist = Wishlist::create([
            'user_id'    => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'បានបន្ថែមទៅ Wishlist!',
            'data'    => $wishlist,
        ], 201);
    }

    // DELETE /api/wishlist/{id}
    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'បានដកចេញពី Wishlist!',
        ]);
    }
}