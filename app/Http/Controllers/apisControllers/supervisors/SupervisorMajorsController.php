<?php

namespace App\Http\Controllers\apisControllers\supervisors;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\MajorSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorMajorsController extends Controller
{
    // for the supervisor who logged in
    // majors with number of students in each major
    public function getSupervisorsMajors()
    {
        $supervisorId = auth()->user()->u_id;
        $supervisorMajorsIdList = MajorSupervisor::where('ms_super_id', $supervisorId)->pluck('ms_major_id');
        $supervisorMajorsList = Major::whereIn('m_id', $supervisorMajorsIdList)->withCount('majorStudent')->get();

        return response()->json([
            'status' => true,
            'majors' => $supervisorMajorsList,
        ]);
    }
}
