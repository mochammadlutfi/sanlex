<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';


    protected $fillable = [
        
    ];
    
    public function product()
    {
        return $this->belongsTo(Product\Product::class, 'product_id');
    }

    
    public function variant()
    {
        return $this->belongsTo(Product\ProductVariant::class, 'variant_id');
    }
}
