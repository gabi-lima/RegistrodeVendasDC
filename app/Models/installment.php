<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class installment extends Model
{
    use HasFactory;
    
    protected $table = 'installments';
    protected $fillable = [
        'order_id',
        'amount',
        'due_date',
        'created_at',];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function installmentValues()
    {
        return $this->hasMany(InstallmentValue::class);
    }
}