<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'account_id', 'method', 'post_code', 'address', 'building'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
