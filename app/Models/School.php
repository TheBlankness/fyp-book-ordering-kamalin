<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    public function customOrders()
    {
        return $this->hasMany(\App\Models\CustomOrder::class, 'school_id');
    }

    protected $fillable = ['name'];
}
