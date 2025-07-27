<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\CustomOrder;
use App\Models\Reorder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_order_id',
        'reorder_id',            
        'order_type',
        'agent_id',
        'status',
        'order_number',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class, 'custom_order_id');
    }

    public function reorder()
    {
        return $this->belongsTo(Reorder::class, 'reorder_id'); // ✅ Add this
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'order_id');
    }

    public function canEdit()
    {
        return $this->status === 'submitted' && now()->diffInDays($this->submitted_at) <= 7;
    }
}
