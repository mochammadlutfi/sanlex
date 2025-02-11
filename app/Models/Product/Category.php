<?php
namespace App\Models\Product;


use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model implements TranslatableContract
{
    use Translatable;

    use NodeTrait;

    public $translatedAttributes = ['name', 'slug', 'description'];
    
    protected $table = 'product_category';
    protected $primaryKey = 'id';
    // protected $with = ["translation"];


    protected $appends = [
        'has_english', 'image_url'
    ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image', 'status', 'category_id'
    ];

    public function children(){
        return $this->hasMany(Category::Class, 'parent_id');
    }

    public function product(){
        return $this->hasOne(Product::Class, 'category_id');
    }

    public function parent(){
        return $this->belongsTo(Category::Class, 'parent_id');
    }
    
    public function getLabelAttribute()
    {
        return $this->translate()->name;
    }
    
    public function getValueAttribute()
    {
        return $this->id;
    }

    public function getSlugAttribute()
    {
        return $this->translate()->slug;
    }
    
    public function getHasEnglishAttribute()
    {
        return $this->hasTranslation('id');
    }

    public function getImageAttribute()
    {
        if (file_exists(public_path($this->attributes['image'])) && $this->attributes['image'] !== null) {
            return $this->attributes['image'];
        } else {
            return '/images/placeholder/product.png';
        }
    }
    
    public function getImageUrlAttribute()
    {
        if (file_exists(public_path($this->attributes['image'])) && $this->attributes['image'] !== null) {
            return asset($this->attributes['image']);
        } else {
            return '/images/placeholder/product.png';
        }
    }
}
