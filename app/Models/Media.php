<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Media as Mediables;

class Media extends Mediables
{
    
    protected $appends = [
        'path'
    ];

    public function getPathAttribute($value)
    {
        return '/uploads'.$this->attributes['directory'].'/'.$this->attributes['filename'].'.'.$this->attributes['extension'];
    }
    
}
