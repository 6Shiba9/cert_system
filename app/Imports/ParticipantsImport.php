<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Facades\Log;

class ParticipantsImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnError, 
    WithBatchInserts, 
    WithChunkReading,
    WithUpserts  // เพิ่มนี้
{
    use Importable, SkipsErrors;
    
    protected $activityId;
    protected $imported = 0;
    protected $skipped = 0;

    public function __construct($activityId)
    {
        $this->activityId = $activityId;
    }

    /**
     * กำหนดคอลัมน์ที่ใช้เป็น unique key
     */
    public function uniqueBy()
    {
        return ['activity_id', 'student_id'];
    }

    public function model(array $row)
    {
        Log::info('📝 Reading row:', $row);
        
        $name = trim($row['name'] ?? $row['ชื่อ'] ?? '');
        
        if (empty($name)) {
            Log::warning('⚠️ Skipping empty row');
            return null;
        }

        $email = $row['email'] ?? $row['อีเมล'] ?? null;
        $studentId = $row['student_id'] ?? $row['รหัสนักศึกษา'] ?? null;

        if ($email) {
            $email = trim($email);
            if (empty($email)) $email = null;
        }

        if ($studentId) {
            $studentId = trim($studentId);
            if (empty($studentId)) $studentId = null;
        }

        // ตรวจสอบว่ามีข้อมูลซ้ำหรือไม่
        $exists = Participant::where('activity_id', $this->activityId)
            ->where('student_id', $studentId)
            ->exists();

        if ($exists) {
            Log::warning('⚠️ Duplicate student_id skipped:', [
                'student_id' => $studentId,
                'name' => $name
            ]);
            $this->skipped++;
            return null;
        }

        Log::info('✅ Creating participant:', [
            'activity_id' => $this->activityId,
            'name' => $name,
            'email' => $email,
            'student_id' => $studentId
        ]);

        $this->imported++;

        return new Participant([
            'activity_id' => $this->activityId,
            'name' => $name,
            'email' => $email,
            'student_id' => $studentId,
            'certificate_token' => Participant::generateToken(),
            'certificate_generated' => false,
        ]);
    }

    public function rules(): array
    {
        return [];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getImportedCount()
    {
        return $this->imported;
    }

    public function getSkippedCount()
    {
        return $this->skipped;
    }
}