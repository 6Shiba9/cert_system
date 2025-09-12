<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Branches;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;

class ActivityController extends Controller
{
    public function showCreateActivity()
    {
        $Agency = Agency::with('branches')->get();
        $Branches = Branches::all();
        return view('actitvity.CreateActivity' , compact('Agency', 'Branches'));
    }

    public function saveActivity(request $request)
    {
    //     try {
    //         $validatedData = $request->validate([
    //             'activity_name' => 'required|string|max:255',
    //             'agency_id' => 'required',
    //             'branch_id' => 'required',
    //             'date_start' => 'required|date',
    //             'date_end' => 'required|date',
    //             'user_id' => 'required',
    //         ]);
    // }
    }
}