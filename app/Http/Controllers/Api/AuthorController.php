<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index()
    {
        return response()->json(Author::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:authors',
            'bio' => 'nullable|string',
        ]);

        $author = Author::create($validated);

        return response()->json($author, 201);
    }

    // Detail penulis + daftar bukunya
    public function show(Author $author)
    {
        return response()->json($author->load('books'));
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('authors')->ignore($author->id)],
            'bio' => 'nullable|string',
        ]);

        $author->update($validated);

        return response()->json($author);
    }

    // Buku milik penulis ikut terhapus (cascade di migration)
    public function destroy(Author $author)
    {
        $jumlahBuku = $author->books()->count();
        $author->delete();

        return response()->json([
            'message' => "Penulis berhasil dihapus beserta {$jumlahBuku} bukunya",
        ]);
    }
}