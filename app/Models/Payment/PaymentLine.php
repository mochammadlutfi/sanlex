<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLine extends Model
{
    use HasFactory;


    protected $connection = 'orange';
    protected $table = 'sale_payment_line';
    protected $primaryKey = 'id';

    protected $fillable = [

    ];

    
    public function payment(){
        return $this->belongsTo(Payment::Class, 'payment_id');
    }
}
