<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Branches;
use App\Models\User;
use App\Models\Activity;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;

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
        try {
            $validatedData = $request->validate([
                'activity_name' => 'required|string|max:255',
                'agency_id' => 'required|exists:agency,agency_id',
                'branch_id' => 'required|exists:branches,branch_id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'certificate_img' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
                'position_x' => 'nullable|numeric|min:0|max:1000',
                'position_y' => 'nullable|numeric|min:0|max:1000',
            ]);

            // Handle certificate image upload
            $certificateImg = null;
            if ($request->hasFile('certificate_img')) {
                $certificateImg = $request->file('certificate_img')->store('certificates', 'public');
            }

            Activity::create([
                'activity_name' => $validatedData['activity_name'],
                'agency_id' => $validatedData['agency_id'],
                'branch_id' => $validatedData['branch_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'certificate_img' => $certificateImg,
                'position_x' => $validatedData['position_x'] ?? 0,
                'position_y' => $validatedData['position_y'] ?? 0,
                'user_id' => auth()->user()->user_id,
                'access_code' => Activity::generateAccessCode(),
                'is_active' => true,
            ]);

            return redirect()->route('manage-activities')->with('success', 'กิจกรรมถูกเพิ่มเรียบร้อยแล้ว');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function showManageActivities()
    {
        $activities = Activity::with(['agency', 'branch', 'user', 'participants'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('actitvity.ManageActivities', compact('activities'));
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
        $activity = Activity::findOrFail($id);
        
        try {
            $validatedData = $request->validate([
                'activity_name' => 'required|string|max:255',
                'agency_id' => 'required|exists:agency,agency_id',
                'branch_id' => 'required|exists:branches,branch_id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'certificate_img' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
                'position_x' => 'nullable|numeric|min:0|max:1000',
                'position_y' => 'nullable|numeric|min:0|max:1000',
                'is_active' => 'boolean',
            ]);

            // Handle certificate image upload
            if ($request->hasFile('certificate_img')) {
                // Delete old image if exists
                if ($activity->certificate_img) {
                    Storage::disk('public')->delete($activity->certificate_img);
                }
                $validatedData['certificate_img'] = $request->file('certificate_img')->store('certificates', 'public');
            }

            $activity->update($validatedData);

            return redirect()->route('manage-activities')->with('success', 'กิจกรรมถูกแก้ไขเรียบร้อยแล้ว');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function deleteActivity($id)
    {
        $activity = Activity::findOrFail($id);
        
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
            
            // Log file details for debugging
            Log::info('File upload details:', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension()
            ]);
            
            // Import participants
            $import = new ParticipantsImport($activity->activity_id);
            Excel::import($import, $file);
            
            // Count imported participants
            $participantCount = Participant::where('activity_id', $activity->activity_id)->count();
            Log::info('Participants imported successfully', [
                'activity_id' => $activity->activity_id,
                'total_participants' => $participantCount
            ]);
            
            return back()->with('success', "อัพโหลดรายชื่อผู้เข้าร่วมเรียบร้อยแล้ว (จำนวน {$participantCount} คน)");
            
        } catch (\Exception $e) {
            Log::error('Participant import error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์: ' . $e->getMessage());
        }
    }

    public function getBranchesByAgency($agencyId)
    {
        $branches = Branches::where('agency_id', $agencyId)->get();
        return response()->json($branches);
    }
}