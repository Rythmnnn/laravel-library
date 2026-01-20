<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $data = $this->book->search($request->search);

            return response([
                'message' => count($data) > 0 ? 'list book found!' : 'list book not found',
                'data' => $data,
            ], count($data) > 0 ? 200 : 404);

        }

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
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:1024',
        ]);

        $filename = '';

        if ($request->file('cover')) {
            $filename = Carbon::now()->format('YmdHis').'.'.$request->file('cover')->extension();
            $request->file('cover')->storeAs('upload', $filename, 'public');

            // $request->file('cover')->move(public_path('upload/'), $filename);
        }

        $this->book->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'author' => $request->author,
            'qty' => $request->qty,
            'year' => $request->year,
            'cover' => $request->file('cover') ? url('storage/upload/'.$filename) : null,
            'filename' => $filename,
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
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|min:4|max:255',
            'author' => 'required|string|min:4|max:255',
            'qty' => 'required|integer',
            'year' => 'required|digits:4',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:1024',
        ]);

        $filename = '';

        $detail = $this->book->findOrFail($id);

        if ($request->file('cover')) {

            Storage::disk('upload')->delete($detail->filename);

            $filename = Carbon::now()->format('YmdHis').'.'.$request->file('cover')->extension();
            $request->file('cover')->storeAs('upload', $filename, 'public');

            // $request->file('cover')->move(public_path('upload/'), $filename);
        }

        $detail->category_id = $request->category_id;
        $detail->title = $request->title;
        $detail->author = $request->author;
        $detail->qty = $request->qty;
        $detail->year = $request->year;

        if ($request->file('cover')) {
            $detail->cover = url('storage/upload/'.$filename);
            $detail->filename = $filename;
        }

        $detail->save();

        return response([
            'message' => 'book has been updated!',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
