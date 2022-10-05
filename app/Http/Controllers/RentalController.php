<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;

use DB;
use App\Http\Response\RentalTransformer;

class RentalController extends Controller
{
    public function list(Request $request)
    {
        try {
            $type = $request->input('type');
            $user = auth()->user();
            $data = Book::with('user')->wherehas('user', function ($q) use ($user) {
                $q->where('id', $user->id);
            })->get();

            return RentalTransformer::getList($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $user = User::where('id', $user->id)->first();;
            $user->book()->attach($request->book_id);
            $data = Book::wherehas('user', function ($q) use ($user) {
                $q->where('id', $user->id);
            })->get();
            DB::commit();

            return RentalTransformer::getList($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function return(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            DB::table('rental')->where([
                'user_id' => $user->id,
                'book_id' => $request->book_id
            ])->update([
                'is_return' => true
            ]);;
            $data = Book::with('user')->wherehas('user', function ($q) use ($user) {
                $q->where('id', $user->id);
            })->get();
            DB::commit();

            return RentalTransformer::getList($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
