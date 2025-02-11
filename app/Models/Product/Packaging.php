<?php
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Packaging extends Model 
{

    protected $table = 'product_packaging';
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
        return $this->hasMany(ProductVariant::class, 'packaging_id');
    }

}
