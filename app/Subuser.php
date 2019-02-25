<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Uuids;

class Subuser extends Authenticatable
{
    use HasApiTokens, Notifiable, Uuids;


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

    public function checkPermission() {
        if($this->permission === 1){
            
            return true;

        }else {
            return abort(403, 'Unauthorized action.');

        }
    }

}
