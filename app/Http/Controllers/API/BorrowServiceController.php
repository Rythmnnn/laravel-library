<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowServiceController extends Controller
{
    private $book;

    private $borrow;

    private $user;

    public function __construct(
        Book $book,
        User $user,
        Borrow $borrow
    ) {
        $this->book = $book;
        $this->user = $user;
        $this->borrow = $borrow;
    }

    public function index(Request $request)
    {
        $user = $request->user()->load('role');

        if ($user->role[0]->role_name == 'admin') {
            $borrows = $this->borrow->with('user', 'book')->get();

            return response([
                'data' => $borrows,
                'message' => 'data found!',
            ], 200);
        }

        return response([
            'message' => 'only admin access!',
        ], 401);

    }

    public function store(Request $request)
    {
        $user = $request->user()->load('role');

        if ($user->role[0]->role_name == 'admin') {

            $request->validate([
                'book_id' => 'required|exists:books,id',
                'user_id' => 'required|exists:users,id',
                'qty' => 'required|digits_between:1,2',
                'user_id' => 'required|exists:users,id',
            ]);

            $borrowData = $this->borrow->where([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
            ])->first();

            if ($borrowData) {
                // nanti disini logic kalo ada datanya
                return response([
                    'data' => $borrowData,
                    'message' => 'book has not  return!',
                ], 422);
            }

            $date = new Carbon;

            $this->borrow->create([
                'book_id' => $request->book_id,
                'user_id' => $request->user_id,
                'qty' => 1,
                'user_id' => $request->user_id,
                'start_borrow' => $date->now(),
                'end_borrow' => $date->addDays(3),
                'fine' => 0,
            ]);

            return response([
                'data' => $borrowData,
                'message' => 'borrow success!',
            ], 401);
        }

        return response([
            'message' => 'only admin access!',
        ], 401);

    }
}
