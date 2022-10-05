<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BookCategory;
use Illuminate\Support\Facades\Schema;

class Book extends Model
{
    use HasFactory;
    protected $table = 'book';
    protected $guarded = [];


    public function user()
    {
        return $this->belongsToMany(User::class, 'rental', 'book_id', 'user_id')->withPivot('is_return');
    }

    public function return()
    {
        return $this->belongsToMany(User::class, 'rental', 'book_id', 'user_id')->withPivot('is_return')->wherePivot('is_return', 1);
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id', 'id');
    }

    private function getField()
    {
        $table = $this->getTable();
        $columns = Schema::getColumnListing($table);
        return $columns;
    }

    public function scopeOrder($q, $order_by, $sort)
    {
        $column = $this->getField();
        if (is_array($order_by) && is_array($sort)) {
            $max = count($order_by);
            if (count($sort) > $max) {
                $max = count($sort);
            }
            for ($i = 0; $i < $max; $i++) {
                if (in_array($order_by[$i], $column) && isset($order_by[$i]) && isset($sort[$i])) {
                    $q->orderBy($order_by[$i], $sort[$i]);
                }
            }
        }
    }

    public function scopeSearch($q, $field, $keyword)
    {
        $q->where($field, $keyword);
        // $column = $this->getField();
        // if (is_array($field) && is_array($keyword)) {
        //     $max = count($field);
        //     if (count($keyword) > $max) {
        //         $max = count($keyword);
        //     }
        //     for ($i = 0; $i < $max; $i++) {
        //         if (in_array($field[$i], $column) && isset($field[$i]) && isset($keyword[$i])) {
        //             $q->where($field[$i], $keyword[$i]);
        //         }
        //     }
        // }
    }
}
