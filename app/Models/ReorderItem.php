<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReorderItem extends Model
{
    protected $fillable = [
        'reorder_id',
        'book_id',
        'title',
        'cover',
        'quantity',
        'price',
    ];

    public function reorder()
    {
        return $this->belongsTo(Reorder::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
