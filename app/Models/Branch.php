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

    // protected $appends = [
    //     'daerah', 'dibuat'
    // ];


    // public function city()
    // {
    //     return $this->belongsTo('App\Models\Kota', 'city_id');
    // }

    // public function getDaerahAttribute($value)
    // {
    //     return ucwords(strtolower($this->city->name)).'<br>'. ucwords(strtolower($this->city->provinsi->name));
    // }

    // public function getDibuatAttribute()
    // {
    //     Carbon::setLocale('id');
    //     return Carbon::parse($this->attributes['created_at'])->translatedFormat('d F Y');
    // }

}
