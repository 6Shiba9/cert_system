<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participants';
    protected $primaryKey = 'participant_id';
    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'name',
        'email',
        'student_id',
        'certificate_token',
        'certificate_generated',
    ];

    protected $casts = [
        'certificate_generated' => 'boolean',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'activity_id');
    }

    public function downloadLogs()
    {
        return $this->hasMany(DownloadLog::class, 'participant_id', 'participant_id');
    }

    public static function generateToken()
    {
        do {
            $token = bin2hex(random_bytes(16));
        } while (self::where('certificate_token', $token)->exists());
        
        return $token;
    }
}
