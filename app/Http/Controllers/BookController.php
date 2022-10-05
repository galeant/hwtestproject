<?php

namespace App\Http\Controllers;

use App\Http\Response\BookTransformer;
use Illuminate\Http\Request;
use DB;
use App\Models\Book;
use App\Http\Requests\CreateBookRequest;

class BookController extends Controller
{
    public function index(Request $request)
    {
        try {
            $order_by = $request->input('order_by', ['id']);
            $sort = $request->input('sort', ['asc']);

            $search_by = $request->search_by;
            $keyword = $request->keyword;

            $per_page = $request->input('per_page', 10);
            $data = Book::order($order_by, $sort)
                ->search($search_by, $keyword)
                ->paginate($per_page);

            return BookTransformer::getList($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(CreateBookRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = Book::create([
                'serial_code' => $request->serial_code,
                'title' => $request->title,
                'creator' => $request->creator,
                'category_id' => $request->category_id,
            ]);

            DB::commit();
            return BookTransformer::getDetail($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function detail(Request $request, $id)
    {
        try {
            $data = Book::where('id', $id)->firstOrFail();
            return BookTransformer::getDetail($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = Book::where('id', $id)->firstOrFail();
            $data->update([
                'serial_code' => $request->serial_code,
                'title' => $request->title,
                'creator' => $request->creator,
                'category_id' => $request->category_id,
            ]);
            DB::commit();
            return BookTransformer::getDetail($data->fresh());
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = Book::where('id', $id)->firstOrFail();
            $data->delete();
            DB::commit();
            return BookTransformer::getDetail($data);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }
}
