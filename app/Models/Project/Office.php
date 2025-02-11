<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Office extends Model 
{

    protected $table = 'project_office';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'pic', 'hp', 'telp',
    ];

}
