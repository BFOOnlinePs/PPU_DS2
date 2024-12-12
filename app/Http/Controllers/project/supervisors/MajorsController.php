<?php

namespace App\Http\Controllers\project\supervisors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MajorSupervisor;
use App\Models\Major;

class MajorsController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);
        $supervisor_majors_id = MajorSupervisor::where('ms_super_id' , $id)
                                            ->pluck('ms_major_id')
                                            ->toArray();
        $majors = Major::whereNotIn('m_id', $supervisor_majors_id)->get();
        $data = MajorSupervisor::where('ms_super_id' , $id)->get();
        return view('project.supervisor.majors.index' , ['user' => $user , 'majors' => $majors , 'data' => $data]);
    }
}
