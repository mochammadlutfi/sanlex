<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    
    protected $table = 'customer_address';

    protected $fillable = [
        'customer_id', 'address', 'kota_id', 'kec_id', 'kel_id', 'prov_id', 'pos', 'lat', 'lng'
    ];

    
    public function provinsi()
    {
        return $this->belongsTo(\App\Models\Daerah\Provinsi::class, 'prov_id');
    }

    public function kota()
    {
        return $this->belongsTo(\App\Models\Daerah\Kota::class, 'kota_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(\App\Models\Daerah\Kecamatan::class, 'kec_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(\App\Models\Daerah\Kelurahan::class, 'kel_id');
    }
}
