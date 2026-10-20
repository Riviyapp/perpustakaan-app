<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        $request->merge(['slug' => $slug]);
        $request->validate(['slug' => 'unique:categories,slug']);

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return response()->json($category, 201);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}