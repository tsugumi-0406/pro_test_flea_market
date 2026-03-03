<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'buyer_assessment', 'seller_assessment'];

    public function order() {
        return $this->hasOne(Order::class);
    }
}
