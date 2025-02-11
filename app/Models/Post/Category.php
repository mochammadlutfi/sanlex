<?php

namespace App\Models\Post;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model implements TranslatableContract
{
    use Translatable, NodeTrait;

    public $translatedAttributes = ['title', 'slug', 'description'];
    protected $table = 'post_category';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    public function post()
    {
        return $this->hasOne(Post::class, 'category_id', 'id');
    }
}
