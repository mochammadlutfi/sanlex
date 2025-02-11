<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'email', 'password',
    ];

    protected $append = [
        'dari'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function kota()
    {
        return $this->belongsTo('App\Models\Kota', 'kota_id', 'id');
    }

    public function getDariAttribute($value)
    {
        return ucwords(strtolower($this->kota->name).', '.strtolower($this->kota->provinsi->name));
    }

    public function getLastLoginAtAttribute($value)
    {
        Carbon::setLocale('id');
        return Carbon::parse($value)->translatedFormat('l, d F Y h:i');
    }


}
