<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Branches;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
class AgencyController extends Controller
{
    public function showagency()
    {
        $agency = Agency::with('branches')->get();
        $branches = Branches::all();
        return view('advancedmanagent.Agency', compact('agency', 'branches'));

    }


    public function createagency(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'agency_name' => 'required|string|max:255',
            ]);

            Agency::create([
                'agency_name' => $validatedData['agency_name'],
            ]);

            return back()->with('success', 'ผู้ใช้งานถูกเพิ่มเรียบร้อยแล้ว.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function updateagency(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);

        $validatedData = $request->validate([
            'agency_name' => 'required|string|max:255',
        ]);

        $agency->agency_name = $validatedData['agency_name'];
        $agency->save();

        return redirect()->route('agency')->with('success', 'ผู้ใช้งานถูกแก้ไขเรียบร้อยแล้ว.');
    }


    public function deleteagency($id)
    {
       $agency = Agency::find($id);
        if ($agency) {

            $agency->branches()->delete(); 

            $agency->delete();

            return redirect()->route('agency')->with('success', 'ลบสําเร็จ');
        }

        return redirect()->route('agency')->with('error', 'ไม่พบข้อมูลที่ต้องการลบ.');
    }

// branch controller

    public function createbranch(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'branch_name' => 'required|string|max:255',
                'agency_id' => 'required',
            ]);

            Branches::create([
                'branch_name' => $validatedData['branch_name'],
                'agency_id' => $validatedData['agency_id'],
            ]);

            return back()->with('success', 'ผู้ใช้งานถูกเพิ่มเรียบร้อยแล้ว.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }


    public function deletebranch($id)
    {
        $branch = Branches::find($id);
        if ($branch) {
            $branch->delete();
            return redirect()->route('agency')->with('success', 'ลบสําเร็จ');
        }
    }

    public function updatebranch(Request $request, $id)
    {
        $branch = Branches::findOrFail($id);

        $validatedData = $request->validate([
            'branch_name' => 'required|string|max:255',
            'agency_id' => 'required',
        ]);

        $branch->branch_name = $validatedData['branch_name'];
        $branch->agency_id = $validatedData['agency_id'];
        $branch->save();

        return redirect()->route('agency')->with('success', 'ผู้ใช้งานถูกแก้ไขเรียบร้อยแล้ว.');
    }
}