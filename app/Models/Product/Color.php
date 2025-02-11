<?php
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Color extends Model 
{

    protected $table = 'colors';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'hex'
    ];

    
    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_color_group', 'color_id', 'product_id');
    }

}
