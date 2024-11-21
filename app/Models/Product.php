<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'datetime',
    ];
    
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price;
    }
}
