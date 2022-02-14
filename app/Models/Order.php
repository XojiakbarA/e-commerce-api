<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = [];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function orderProducts()
    {
        return $this->hasManyThrough(OrderProduct::class, SubOrder::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }
}
