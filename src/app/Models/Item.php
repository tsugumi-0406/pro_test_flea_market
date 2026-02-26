<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'condition', 'name', 'brand', 'description', 'price', 'account_id'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function comments(){
            return $this->hasmany('App\Models\Comment');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function order()
    {
        return $this->hasone(Order::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        return $query;
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category', 'item_id', 'category_id');
    }
}
