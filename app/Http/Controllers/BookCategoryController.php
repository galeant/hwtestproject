<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookCategory;
use App\Http\Response\BookCategoryTransformer;
use App\Http\Requests\CreateBookCategoryRequest;
use Illuminate\Support\Str;
use DB;

class BookCategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $order_by = $request->input('order_by', ['id']);
            $sort = $request->input('sort', ['asc']);

            $search_by = $request->search_by;
            $keyword = $request->keyword;

            $per_page = $request->input('per_page', 10);
            $data = BookCategory::order($order_by, $sort)
                ->search($search_by, $keyword)
                ->paginate($per_page);

            return BookCategoryTransformer::getList($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(CreateBookCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = BookCategory::create([
                'slug' => Str::slug($request->name),
                'name' => $request->name,
            ]);
            DB::commit();
            return BookCategoryTransformer::getDetail($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }


    public function detail(Request $request, $id)
    {
        try {
            $data = BookCategory::where('id', $id)->firstOrFail();
            return BookCategoryTransformer::getDetail($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(CreateBookCategoryRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = BookCategory::where('id', $id)->firstOrFail();
            $data->update([
                'slug' => Str::slug($request->name),
                'name' => $request->name,
            ]);

            DB::commit();
            return BookCategoryTransformer::getDetail($data->fresh());
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = BookCategory::where('id', $id)->firstOrFail();
            $data->delete();

            DB::commit();
            return BookCategoryTransformer::getDetail($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
