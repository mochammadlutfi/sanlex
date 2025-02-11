<?php
namespace App\Models\Product;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{

    protected $table = 'brand_translations';
    protected $fillable = ['description'];
    public $timestamps = false;
    
}
