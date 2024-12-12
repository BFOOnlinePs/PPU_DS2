<?php

namespace App\Http\Controllers\apisControllers\program_coordinator\majors;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class ProgramCoordinatorMajorsController extends Controller
{
    // all majors with number of students in each major
    public function getAllMajors()
    {
        $MajorsList = Major::withCount('majorStudent')->get();

        return response()->json([
            'status' => true,
            'majors' => $MajorsList,
        ]);
    }
}
