<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // GET /api/products
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(12);

        // បន្ថែម full image URL
        $products->getCollection()->transform(function ($product) {
            $product->image_url = $product->image
                ? Storage::url($product->image)
                : null;
            return $product;
        });

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = Product::with(['category', 'reviews.user'])
            ->findOrFail($id);

        $product->image_url = $product->image
            ? Storage::url($product->image)
            : null;

        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }

    // GET /api/products/search?keyword=xxx&category_id=1&min_price=10&max_price=100
    public function search(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->get();

        $products->transform(function ($product) {
            $product->image_url = $product->image
                ? Storage::url($product->image)
                : null;
            return $product;
        });

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }
}