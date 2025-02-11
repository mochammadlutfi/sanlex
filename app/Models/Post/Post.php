<?php

namespace App\Models\Post;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Storage;
use Plank\Mediable\Mediable;
use \Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

class Post extends Model implements TranslatableContract, LocalizedUrlRoutable
{
    use Translatable;
    use Mediable;

    protected $table = 'posts';
    protected $primaryKey = 'id';

    public $translatedAttributes = [
        'title', 'slug', 'description'
    ];

    protected $fillable = [
        'featured_img', 'seo_keyword', 'seo_description', 'seo_tags', 'category_id', 'status', 'views'
    ];

    protected $appends = ['image', 'status_badge'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getDibuatAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->attributes['status'] === 1) {
            return '<span class="badge badge-success">Publikasi</span>';
        } else {
            return '<span class="badge badge-danger">Draft</span>';
        }
    }
    

    public function getImageAttribute()
    {
        $path = null;
        if($this->hasMedia('thumbnail')){
            $path = asset('/uploads/'.$this->getMedia('thumbnail')->first()->getDiskPath());
        }
        return $path;
    }

    public function registerMediaGroups()
    {
        $this->addMediaGroup('post')->performConversions('post_thumb');
    }

    public function getLocalizedRouteKey($locale)
    {
        return $this->translate($locale)->first()->slug;
    }

    // public function resolveRouteBinding($slug)
    // {
    //     return static::whereHas('slugs', function($q){
    //         $q->where('slug', '=', $slug);
    //     })->first() ?? abort(404);
    // }
    



}
