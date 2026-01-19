<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Branches;
use App\Models\Activity;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ActivityController extends Controller
{
    public function showCreateActivity()
    {
        $user = Auth::user();
        
        // ✅ ถ้าเป็น admin ดูหน่วยงานทั้งหมด, ถ้าไม่ใช่ดูเฉพาะหน่วยงานของตัวเอง
        if ($user->role === 'admin') {
            $agencies = Agency::with('branches')->get();
        } else {
            $agencies = Agency::with('branches')
                ->where('agency_id', $user->agency_id)
                ->get();
        }
        
        $branches = Branches::all();
        return view('actitvity.CreateActivity', compact('agencies', 'branches', 'user'));
    }

    public function saveActivity(Request $request)
    {
        $user = Auth::user();
        
        // ✅ ถ้าไม่ใช่ admin ให้บังคับใช้ agency_id ของ user
        if ($user->role !== 'admin') {
            $request->merge(['agency_id' => $user->agency_id]);
        }
        
        $request->validate([
            'activity_name' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'agency_id' => 'required|exists:agency,agency_id',
            'branch_id' => 'nullable|exists:branches,branch_id',
        ]);

        $activity = new Activity();
        $activity->user_id = $request->user_id;
        $activity->activity_name = $request->activity_name;
        $activity->agency_id = $request->agency_id;
        $activity->branch_id = $request->branch_id;
        $activity->start_date = $request->start_date;
        $activity->end_date = $request->end_date;
        $activity->access_code = Activity::generateAccessCode();
        $activity->is_active = true;
        $activity->save();

        return redirect()
            ->route('add-certificate', ['activity_id' => $activity->activity_id])
            ->with('success', 'บันทึกกิจกรรมเรียบร้อย! กรุณาเพิ่มใบประกาศต่อ');
    }

    public function showManageActivities()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $activities = Activity::with(['agency', 'branch', 'user','participants'])
                ->get();
        } else {
            $activities = Activity::with(['agency', 'branch', 'user'])
                ->where('user_id', $user->user_id)
                ->get();
        }
        return view('actitvity.ManageActivities', compact('activities'));
    }

    public function storeCertificate(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activity,activity_id',
            'certificate_img' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'position_x' => 'required|integer|min:0',
            'position_y' => 'required|integer|min:0',
            'font_size' => 'nullable|integer|min:8|max:72',
            'font_color' => 'nullable|string|max:7',
        ]);

        $activity = Activity::findOrFail($request->activity_id);
        
        if ($activity->certificate_img) {
            Storage::disk('public')->delete($activity->certificate_img);
        }
        
        $file = $request->file('certificate_img');
        $extension = $file->getClientOriginalExtension();
        $fileName = 'cert_' . $activity->activity_id . '_' . time() . '.' . $extension;
        
        $path = $file->storeAs('certificates',$fileName,'public');
        
        $activity->certificate_img = $path; 
        $activity->position_x = $request->position_x;
        $activity->position_y = $request->position_y;
        $activity->font_size = $request->font_size ?? 16;
        $activity->font_color = $request->font_color ?? '#000000';
        $activity->save();

        return redirect()
            ->route('activity.certificates', ['id' => $activity->activity_id])
            ->with('success', 'อัปโหลดใบประกาศสำเร็จ! ตอนนี้สามารถเพิ่มรายชื่อผู้เข้าร่วมได้แล้ว');
    }

    public function editActivity($id)
    {
        $user = Auth::user();
        $activity = Activity::findOrFail($id);
        
        // ✅ ถ้าเป็น admin ดูหน่วยงานทั้งหมด, ถ้าไม่ใช่ดูเฉพาะหน่วยงานของตัวเอง
        if ($user->role === 'admin') {
            $agencies = Agency::with('branches')->get();
        } else {
            $agencies = Agency::with('branches')
                ->where('agency_id', $user->agency_id)
                ->get();
        }
        
        $branches = Branches::all();
        
        return view('actitvity.EditActivity', compact('activity', 'agencies', 'branches', 'user'));
    }

    public function updateActivity(Request $request, $id)
    {
        $user = Auth::user();
        $activity = Activity::findOrFail($id);
        $isStarted = now()->gte($activity->start_date);
        
        // ✅ ถ้าไม่ใช่ admin ให้บังคับใช้ agency_id ของ user
        if ($user->role !== 'admin') {
            $request->merge(['agency_id' => $user->agency_id]);
        }
        
        $request->validate([
            'activity_name' => 'required|string|max:255',
            'start_date' => $isStarted 
                ? 'required|date'
                : 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'agency_id' => 'nullable|exists:agency,agency_id',
            'branch_id' => 'nullable|exists:branches,branch_id',
            'certificate_img' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'position_x' => 'nullable|integer|min:0|max:1000',
            'position_y' => 'nullable|integer|min:0|max:1000',
            'font_size' => 'nullable|integer|min:8|max:72',
            'font_color' => 'nullable|string|max:7',
        ]);
        
        $activity->activity_name = $request->activity_name;
        $activity->agency_id = $request->agency_id;
        $activity->branch_id = $request->branch_id;
        $activity->start_date = $request->start_date;
        $activity->end_date = $request->end_date;
        $activity->font_size = $request->font_size ?? 16;
        $activity->font_color = $request->font_color ?? '#000000';
        $activity->is_active = $request->has('is_active') ? 1 : 0;
        
        if ($request->hasFile('certificate_img')) {
            if ($activity->certificate_img) {
                Storage::disk('public')->delete($activity->certificate_img);
            }
            
            $file = $request->file('certificate_img');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'cert_' . $activity->activity_id . '_' . time() . '.' . $extension;
            $path = $file->storeAs('certificates', $fileName, 'public');
            
            $activity->certificate_img = $path;
        }
        
        if ($request->filled('position_x') && $request->filled('position_y')) {
            $activity->position_x = $request->position_x;
            $activity->position_y = $request->position_y;
        }
        
        $activity->save();

        return redirect()
            ->route('manage-activities')
            ->with('success', 'แก้ไขกิจกรรมเรียบร้อยแล้ว');
    }

    // ... methods อื่นๆ เหมือนเดิม ...
    
    public function showAddCertificate($id)
    {
        $activity = Activity::findOrFail($id);
        return view('actitvity.Importimg', compact('activity'));
    }

    public function deleteActivity($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->participants()->delete();
        
        if ($activity->certificate_img) {
            Storage::disk('public')->delete($activity->certificate_img);
        }
        
        $activity->delete();
        
        return redirect()->route('manage-activities')->with('success', 'กิจกรรมถูกลบเรียบร้อยแล้ว');
    }

    public function showUploadParticipants($activityId)
    {
        $activity = Activity::with(['participants.downloadLogs', 'agency'])->findOrFail($activityId);
        return view('actitvity.activity_certificates', compact('activity'));
    }

    public function uploadParticipants(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        
        $request->validate([
            'participants_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $file = $request->file('participants_file');
            
            Log::info('📁 File upload details:', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension()
            ]);

            $beforeCount = Participant::where('activity_id', $activity->activity_id)->count();

            $import = new ParticipantsImport($activity->activity_id);
            Excel::import($import, $file);

            $afterCount = Participant::where('activity_id', $activity->activity_id)->count();
            $actualImported = $afterCount - $beforeCount;
            $skipped = $import->getSkippedCount();
            
            $message = "อัพโหลดเรียบร้อยแล้ว: เพิ่มใหม่ {$actualImported} คน";
            if ($skipped > 0) {
                $message .= " | ข้ามข้อมูลซ้ำ {$skipped} คน";
            }
            $message .= " | รวมทั้งหมด {$afterCount} คน";
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('❌ Error uploading participants:', ['message' => $e->getMessage()]);
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function addParticipant(Request $request, $activityId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('participants', 'student_id')
                    ->where('activity_id', $activityId)
            ],
            'email' => 'nullable|email|max:255',
        ]);

        $participant = new Participant();
        $participant->activity_id = $activityId;
        $participant->name = $request->name;
        $participant->email = $request->email;
        $participant->certificate_token = Participant::generateToken();
        $participant->student_id = $request->student_id;
        $participant->save();

        return back()->with('success', 'เพิ่มผู้เข้าร่วมเรียบร้อยแล้ว');
    }

    public function editparticipant($id)
    {
        $participant = Participant::findOrFail($id);
        return view('actitvity.EditParticipant', compact('participant'));
    }
    
    public function updateparticipant(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $participant->name = $validatedData['name'];
        $participant->save();

        return redirect()->route('activity.certificates', $participant->activity_id)->with('success', 'แก้ไขผู้เข้าร่วมเรียบร้อยแล้ว');
    }

    public function deleteParticipant($id)
    {
        $user = Auth::user();
        $participant = Participant::with('downloadLogs')->findOrFail($id);
        
        // ✅ ตรวจสอบว่ามี download log หรือไม่
        $hasDownloadLog = $participant->downloadLogs()->exists();
        
        // 🔒 ถ้าไม่ใช่ admin และมี download log ห้ามลบ
        if ($hasDownloadLog && $user->role !== 'admin') {
            return back()->with('error', 'ไม่สามารถลบผู้เข้าร่วมที่มีประวัติดาวน์โหลดใบประกาศได้ กรุณาติดต่อ Admin หากมีปัญหา');
        }
        
        // ⚠️ ถ้าเป็น admin และมี download log แสดงคำเตือน
        if ($hasDownloadLog && $user->role === 'admin') {
            // Admin สามารถลบได้ แต่ต้องยืนยัน
            $downloadCount = $participant->downloadLogs->count();
            // คำเตือนจะแสดงใน JavaScript confirmation
        }
        
        // ✅ ลบ download logs ก่อน (ถ้าเป็น admin)
        if ($user->role === 'admin') {
            $participant->downloadLogs()->delete();
        }
        
        $participant->delete();

        return back()->with('success', 'ลบผู้เข้าร่วมเรียบร้อยแล้ว');
    }
 
    public function getBranchesByAgency($agencyId)
    {
        $branches = Branches::where('agency_id', $agencyId)->get();
        return response()->json($branches);
    }

    public function downloadTemplateGeneral()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Participants');
        
        $headers = ['name', 'email', 'student_id'];
        $sheet->fromArray($headers, null, 'A1');
        
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
        
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(20);
        
        $exampleData = [
            ['Name Surname', 'email@example.com', '6412345']
        ];
        $sheet->fromArray($exampleData, null, 'A2');
        
        $fileName = 'participants_template.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}