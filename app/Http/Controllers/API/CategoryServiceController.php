<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryServiceController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'message' => 'list category found!',
            'data' => $this->category->all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|min:3|max:100|unique:categories,category_name',
        ], [
            'category_name.required' => 'category harus diisi bos',
            'category_name.min' => 'category minimal 3 karakter',
            'category_name.unique' => 'category has already on database',
        ]);

        $this->category->create([
            'category_name' => $request->category_name,
        ]);

        return response([
            'message' => 'category has been created!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->category->find($id);

        if (! $data) {
            return response([
                'message' => 'category not found!',
                'data' => $data,
            ], 404);
        }

        return response([
            'message' => 'category found!',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_name' => 'required|string|min:3|max:100|unique:categories,category_name',
        ], [
            'category_name.required' => 'category harus diisi bos',
            'category_name.min' => 'category minimal 3 karakter',
            'category_name.unique' => 'category has already on database',
        ]);

        $category = $this->category->find($id);

        if (! $category) {
            return response([
                'message' => 'category not found!',
            ], 404);
        }

        $category->category_name = $request->category_name;
        $category->save();

        return response([
            'message' => 'category has been updated!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
