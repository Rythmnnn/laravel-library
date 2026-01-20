<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'author',
        'year',
        'qty',
        'title',
        'cover',
        'filename',
    ];

    public function search($param)
    {
        return $this->join('categories', function ($join) {
            return $join->on('books.category_id', '=', 'categories.id');
        })
            ->where('title', 'like', "%$param%")
            ->orWhere('categories.category_name', 'like', "%$param%")
            ->select(
                'books.id',
                'categories.category_name',
                'books.cover',
                'books.title',
                'books.author',
                'books.qty',
                'books.year'
            )->get();
    }

    public function withCategory()
    {
        return $this->join('categories', function ($join) {
            return $join->on('books.category_id', '=', 'categories.id');
        })->select(
            'books.id',
            'categories.category_name',
            'books.cover',
            'books.title',
            'books.author',
            'books.qty',
            'books.year'
        )->get();
    }
}
