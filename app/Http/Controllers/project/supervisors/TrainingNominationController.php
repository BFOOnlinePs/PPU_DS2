<?php

namespace App\Http\Controllers\project\supervisors;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class TrainingNominationController extends Controller
{
    public function index(){
        $company = Company::get();
        return view('project.supervisor.training_nominations.index',['company'=>$company]);
    }
}
