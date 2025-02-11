<?php

namespace App\Models\Project;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class PortofolioTranslation extends Model
{
    use HasSlug;

    protected $table = 'project_portofolio_translations';
    protected $fillable = ['title', 'description', 'slug'];

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
