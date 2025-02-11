<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    protected $table = 'contact';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name', 'email', 'phone', 'category', 'profession', 'subject', 'description'
    ];

    protected $appends = ['dibuat', 'status_badge'];

    public function getDibuatAttribute()
    {
        // Carbon::setLocale();
        return Carbon::parse($this->attributes['created_at'])->translatedFormat('l, d F Y');
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->attributes['status'] === 0) {
            return '<span class="badge badge-danger">Belum Dibaca</span>';
        } else {
            return '<span class="badge badge-info">Suda Dibaca</span>';
        }
    }


}
