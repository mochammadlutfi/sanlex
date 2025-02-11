<?php
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model 
{

    protected $table = 'product_variants';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function packaging()
    {
        return $this->belongsTo(Packaging::class, 'packaging_id');
    }
    
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
    
    public function price()
    {
        return $this->hasMany(ProductPrice::class, 'variant_id');
    }
}
