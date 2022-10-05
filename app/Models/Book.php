<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Book extends Model
{
    use HasFactory;
    protected $table = 'book';
    protected $guarded = [];


    public function user()
    {
        return $this->belongsToMany(User::class, 'rental', 'book_id', 'user_id')->withPivot('is_return');
    }

    public function category()
    {
        return $this->belongstTo(BookCategory::class, 'category_id', 'id');
    }
}
