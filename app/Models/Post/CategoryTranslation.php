<?php

namespace App\Models\Post;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{

    protected $table = 'post_category_translations';
    protected $fillable = ['title', 'description'];
    public $timestamps = false;
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
