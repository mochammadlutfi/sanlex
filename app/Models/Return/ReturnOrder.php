<?php

namespace App\Models\Return;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    use HasFactory;

    protected $connection = 'orange';
    protected $table = 'return_order';
    protected $primaryKey = 'id';
    
    public function line(){
        return $this->hasMany(ReturnOrderLine::Class, 'order_id');
    }
}
