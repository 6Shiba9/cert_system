<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Agency;
use App\Models\Branches;
use App\Models\User;
class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';
    protected $primaryKey = 'activity_id';
    public $timestamps = false;


    protected $fillable = [
        'activity_name',
        'position_x',
        'position_y',
        'agency_id',
        'branch_id',
        'start_date',
        'end_date',
        'certificate_img',
        'user_id',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id', 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // โดยปกติ Primary Key ของ User คือ id
    }
}