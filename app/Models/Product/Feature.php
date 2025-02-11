<?php
namespace App\Models\Product;


use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Feature extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];
    
    protected $table = 'product_features';
    protected $primaryKey = 'id';
    protected $with = ["translation"];

    protected $appends = [
        'has_english'
    ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug','thumbnail', 'status', 'category_id'
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_features_rel');
    }
    
    public function getHasEnglishAttribute()
    {
        return $this->hasTranslation('id');
    }

    public function getThumbnailAttribute()
    {
        if (file_exists( public_path() . '/uploads/' .$this->attributes['thumbnail']) && $this->attributes['thumbnail'] !== null) {
            return '/uploads/'.$this->attributes['thumbnail'];
        } else {
            return '/images/placeholder/product.png';
        }
    }
}
