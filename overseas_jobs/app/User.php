<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function selectedTimeZone()
    {
        if($this->attributes['extra'] == NULL){
            return '+09:00';
        }
        else {
            $offset_val = json_decode($this->attributes['extra']);    
            return $offset_val->offset;
        }
    }

    public function selectedTimeZoneVal()
    {
        if($this->attributes['extra'] == NULL){
            return 'Asia/Tokyo';
        }
        else {
            $timezone_val = json_decode($this->attributes['extra']);
            return $timezone_val->timezone;         
        }
    }
}