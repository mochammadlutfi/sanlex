<?php

namespace App\Models\Daerah;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'mst_kelurahan';

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id');
    }
}
