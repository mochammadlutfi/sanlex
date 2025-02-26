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

    public function province()
    {
        return $this->belongsTo(\App\Models\Daerah\Provinsi::class, 'province_id');
    }


    public function city()
    {
        return $this->belongsTo(\App\Models\Daerah\Kota::class, 'city_id');
    }

}
