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
            ]);

            $borrowData = $this->borrow->where([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
            ])->first();

            if ($borrowData && ! isset($borrowData->return_borrow)) {

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
                'start_borrow' => $date->now(),
                'end_borrow' => $date->addDays(3),
                'fine' => 0,
            ]);

            $book = $this->book->find($request->book_id);
            $book->qty -= 1;
            $book->save();

            return response([
                'data' => $borrowData,
                'message' => 'borrow success!',
            ], 201);
        }

        return response([
            'message' => 'only admin access!',
        ], 401);

    }

    public function returnBorrow(Request $request, $id)
    {
        $user = $request->user()->load('role');

        if ($user->role[0]->role_name == 'admin') {
            $date = new Carbon;
            $borrowData = $this->borrow->findOrFail($id);

            $day1 = $date->parse($borrowData->end_borrow);
            $day2 = $date->parse($date->now());

            if (isset($borrowData->return_borrow)) {
                return response([
                    'data' => $borrowData,
                    'message' => 'data can\'t changed!',
                ], 422);
            }

            if ($day2 > $day1) {
                $dayAccumulate = $day1->diffInDays($day2);
                $borrowData->fine = floor($dayAccumulate) * 1000;
            }
            $borrowData->return_borrow = $date->now();
            $borrowData->save();
            $book = $this->book->find($borrowData->book_id);
            $book->qty += 1;
            $book->save();

            return response([
                'data' => $borrowData,
                'message' => 'return borrow success!',
            ], 200);
        }

        return response([
            'message' => 'only admin access!',
        ], 401);
    }
}
