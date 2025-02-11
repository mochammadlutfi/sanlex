<?php
namespace App\Models\Product;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Brand extends Model implements TranslatableContract
{
    use Translatable;
    use HasSlug;

    protected $table = 'brands';
    protected $primaryKey = 'id';
    public $translatedAttributes = ['description'];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'logo', 'slug'
    ];

    protected $appends = [
        'image_url'
    ];


    public function product()
    {
        return $this->hasOne(Product::class, 'brand_id');
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    
    public function getImageUrlAttribute()
    {
        if (file_exists(public_path() . $this->attributes['image']) && $this->attributes['image'] !== null) {
            return asset($this->attributes['image']);
        } else {
            return asset('/images/placeholder/brand.png');
        }
    }
}
