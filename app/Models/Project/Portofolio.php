<?php

namespace App\Models\Project;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Storage;
use Plank\Mediable\Mediable;

class Portofolio extends Model implements TranslatableContract
{
    use Translatable;
    use Mediable;

    protected $table = 'project_portofolio';
    protected $primaryKey = 'id';

    public $translatedAttributes = [
        'title', 'slug', 'description'
    ];

    protected $fillable = [
        
    ];

    protected $appends = [
        'image'
    ];

    public function getImageAttribute()
    {
        $path = null;
        if($this->hasMedia('thumbnail')){
            $path = asset('/uploads/'.$this->getMedia('thumbnail')->first()->getDiskPath());
        }else{
            return asset('/images/placeholder/thumbnail.jpg');
        }
        return $path;
    }
}
