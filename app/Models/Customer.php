<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'user_id',
        'name',
        'cpf',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function order(){
        return $this->hasMany(Order::class);
    }
}
