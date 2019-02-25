<?php

namespace App;
    
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Uuids;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Uuids, Billable;


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


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

        public function subuser(){
        return $this->hasMany(Subuser::class);
    }
}
