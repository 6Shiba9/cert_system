<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class ManageUserController extends Controller
{
    /**
     * แสดงหน้าจัดการผู้ใช้งาน
     */
    public function showmanageUser()
    {
        $users = User::with('agency')->withCount('activities')->get();
        $agencies = Agency::all();
        return view('advancedmanagent.ManageUser', compact('users', 'agencies'));
    }

    /**
     * สร้างผู้ใช้งานใหม่
     */
    public function createuser(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'agency_id' => 'required|exists:agency,agency_id',
                'role' => 'required|in:admin,manager',
            ], [
                'name.required' => 'กรุณากรอกชื่อ',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
                'agency_id.required' => 'กรุณาเลือกหน่วยงาน',
                'agency_id.exists' => 'หน่วยงานที่เลือกไม่ถูกต้อง',
                'role.required' => 'กรุณาเลือกบทบาท',
                'role.in' => 'บทบาทที่เลือกไม่ถูกต้อง',
            ]);

            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'agency_id' => $validatedData['agency_id'],
                'role' => $validatedData['role'],
            ]);

            return back()->with('success', 'เพิ่มผู้ใช้งานเรียบร้อยแล้ว');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * แสดงหน้าแก้ไขผู้ใช้งาน
     */
    public function edituser($id)
    {
        $user = User::findOrFail($id);
        $agencies = Agency::all();
        return view('advancedmanagent.Edituser', compact('user', 'agencies'));
    }

    /**
     * อัปเดตข้อมูลผู้ใช้งาน
     */
    public function updateuser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6',
            'agency_id' => 'required|exists:agency,agency_id',
            'role' => 'required|in:admin,manager',
        ], [
            'name.required' => 'กรุณากรอกชื่อ',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
            'agency_id.required' => 'กรุณาเลือกหน่วยงาน',
            'agency_id.exists' => 'หน่วยงานที่เลือกไม่ถูกต้อง',
            'role.required' => 'กรุณาเลือกบทบาท',
            'role.in' => 'บทบาทที่เลือกไม่ถูกต้อง',
        ]);

        // อัปเดตข้อมูลพื้นฐาน
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->agency_id = $validatedData['agency_id'];
        $user->role = $validatedData['role'];

        // อัปเดต password เฉพาะเมื่อมีการกรอกเท่านั้น
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('ManageUser')->with('success', 'แก้ไขข้อมูลผู้ใช้งานเรียบร้อยแล้ว');
    }

 /**
     * ลบผู้ใช้งานพร้อมกิจกรรมทั้งหมดที่สร้าง
     */
    public function deleteuser($id)
    {
        $user = User::with('activities.participants')->find($id);

        if (!$user) {
            return redirect()->route('ManageUser')
                ->with('error', 'ไม่พบผู้ใช้งานที่ต้องการลบ');
        }

        // 🔒 ห้ามลบ Admin หลัก (ID 1)
        if ($id == 1) {
            return redirect()->route('ManageUser')
                ->with('error', 'ไม่สามารถลบผู้ดูแลระบบหลักได้');
        }

        // 🔒 ป้องกันไม่ให้ลบตัวเอง
        if (auth()->check() && auth()->id() == $id) {
            return redirect()->route('ManageUser')
                ->with('error', 'คุณไม่สามารถลบบัญชีของตนเองได้');
        }

        // ✅ ลบกิจกรรมทั้งหมดที่ผู้ใช้สร้าง
        foreach ($user->activities as $activity) {
            // ลบผู้เข้าร่วมทั้งหมดในกิจกรรม
            $activity->participants()->delete();
            
            // ลบรูปใบประกาศ
            if ($activity->certificate_img) {
                Storage::disk('public')->delete($activity->certificate_img);
            }
            
            // ลบกิจกรรม
            $activity->delete();
        }

        // ✅ ลบผู้ใช้งาน
        $user->delete();

        return redirect()->route('ManageUser')
            ->with('success', 'ลบผู้ใช้งานและกิจกรรมที่เกี่ยวข้องเรียบร้อยแล้ว');
    }
}