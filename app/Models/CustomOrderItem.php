<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_order_id',
        'book_id',
        'title',
        'cover',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(CustomOrder::class, 'custom_order_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
