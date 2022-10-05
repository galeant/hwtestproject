<?php

namespace App\Http\Response;

class BookTransformer
{
    public static function getList($data, $message = 'Success')
    {
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $items = collect($data->items())->transform(function ($v) {
                return self::reform($v);
            });
            $return = [
                'data' => $items,
                'current_page' => $data->currentPage(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
                'total' => $data->total(),
                'total_page' => $data->lastPage(),
                'per_page' => $data->perPage()
            ];
        } else {

            $return = [
                'data' => $data->transform(function ($v) {
                    return self::reform($v);
                }),
                'total' => count($data)
            ];
        }
        return response()->json([
            'message' => $message,
            'result' => $return
        ]);
    }

    public static function getDetail($data, $message = 'Success')
    {

        return response()->json([
            'message' => $message,
            'result' => self::reform($data)
        ]);
    }

    private static function reform($data)
    {
        return [
            'id' => $data->id,
            'serial_code' => $data->serial_code,
            'title' => $data->title,
            'qty' => $data->qty,
            'category' => [
                'id' => $data->category->id,
                'name' => $data->category->slug,
                'slug' => $data->category->name,

            ]
        ];
    }
}
