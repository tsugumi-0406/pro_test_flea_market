<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'account_id', 'method', 'post_code', 'address', 'status', 'buyer_last_read_at', 'seller_last_read_at', 'last_message_at'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
