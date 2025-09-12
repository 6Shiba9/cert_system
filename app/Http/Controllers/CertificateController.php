<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Participant;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function generateCertificates($activityId)
    {
        $activity = Activity::with('participants')->findOrFail($activityId);
        
        if (!$activity->certificate_img) {
            return back()->with('error', 'กรุณาอัพโหลดใบประกาศก่อนสร้าง PDF');
        }

        $generatedCount = 0;
        
        foreach ($activity->participants as $participant) {
            $this->generateSingleCertificate($activity, $participant);
            $participant->update(['certificate_generated' => true]);
            $generatedCount++;
        }

        return back()->with('success', "สร้างใบประกาศสำเร็จ จำนวน {$generatedCount} ใบ");
    }

    public function viewCertificates($activityId)
    {
        $activity = Activity::with(['participants' => function($query) {
            $query->where('certificate_generated', true);
        }])->findOrFail($activityId);

        // Check if certificates directory exists
        $certificatesPath = storage_path('app/public/certificates/generated/');
        if (!file_exists($certificatesPath)) {
            mkdir($certificatesPath, 0755, true);
        }

        // Get all generated certificate files for this activity
        $certificates = [];
        foreach ($activity->participants as $participant) {
            $fileName = 'certificate_' . $participant->certificate_token . '.png';
            $filePath = $certificatesPath . $fileName;
            
            if (file_exists($filePath)) {
                $certificates[] = [
                    'participant' => $participant,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_url' => asset('storage/certificates/generated/' . $fileName),
                    'file_size' => $this->formatBytes(filesize($filePath)),
                    'created_at' => date('d/m/Y H:i:s', filemtime($filePath))
                ];
            }
        }

        return view('certificate.view_certificates', compact('activity', 'certificates'));
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }

    public function downloadAllCertificates($activityId)
    {
        $activity = Activity::with('participants')->findOrFail($activityId);
        
        $zipFileName = 'certificates_' . str_replace(' ', '_', $activity->activity_name) . '_' . date('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Create temp directory if it doesn't exist
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }
        
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return back()->with('error', 'ไม่สามารถสร้างไฟล์ ZIP ได้');
        }
        
        $addedCount = 0;
        foreach ($activity->participants as $participant) {
            if ($participant->certificate_generated) {
                $fileName = 'certificate_' . $participant->certificate_token . '.png';
                $filePath = storage_path('app/public/certificates/generated/' . $fileName);
                
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $participant->name . '.png');
                    $addedCount++;
                }
            }
        }
        
        $zip->close();
        
        if ($addedCount === 0) {
            unlink($zipPath);
            return back()->with('error', 'ไม่พบใบประกาศที่ต้องการดาวน์โหลด');
        }
        
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function generateSingleCertificate(Activity $activity, Participant $participant)
    {
        // Load the certificate template
        $imagePath = storage_path('app/public/' . $activity->certificate_img);
        
        if (!file_exists($imagePath)) {
            throw new \Exception('ไม่พบไฟล์ใบประกาศ');
        }

        // Create image manager and load image
        $manager = new ImageManager(new Driver());
        $img = $manager->read($imagePath);
        
        // Add participant name to the certificate using DrawTool
        $img = $img->text($participant->name, $activity->position_x, $activity->position_y, function($font) {
            $font->size(24);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        // Save the personalized certificate
        $fileName = 'certificate_' . $participant->certificate_token . '.png';
        $outputPath = storage_path('app/public/certificates/generated/' . $fileName);
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }
        
        $img->save($outputPath);

        return $outputPath;
    }

    public function downloadCertificate(Request $request, $token)
    {
        $participant = Participant::where('certificate_token', $token)->firstOrFail();
        $activity = $participant->activity;

        if (!$activity->is_active) {
            abort(404, 'กิจกรรมนี้ปิดการใช้งานแล้ว');
        }

        // Generate certificate if not exists
        if (!$participant->certificate_generated) {
            $this->generateSingleCertificate($activity, $participant);
            $participant->update(['certificate_generated' => true]);
        }

        // Log the download
        DownloadLog::create([
            'participant_id' => $participant->participant_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'downloaded_at' => now(),
        ]);

        // Return the certificate file
        $fileName = 'certificate_' . $participant->certificate_token . '.png';
        $filePath = storage_path('app/public/certificates/generated/' . $fileName);

        if (file_exists($filePath)) {
            return response()->download($filePath, "ใบประกาศ_{$participant->name}_{$activity->activity_name}.png");
        }

        abort(404, 'ไม่พบใบประกาศ');
    }

    public function showCertificateForm()
    {
        return view('certificate.access');
    }

    public function accessCertificate(Request $request)
    {
        $request->validate([
            'access_code' => 'required|string',
            'name' => 'required|string',
        ]);

        $activity = Activity::where('access_code', $request->access_code)
                           ->where('is_active', true)
                           ->first();

        if (!$activity) {
            return back()->with('error', 'รหัสเข้าถึงไม่ถูกต้องหรือกิจกรรมปิดการใช้งาน');
        }

        $participant = $activity->participants()
                               ->where('name', 'LIKE', '%' . $request->name . '%')
                               ->first();

        if (!$participant) {
            return back()->with('error', 'ไม่พบชื่อของคุณในกิจกรรมนี้');
        }

        return redirect()->route('download-certificate', $participant->certificate_token);
    }

    public function showActivityCertificates($activityId)
    {
        $activity = Activity::with(['participants.downloadLogs'])->findOrFail($activityId);
        
        return view('certificate.activity_certificates', compact('activity'));
    }

    public function previewCertificate($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        
        if (!$activity->certificate_img) {
            return back()->with('error', 'กรุณาอัพโหลดใบประกาศก่อน');
        }

        // Create a preview with sample name
        $imagePath = storage_path('app/public/' . $activity->certificate_img);
        $manager = new ImageManager(new Driver());
        $img = $manager->read($imagePath);
        
        // Add sample text (simpler version without custom fonts)
        $img = $img->text('ตัวอย่างชื่อผู้เข้าร่วม', $activity->position_x, $activity->position_y, function($font) {
            $font->size(24);
            $font->color('#FF0000'); // Red color for preview
            $font->align('center');
            $font->valign('middle');
        });

        // Return as response
        return response($img->toPng())->header('Content-Type', 'image/png');
    }
}
