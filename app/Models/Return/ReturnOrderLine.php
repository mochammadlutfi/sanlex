<?php

namespace App\Models\Return;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrderLine extends Model
{
    use HasFactory;


    protected $connection = 'orange';
    protected $table = 'return_order_line';
    protected $primaryKey = 'id';

    protected $fillable = [

    ];

    
    public function return(){
        return $this->belongsTo(ReturnOrder::Class, 'order_id');
    }
}
