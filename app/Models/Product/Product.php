<?php
namespace App\Models\Product;

use Carbon\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Product\Packaging;

class Product extends Model implements TranslatableContract
{
    use Translatable;
    use HasSlug;

    public $translatedAttributes = ['description', 'application', 'technical', 'spesification'];
    protected $table = 'product';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description', 'category_id', 'packaging', 'color'
    ];
    
    protected $appends = ['image_url'];
    
    public function getPackagingAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        $ids = explode(',', $value);

        return Packaging::whereIn('id', $ids)->get();
    }


    public function getColorAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        $ids = explode(',', $value);

        return Color::whereIn('id', $ids)->get();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function variant()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
    
    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    public function features(){
        return $this->belongsToMany(Feature::class, 'product_features_rel', 'product_id', 'feature_id');
    }

    public function getImageAttribute()
    {
        if (file_exists(public_path() . '/' . $this->attributes['image']) && $this->attributes['image'] !== null) {
            return $this->attributes['image'];
        } else {
            return '/images/placeholder/product.png';
        }
    }

    public function getImageUrlAttribute()
    {
        if (file_exists(public_path() . '/' . $this->attributes['image']) && $this->attributes['image'] !== null) {
            return asset($this->attributes['image']);
        } else {
            return asset('/images/placeholder/product.png');
        }
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
}
