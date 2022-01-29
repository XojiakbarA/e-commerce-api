<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function orderProducts()
    {
        return $this->hasManyThrough(OrderProduct::class, ShopOrder::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function shopOrders()
    {
        return $this->hasMany(ShopOrder::class);
    }
}
