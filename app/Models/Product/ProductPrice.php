<?php
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model 
{

    protected $table = 'product_prices';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'variant_id', 'branch_id', 'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    
    public function Branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }
}
