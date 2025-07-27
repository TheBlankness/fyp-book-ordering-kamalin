<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'custom_order_id',
        'reorder_id',
        'order_type',
        'invoice_number',
        'issue_date',
        'total_amount',
        'status',
        'cheque_proof',
        'additional_charges',
    ];

    public function customOrder()
    {
        return $this->belongsTo(\App\Models\CustomOrder::class, 'custom_order_id');
    }

    public function reorder()
    {
        return $this->belongsTo(Reorder::class, 'reorder_id');
    }

    public function getTotalAmountAttribute($value)
    {
        return $value;
    }

    public function order()
    {
        if ($this->order_type === 'custom') {
            return $this->customOrder();
        } elseif ($this->order_type === 'reorder') {
            return $this->reorder();
        }
        return null;
    }

}
