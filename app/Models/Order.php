<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['rice_id', 'quantity', 'total_amount'];

    public function rice() {
        return $this->belongsTo(Rice::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}