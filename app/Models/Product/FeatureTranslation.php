<?php
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class FeatureTranslation extends Model
{
    protected $table = 'product_features_translations';
    protected $fillable = ['name'];
    public $timestamps = false;

}
