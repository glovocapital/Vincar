<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_nombre', 'user_apellido', 'user_rut', 'user_cargo', 'email', 'password'
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


    public function hasRole($role){
        return ($this->oneRol->rol_desc == $role) ? true:false;
    }

    public function oneRol()
    {
        return $this->hasOne(Rol::class, 'rol_id', 'rol_id');
    }

    public function belongsToEmpresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }

    public function vins() {
        return Vin::where('user_id', $this->user_id)->get();
    }

/*
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
        //$this->notify(new Notifications\MailResetPasswordNotification($token));
    }
*/
}
