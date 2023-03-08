<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    //use HasApiTokens, HasFactory, Notifiable;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $connection = 'mysql';

    protected $fillable = [
        'username',
        'password',
        'lastname',
        'firstname',
        'middlename',
        'extname',
        'level_id',
        'status_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function user_role()
    {
        return $this->hasMany(UsersRoleList::class, 'user_id', 'id');
    }
    public function statuses()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id')->withDefault();
    }
}
