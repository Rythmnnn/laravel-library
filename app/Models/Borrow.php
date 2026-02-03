<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Borrow extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'qty',
        'start_borrow',
        'end_borrow',
        'return_borrow',
        'fine',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function book(): HasOne
    {
        return $this->hasOne(Book::class);
    }
}
