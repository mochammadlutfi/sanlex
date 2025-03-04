<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $connection = 'orange';
    protected $table = 'account_invoice';
    protected $primaryKey = 'id';
    
    public function line(){
        return $this->hasMany(InvoiceLine::Class, 'invoice_id');
    }
}
