<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class ManageUserController extends Controller
{
    public function showmanageUser()
    {
        $users = User::all();
        return view('advancedmanagent.ManageUser', compact('users'));
    }

    public function createuser(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string',
                'role' => 'required|in:admin,manager',
            ]);

            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' =>Hash::make($validatedData['password']), // Hash the password$validatedData['password'],
                'role' => $validatedData['role'],
            ]);

            return back()->with('success', 'ผู้ใช้งานถูกเพิ่มเรียบร้อยแล้ว.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
    public function edituser($id)
    {
        $user = User::findOrFail($id);
        return view('advancedmanagent.Edituser', compact('user'));
    }


    public function updateuser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6', // ✅ เปลี่ยนเป็น nullable และเพิ่ม min
            'role' => 'required|in:admin,manager',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];

        // ✅ อัปเดต password เฉพาะเมื่อมีการกรอกเท่านั้น
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('ManageUser')->with('success', 'แก้ไขผู้ใช้งานเรียบร้อยแล้ว');
    }

    /**
     * Delete the user with the given ID
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteuser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('ManageUser')->with('success', 'User deleted successfully.');
        }

        return redirect()->route('ManageUser')->with('error', 'ไม่พบผู้ใช้งานที่ต้องการลบ.');
    }
}
