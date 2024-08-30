<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function orderItem(){
        return $this->hasMany(OrderItem::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
    
    public function installments()
    {
        return $this->hasMany(Installment::class, 'installment_id');
    }
}
