<?php

namespace App\Http\Controllers\project\supervisor_assistants;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\MajorSupervisor;
use App\Models\SupervisorAssistant;
use App\Models\User;
use Illuminate\Http\Request;

class MajorsController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);
        $supervisor_assistants = SupervisorAssistant::where('sa_assistant_id' , $id)
                                                    ->pluck('sa_supervisor_id')
                                                    ->toArray();
        $supervisor_majors_id = MajorSupervisor::whereIn('ms_super_id' , $supervisor_assistants)
                                            ->pluck('ms_major_id')
                                            ->toArray();
        $majors = Major::whereNotIn('m_id', $supervisor_majors_id)->get();
        $data = MajorSupervisor::whereIn('ms_super_id' , $supervisor_assistants)->get();
        return view('project.assistant.majors.index' , ['user' => $user , 'majors' => $majors , 'data' => $data]);
    }
}
