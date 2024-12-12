<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\cities;

use App\Http\Controllers\Controller;
use App\Models\CitiesModel;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function getCities(){
        $cities = CitiesModel::get();

        return response()->json([
            'status' => true,
            'cities' => $cities
        ]);
    }
}
