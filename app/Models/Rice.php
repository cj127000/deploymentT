<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rice extends Model
{
    protected $table = 'rice';
    protected $fillable = ['name', 'price_per_kg', 'stock_qty'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}