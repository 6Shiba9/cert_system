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

class ActivityController extends Controller
{
    public function showCreateActivity()
    {
        


        $agencies = Agency::with('branches')->get();
        $branches = Branches::all();
        return view('actitvity.CreateActivity', compact('agencies', 'branches'));
    }

    public function saveActivity(Request $request)
    {
        $request->validate([
        'activity_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'agency_id' => 'nullable|exists:agency,agency_id',
        'branch_id' => 'nullable|exists:branches,branch_id',
    ]);

        // บันทึกกิจกรรม
        $activity = new Activity();
        $activity->user_id = $request->user_id;
        $activity->activity_name = $request->activity_name;
        $activity->agency_id = $request->agency_id;
        $activity->branch_id = $request->branch_id;
        $activity->start_date = $request->start_date;
        $activity->end_date = $request->end_date;
        $activity->access_code = Activity::generateAccessCode();
        $activity->is_active = true; // ตั้งค่าเริ่มต้นเป็น true
        $activity->save();

        // ✅ หลังจากบันทึกสำเร็จ ให้ไปหน้าเพิ่มใบประกาศ
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

    // dd([
    //     'has_file' => $request->hasFile('certificate_img'),
    //     'all_files' => $request->allFiles(),
    //     'all_data' => $request->all(),
    // ]);

        // Validate
    $request->validate([
        'activity_id' => 'required|exists:activity,activity_id',
        'certificate_img' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        'position_x' => 'required|integer|min:0',
        'position_y' => 'required|integer|min:0',
    ]);

    $activity = Activity::findOrFail($request->activity_id);
    
    // 🗑️ ลบไฟล์เก่า (ถ้ามี)
    if ($activity->certificate_img) {
        Storage::disk('public')->delete($activity->certificate_img);
    }
    
    // 📝 สร้างชื่อไฟล์ที่ไม่ซ้ำ
    $file = $request->file('certificate_img');
    $extension = $file->getClientOriginalExtension(); // jpg, png, jpeg
    $fileName = 'cert_' . $activity->activity_id . '_' . time() . '.' . $extension;
    
    // 💾 บันทึกไฟล์ไปยัง storage/app/public/certificates/
    $path = $file->storeAs(
        'certificates',  // โฟลเดอร์
        $fileName,       // ชื่อไฟล์
        'public'         // disk (storage/app/public/)
    );
    
    // 🗄️ บันทึก path ลงฐานข้อมูล
    $activity->certificate_img = $path; // เช่น: "certificates/cert_1_1234567890.jpg"
    $activity->position_x = $request->position_x;
    $activity->position_y = $request->position_y;
    $activity->save();

    return redirect()
    ->route('manage-activities')
    ->with('success', 'อัปโหลดใบประกาศสำเร็จ: ' . $fileName);
    
    }


    public function editActivity($id)
    {
        $activity = Activity::findOrFail($id);
        $agencies = Agency::with('branches')->get();
        $branches = Branches::all();
        
        return view('actitvity.EditActivity', compact('activity', 'agencies', 'branches'));
    }

    public function updateActivity(Request $request, $id)
{
    $request->validate([
        'activity_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'agency_id' => 'nullable|exists:agency,agency_id',
        'branch_id' => 'nullable|exists:branches,branch_id',
        'certificate_img' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'position_x' => 'nullable|integer|min:0|max:1000',
        'position_y' => 'nullable|integer|min:0|max:1000',
    ]);

    $activity = Activity::findOrFail($id);
    
    // Update basic info
    $activity->activity_name = $request->activity_name;
    $activity->agency_id = $request->agency_id;
    $activity->branch_id = $request->branch_id;
    $activity->start_date = $request->start_date;
    $activity->end_date = $request->end_date;
    $activity->is_active = $request->has('is_active') ? 1 : 0;
    
    // Update certificate if new file uploaded
    if ($request->hasFile('certificate_img')) {
        // Delete old file
        if ($activity->certificate_img) {
            Storage::disk('public')->delete($activity->certificate_img);
        }
        
        // Save new file
        $file = $request->file('certificate_img');
        $extension = $file->getClientOriginalExtension();
        $fileName = 'cert_' . $activity->activity_id . '_' . time() . '.' . $extension;
        $path = $file->storeAs('certificates', $fileName, 'public');
        
        $activity->certificate_img = $path;
    }
    
    // Update position if provided
    if ($request->filled('position_x') && $request->filled('position_y')) {
        $activity->position_x = $request->position_x;
        $activity->position_y = $request->position_y;
    }
    
    $activity->save();

    return redirect()
        ->route('manage-activities')
        ->with('success', 'แก้ไขกิจกรรมเรียบร้อยแล้ว');
    }

    public function showAddCertificate($id)
    {
        $activity = Activity::findOrFail($id);
        return view('actitvity.Importimg', compact('activity'));
    }

    public function deleteActivity($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->participants()->delete();
        // Delete certificate image if exists
        if ($activity->certificate_img) {
            Storage::disk('public')->delete($activity->certificate_img);
        }
        
        $activity->delete();
        
        return redirect()->route('manage-activities')->with('success', 'กิจกรรมถูกลบเรียบร้อยแล้ว');
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
            
            // ✅ เพิ่ม: เช็คก่อน import
            Log::info('🚀 เริ่ม import', ['activity_id' => $activity->activity_id]);
            
            $import = new ParticipantsImport($activity->activity_id);
            Excel::import($import, $file);
            
            // ✅ เพิ่ม: เช็คหลัง import
            Log::info('✅ Import เสร็จแล้ว', ['imported_count' => $import->getImportedCount()]);
            
            // Count from database
            $participantCount = Participant::where('activity_id', $activity->activity_id)->count();
            
            Log::info('📊 Database count:', ['total_participants' => $participantCount]);
            
            return back()->with('success', "อัพโหลดรายชื่อผู้เข้าร่วมเรียบร้อยแล้ว (จำนวน {$participantCount} คน)");
            
        } catch (\Exception $e) {
            Log::error('❌ Participant import error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function addParticipant(Request $request, $activityId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $participant = new Participant();
        $participant->activity_id = $activityId;
        $participant->name = $request->name;
        $participant->certificate_token = Participant::generateCertificateToken();
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
        $participant = Participant::findOrFail($id);
        $participant->delete();

        return back()->with('success', 'ลบผู้เข้าร่วมเรียบร้อยแล้ว');
    }

        public function showCertificates($id)
    {
        $activity = Activity::with(['participants.downloadLogs', 'agency'])->findOrFail($id);
        return view('certificate.activity_certificates', compact('activity'));
    }

    
    public function getBranchesByAgency($agencyId)
    {
        $branches = Branches::where('agency_id', $agencyId)->get();
        return response()->json($branches);
    }
}