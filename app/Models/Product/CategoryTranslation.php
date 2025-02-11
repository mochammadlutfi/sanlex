<?php
namespace App\Models\Product;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{

    protected $table = 'product_category_translations';
    protected $fillable = ['name', 'description'];
    public $timestamps = false;
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
