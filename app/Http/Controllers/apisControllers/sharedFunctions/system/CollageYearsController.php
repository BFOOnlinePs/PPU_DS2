<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\system;

use App\Http\Controllers\Controller;
use App\Models\SemesterCourse;
use Illuminate\Http\Request;

class CollageYearsController extends Controller
{
    public function getCollageYears(){
        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();

        return response()->json([
            'collage_years' => $years
        ]);
    }
}
