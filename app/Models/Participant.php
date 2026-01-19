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
        'certificate_generated_at',
        'certificate_signature',
    ];

    protected $casts = [
        'certificate_generated' => 'boolean',
        'certificate_generated_at' => 'datetime',
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

    /**
     * ✅ สร้าง Digital Signature
     */
    public function generateSignature()
    {
        return hash('sha256', $this->certificate_token . $this->name . $this->activity_id . now()->timestamp);
    }

    /**
     * ✅ สร้าง Certificate ID
     */
    public function getCertificateIdAttribute()
    {
        return strtoupper(substr($this->activity->access_code, 0, 4)) . '-' . 
               str_pad($this->participant_id, 6, '0', STR_PAD_LEFT);
    }
}