<?php

namespace App\Models\Post;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    use HasSlug;

    protected $fillable = ['title', 'description', 'slug'];
    public $timestamps = false;

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
