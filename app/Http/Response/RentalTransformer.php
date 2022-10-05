<?php

namespace App\Http\Response;

class RentalTransformer
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
        // $user = $data->user[0];
        // dd($user->pivot);
        return [
            'id' => $data->id,
            'serial_code' => $data->serial_code,
            'title' => $data->title,
            'qty' => $data->qty,
            'is_return' => $data->user[0]->pivot->is_return
        ];
    }
}
