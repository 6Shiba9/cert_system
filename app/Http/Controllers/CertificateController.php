<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Participant;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
class CertificateController extends Controller
{
        private function createPdfWithThaiFont($html, $participant)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', false); // ปิด subsetting
        $options->set('defaultFont', 'thsarabunnew'); // ใช้ตัวพิมพ์เล็กทั้งหมด
        $options->set('defaultMediaType', 'print');
        $options->set('isCssFloatEnabled', true);
        $options->set('isPhpEnabled', false);
        
        // กำหนด path
        $fontDir = public_path('fonts');
        $fontCache = storage_path('fonts');
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!File::exists($fontCache)) {
            File::makeDirectory($fontCache, 0755, true);
        }
        
        $options->set('fontDir', $fontDir);
        $options->set('fontCache', $fontCache);
        $options->set('tempDir', storage_path('app/temp'));
        $options->set('chroot', [public_path(), storage_path()]);
        
        // สร้าง Dompdf instance
        $dompdf = new Dompdf($options);
        
        // โหลด HTML
        $dompdf->loadHtml($html);
        
        // ตั้งค่ากระดาษ
        $dompdf->setPaper('A4', 'landscape');
        
        // Render
        $dompdf->render();
        
        return $dompdf;
    }

    public function viewCertificatePdf($token)
    {
        $participant = Participant::where('certificate_token', $token)->firstOrFail();
        $activity = $participant->activity;

        if (!$activity->is_active) {
            abort(404, 'กิจกรรมนี้ปิดการใช้งานแล้ว');
        }

        // สร้าง HTML
        $html = view('certificate.template_pdf', [
            'activity' => $activity,
            'participant' => $participant,
        ])->render();

        // สร้าง PDF พร้อมฟอนต์ไทย
        $dompdf = $this->createPdfWithThaiFont($html, $participant);

        return $dompdf->stream("certificate_{$participant->name}.pdf", [
            "Attachment" => false
        ]);
    }

    public function downloadCertificatePdf($token)
    {
        $participant = Participant::where('certificate_token', $token)->firstOrFail();
        $activity = $participant->activity;

        if (!$activity->is_active) {
            abort(404, 'กิจกรรมนี้ปิดการใช้งานแล้ว');
        }

        // บันทึก Log
        DownloadLog::create([
            'participant_id' => $participant->participant_id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        // สร้าง HTML
        $html = view('certificate.template_pdf', [
            'activity' => $activity,
            'participant' => $participant,
        ])->render();

        // สร้าง PDF พร้อมฟอนต์ไทย
        $dompdf = $this->createPdfWithThaiFont($html, $participant);

        return $dompdf->stream("certificate_{$participant->name}.pdf", [
            "Attachment" => true
        ]);
    }

    // แสดงหน้า Preview Certificate
    public function showPreviewCertificate($activityId)
    {
        $activity = Activity::with(['agency', 'branch'])->findOrFail($activityId);
        
        if (!$activity->certificate_img) {
            return redirect()
                ->route('add-certificate', $activity->activity_id)
                ->with('error', 'กรุณาอัพโหลดใบประกาศก่อน');
        }

        return view('certificate.preview_certificate', compact('activity'));
    }

    // Generate PDF Preview
    public function previewCertificate($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        
        if (!$activity->certificate_img) {
            return back()->with('error', 'กรุณาอัพโหลดใบประกาศก่อน');
        }

        $participant = new Participant([
            'name' => 'นายตัวอย่าง ทดสอบ',
        ]);

        $html = view('certificate.template_pdf', [
            'activity' => $activity,
            'participant' => $participant,
            'preview' => true,
        ])->render();

        $dompdf = $this->createPdfWithThaiFont($html, $participant);

        return $dompdf->stream("preview_certificate.pdf", [
            "Attachment" => false
        ]);
    }

    public function userDashboard(Request $request)
    {
        $activities = Activity::with(['agency', 'participants'])
                     ->where('is_active', true)
                     ->orderBy('activity_id', 'desc')
                     ->get();
        
        return view('certificate.main_user_menu', compact('activities'));
    }

    public function selectParticipant($accessCode)
    {
        $activity = Activity::where('access_code', $accessCode)
                        ->where('is_active', true)
                        ->with(['agency', 'participants'])
                        ->firstOrFail();
        
        $participants = $activity->participants()
                                ->orderBy('name', 'asc')
                                ->get();
        
        return view('certificate.select-participant', compact('activity', 'participants'));
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
            return back()
                ->withInput()
                ->with('error', 'รหัสเข้าถึงไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง');
        }

        $participant = $activity->participants()
                            ->where('name', 'LIKE', '%' . $request->name . '%')
                            ->first();

        if (!$participant) {
            return back()
                ->withInput()
                ->with('error', 'ไม่พบชื่อของคุณในระบบ กรุณาตรวจสอบชื่อ-นามสกุลอีกครั้ง');
        }

        // ถ้ามีรหัสนักศึกษา ให้ไปหน้ายืนยัน
        if (!empty($participant->student_id)) {
            return redirect()->route('certificate.verify.form', $participant->certificate_token);
        }

        // ถ้าไม่มีรหัสนักศึกษา ไปดาวน์โหลดเลย
        return redirect()->route('certificate.pdf', $participant->certificate_token);
    }

        /**
         * แสดงหน้ายืนยันรหัสนักศึกษา
         */
        public function showVerifyForm($token)
        {
            $participant = Participant::where('certificate_token', $token)->firstOrFail();
            $activity = $participant->activity;

            if (!$activity->is_active) {
                abort(404, 'กิจกรรมนี้ปิดการใช้งานแล้ว');
            }

            return view('certificate.verify-student', compact('participant', 'activity'));
        }

        public function verifyStudent(Request $request, $token)
        {
            $request->validate([
                'student_id' => 'required|string',
            ]);

            $participant = Participant::where('certificate_token', $token)->firstOrFail();
            $activity = $participant->activity;

            if (!$activity->is_active) {
                abort(404, 'กิจกรรมนี้ปิดการใช้งานแล้ว');
            }

            // ตรวจสอบรหัสนักศึกษา
            $inputStudentId = trim($request->student_id);
            $actualStudentId = trim($participant->student_id);

            // นับจำนวนครั้ง
            $sessionKey = 'verify_attempts_' . $token;
            $attempts = session($sessionKey, 0);
            $attempts++;
            session([$sessionKey => $attempts]);

            // จำกัด 5 ครั้ง
            if ($attempts > 5) {
                return back()
                    ->with('error', '⚠️ คุณพยายามยืนยันเกิน 5 ครั้ง กรุณาติดต่อผู้จัดกิจกรรม')
                    ->with('attempts', $attempts);
            }

            // ถ้าไม่มีรหัสในระบบ ให้ผ่าน
            if (empty($actualStudentId)) {
                session()->forget($sessionKey);
                return redirect()->route('certificate.pdf', $token);
            }

            // ตรวจสอบรหัส
            if ($inputStudentId !== $actualStudentId) {
                return back()
                    ->with('error', '❌ รหัสนักศึกษาไม่ถูกต้อง (พยายาม ' . $attempts . '/5 ครั้ง)')
                    ->with('attempts', $attempts)
                    ->withInput();
            }

            // ✅ สำเร็จ - บันทึก Log
            DownloadLog::create([
                'participant_id' => $participant->participant_id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'downloaded_at' => now(),
            ]);

            session()->forget($sessionKey);
            return redirect()->route('certificate.pdf', $token);
        }
}