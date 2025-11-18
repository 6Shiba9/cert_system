<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Participant;
use App\Models\DownloadLog;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;

class CertificateController extends Controller
{
    /**
     * แสดงใบประกาศเป็น PDF (ไม่เก็บไฟล์ - สร้างทุกครั้ง)
     * URL: /certificate/pdf/{token}
     */
    public function viewCertificatePdf($token)
    {
        $participant = Participant::where('certificate_token', $token)->firstOrFail();
        $activity = $participant->activity;

        // เช็คว่ากิจกรรมเปิดอยู่หรือไม่
        if (!$activity->is_active) {
            abort(404, 'กิจกรรมนี้ปิดการใช้งานแล้ว');
        }

        // บันทึก Log การเข้าชม
        DownloadLog::create([
            'participant_id' => $participant->participant_id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        // สร้าง HTML สำหรับ PDF
        $html = view('certificate.template_pdf', [
            'activity' => $activity,
            'participant' => $participant,
        ])->render();

        // ตั้งค่า Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // โหลดรูปจาก URL ได้
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // แนวนอน
        $dompdf->render();

        // ✅ แสดง PDF ในเบราว์เซอร์ (ไม่ดาวน์โหลด)
        return $dompdf->stream("certificate_{$participant->name}.pdf", [
            "Attachment" => false // false = เปิดดู, true = ดาวน์โหลด
        ]);
    }

    /**
     * ดาวน์โหลดใบประกาศเป็น PDF
     * URL: /certificate/pdf/{token}/download
     */
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

        // สร้าง PDF
        $html = view('certificate.template_pdf', [
            'activity' => $activity,
            'participant' => $participant,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // ✅ ดาวน์โหลด PDF
        return $dompdf->stream("certificate_{$participant->name}.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * แสดงฟอร์มค้นหาใบประกาศ
     */
    public function showCertificateForm()
    {
        return view('certificate.access');
    }

    /**
     * ค้นหาใบประกาศจากรหัสและชื่อ
     */
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

        // Redirect ไปแสดง PDF
        return redirect()->route('certificate.pdf', $participant->certificate_token);
    }

    /**
     * แสดงตัวอย่างใบประกาศ (สำหรับ Admin)
     */
    public function previewCertificate($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        
        if (!$activity->certificate_img) {
            return back()->with('error', 'กรุณาอัพโหลดใบประกาศก่อน');
        }

        // สร้าง Participant จำลอง
        $participant = new Participant([
            'name' => 'ตัวอย่างชื่อผู้เข้าร่วม',
        ]);

        // สร้าง HTML
        $html = view('certificate.template_pdf', [
            'activity' => $activity,
            'participant' => $participant,
            'preview' => true, // บอกว่าเป็น preview
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

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
    
    // dd($activities); // ← ลบบรรทัดนี้ออก
    
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
}