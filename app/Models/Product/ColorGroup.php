<?php
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ColorGroup extends Model 
{

    protected $table = 'product_color_group';
    protected $primaryKey = 'id';
    public $timestamps = false;

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
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

}
