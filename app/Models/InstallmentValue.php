<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentValue extends Model
{
    use HasFactory;

    protected $table = 'installment_values';
    protected $fillable = [
        'installment_id',
        'value'
    ];
    
    public function installment(){
        return $this->belongsTo(Installment::class);
    }
    
}
