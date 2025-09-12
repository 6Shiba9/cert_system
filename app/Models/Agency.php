<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agency extends Authenticatable
{
    use Notifiable;

    protected $table = 'agency';
    protected $primaryKey = 'agency_id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'agency_name',
    ];

    public function branches()
    {
        return $this->hasMany(Branches::class, 'agency_id', 'agency_id');
    }
}