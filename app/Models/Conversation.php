<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'designer_id',
        'order_id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function agent()
    {
        return $this->belongsTo(SchoolAgent::class, 'agent_id');
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id'); // assumes designers are in 'users' table
    }

    public function order()
    {
        return $this->belongsTo(CustomOrder::class, 'order_id');
    }
}
