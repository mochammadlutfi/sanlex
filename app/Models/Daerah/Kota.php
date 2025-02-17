<?php

namespace App\Models\Daerah;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'mst_kota';

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'prov_id');
    }
}
