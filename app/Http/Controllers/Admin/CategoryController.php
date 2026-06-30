<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // មើល Categories ទាំងអស់
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    // បង្ហាញ Form បង្កើតថ្មី
    public function create()
    {
        return view('admin.categories.create');
    }

    // រក្សាទុក Category ថ្មី
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:categories',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name'  => $request->name,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category បានបង្កើតដោយជោគជ័យ!');
    }

    // បង្ហាញ Form កែ
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // រក្សាទុក Category ដែលបានកែ
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            // លុប image ចាស់
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name'  => $request->name,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category បានកែដោយជោគជ័យ!');
    }

    // លុប Category
    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->destroy($category->id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category បានលុបដោយជោគជ័យ!');
    }
}