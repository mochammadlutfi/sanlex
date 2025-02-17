<?php

namespace App\Models\Daerah;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'mst_kecamatan';

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
