<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Certificate extends Model
{
    protected $table = 'certificates';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name', 'image'
    ];

    protected $appends = [
        'image_url'
    ];


    public function getImageUrlAttribute()
    {
        $result = null;
        if (file_exists(public_path() . '/' . $this->attributes['image']) && $this->attributes['image'] !== null) {
            return asset($this->attributes['image']);
        }

        return $result;
    }
}
