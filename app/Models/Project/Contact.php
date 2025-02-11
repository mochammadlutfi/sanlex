<?php

namespace App\Models\Project;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model 
{

    protected $table = 'project_contacts';
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
