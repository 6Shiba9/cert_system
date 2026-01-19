<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'user_id'; 
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'agency_id', 
    ];

    protected $hidden = [
        'password',
    ];
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }
    
    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id', 'user_id');
    }
}
