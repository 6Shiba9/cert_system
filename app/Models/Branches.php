<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class branches extends Authenticatable
{
    use Notifiable;

    protected $table = 'branches';
    protected $primaryKey = 'branch_id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'branch_name',
        'agency_id',
    ];

        public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }
}