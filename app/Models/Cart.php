<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name', 'image'
    ];

    
    
    public function product()
    {
        return $this->belongsTo(App\Models\Product\Product::class, 'product_id');
    }

    
    public function variant()
    {
        return $this->belongsTo(App\Models\Product\ProductVariant::class, 'variant_id');
    }
}
