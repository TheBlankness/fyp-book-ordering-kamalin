<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'school_id',
        'design_template',
        'school_logo_path',
        'notes',
        'submitted_at',
        'status',
        'delivery_option',
        'delivery_address',
        'delivery_fee',
        'total_amount',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(CustomOrderItem::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function agent()
    {
        return $this->belongsTo(SchoolAgent::class, 'agent_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'custom_order_id');
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'custom_order_items', 'custom_order_id', 'book_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'custom_order_id', 'id')->where('order_type', 'custom');
    }



    public function conversation()
    {
        return $this->hasOne(\App\Models\Conversation::class, 'order_id', 'id');
    }

    public function canEdit()
    {
        return $this->status === 'submitted' && $this->submitted_at && now()->diffInDays($this->submitted_at) <= 7;
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }


}
