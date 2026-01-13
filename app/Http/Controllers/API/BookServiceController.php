<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookServiceController extends Controller
{
    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'message' => 'list book found!',
            'data' => $this->book->withCategory(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|min:4|max:255',
            'author' => 'required|string|min:4|max:255',
            'qty' => 'required|integer',
            'year' => 'required|digits:4',
        ]);

        $this->book->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'author' => $request->author,
            'qty' => $request->qty,
            'year' => $request->year,
        ]);

        return response([
            'message' => 'book has been created!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->book->withCategory()->find($id);

        if (! isset($data)) {
            return response([
                'message' => 'list book not found!',
                'data' => $data,
            ], 404);
        }

        return response([
            'message' => 'book found!',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
