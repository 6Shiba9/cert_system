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
    
    const CREATED_AT = 'start_date'; // หรือคอลัมน์อื่นที่เก็บวันที่สร้าง
    const UPDATED_AT = null; // ปิดการใช้ updated_at

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
        'access_code',
        'is_active',
        'font_size',
        'font_color',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'public_timestamps' => 'integer',
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

    public function participants()
    {
        return $this->hasMany(Participant::class, 'activity_id', 'activity_id');
    }

    public static function generateAccessCode()
    {
        do {
            $code = strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        } while (self::where('access_code', $code)->exists());
        
        return $code;
    }

       public function downloadLogs()
    {
        return $this->hasManyThrough(
            DownloadLog::class,
            Participant::class,
            'activity_id',     // Foreign key on participants table
            'participant_id',  // Foreign key on download_logs table
            'activity_id',     // Local key on activity table
            'participant_id'   // Local key on participants table
        );
    }
}