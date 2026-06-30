<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // GET /api/products/{id}/reviews
    public function index($id)
    {
        $reviews = Review::with('user')
            ->where('product_id', $id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }

    // POST /api/reviews
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        // Check already reviewed
        $exists = Review::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'អ្នកបាន Review Product នេះស្រាប់ហើយ!',
            ], 400);
        }

        $review = Review::create([
            'user_id'    => $request->user()->id,
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review បានបន្ថែមដោយជោគជ័យ!',
            'data'    => $review->load('user'),
        ], 201);
    }
}