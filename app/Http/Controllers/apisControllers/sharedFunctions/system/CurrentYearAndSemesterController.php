<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\system;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class CurrentYearAndSemesterController extends Controller
{
    public function getCurrentYearAndSemester(){
        $system_settings = SystemSetting::first();

        $year = $system_settings->ss_year;
        $semester = $system_settings->ss_semester_type;

        return response()->json([
            'current_year' => $year,
            'current_semester' => $semester,
        ]);
    }
}
