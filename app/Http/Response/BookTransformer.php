<?php

namespace App\Http\Response;

class BookTransformer
{
    public static function getList($data, $message = 'Success')
    {
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $items = collect($data->items())->transform(function ($v) use ($access) {
                return self::reform($v, 'index', $access);
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
                'data' => $data->transform(function ($v) use ($access) {
                    return self::reform($v, 'index', $access);
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
        self::getAuth();
        $access = self::$access;

        return response()->json([
            'message' => $message,
            'result' => self::reform($data, 'detail', $access)
        ]);
    }

    private static function reform($data)
    {
    }
}
