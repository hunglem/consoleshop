<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(order_detail::class);
    }
    public function transaction()
    {
        return $this->hasOne(transaction::class);
    }
}
