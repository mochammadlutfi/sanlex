<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $connection = 'orange';
    protected $table = 'sale_payment';
    protected $primaryKey = 'id';
    
    public function line(){
        return $this->hasMany(PaymentLine::Class, 'payment_id');
    }
}
