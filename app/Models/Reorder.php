<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Reorder extends Model
{
    protected $fillable = [
        'original_custom_order_id',
        'agent_id',
        'school_id',
        'design_file',
        'delivery_option',
        'delivery_address',
        'delivery_fee',
        'notes',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(ReorderItem::class);
    }

    public function originalOrder()
    {
        return $this->belongsTo(CustomOrder::class, 'original_custom_order_id');
    }

    public function agent()
    {
        return $this->belongsTo(SchoolAgent::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'reorder_id', 'id')->where('order_type', 'reorder');
    }

        public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }



}
