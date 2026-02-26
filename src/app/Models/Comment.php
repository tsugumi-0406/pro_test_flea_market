<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['sentence', 'item_id', 'account_id'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
