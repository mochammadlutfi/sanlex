<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    use HasFactory;


    protected $connection = 'orange';
    protected $table = 'account_invoice_line';
    protected $primaryKey = 'id';

    protected $fillable = [

    ];

    
    public function invoice(){
        return $this->belongsTo(Invoice::Class, 'invoice_id');
    }
}
