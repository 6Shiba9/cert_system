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

class ParticipantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors;
    
    protected $activityId;
    protected $imported = 0;

    public function __construct($activityId)
    {
        $this->activityId = $activityId;
    }

    public function model(array $row)
    {
        // Get name from either English or Thai column
        $name = trim($row['name'] ?? $row['ชื่อ'] ?? '');
        
        // Skip empty rows
        if (empty($name)) {
            \Log::info('Skipping empty row:', $row);
            return null;
        }

        $email = $row['email'] ?? $row['อีเมล'] ?? null;
        $studentId = $row['student_id'] ?? $row['รหัสนักศึกษา'] ?? null;

        // Clean up email
        if ($email) {
            $email = trim($email);
            if (empty($email)) {
                $email = null;
            }
        }

        // Clean up student ID
        if ($studentId) {
            $studentId = trim($studentId);
            if (empty($studentId)) {
                $studentId = null;
            }
        }

        // Log what we're trying to import
        \Log::info('Importing participant:', [
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
        return [
            // Don't validate here, validate in model() method instead
        ];
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
}
