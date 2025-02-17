<?php

namespace App\Models\Daerah;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'mst_provinsi';

    public function kota()
    {
        return $this->hasMany('App\Models\Kota', 'id', 'regency_id');
    }

}
