<?php

namespace App\Http\Controllers\project\supervisors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MajorSupervisor;
use App\Models\Major;

class StudentsController extends Controller
{
    public function index($id , $ms_major_id = null)
    {
        $user = User::find($id);
        $ms_majors_id = MajorSupervisor::where('ms_super_id' , $id)
                                    ->pluck('ms_major_id')
                                    ->toArray();
        $students = User::where('u_role_id' , 2)
                        ->whereIn('u_major_id' , $ms_majors_id)
                        ->get();
        $majors = Major::whereIn('m_id' , $ms_majors_id)
                        ->whereNot('m_id' , $ms_major_id)
                        ->get();
        $major = Major::find($ms_major_id);
        return view('project.supervisor.students.index' , ['user' => $user , 'students' => $students , 'majors' => $majors , 'major' => $major]);
    }
}
