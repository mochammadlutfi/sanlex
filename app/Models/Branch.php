<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Branch extends Model
{

    protected $table = 'branch';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'city_id', 'phone', 'postal_code', 'address', 'lat', 'lng'
    ];

    public function province()
    {
        return $this->belongsTo(Daerah\Provinsi::class, 'province_id');
    }


    public function city()
    {
        return $this->belongsTo(Daerah\Kota::class, 'city_id');
    }

}
