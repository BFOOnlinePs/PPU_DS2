<?php

namespace App\Http\Controllers\project\supervisor_assistants;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\MajorSupervisor;
use App\Models\SupervisorAssistant;
use App\Models\User;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index($ms_major_id = null)
    {
        $user = User::find(auth()->user()->u_id);
        $supervisor_assistants = SupervisorAssistant::where('sa_assistant_id' , auth()->user()->u_id)
                                                    ->pluck('sa_supervisor_id')
                                                    ->toArray();
        $ms_majors_id = MajorSupervisor::whereIn('ms_super_id' , $supervisor_assistants)
                                    ->pluck('ms_major_id')
                                    ->toArray();
        $majors = null;
        $students = null;
        if($ms_major_id != null)
        {
            $majors = Major::whereIn('m_id' , $ms_majors_id)
                            ->whereNot('m_id' , $ms_major_id)
                            ->get();
            $students = User::where('u_role_id' , 2)
                            ->where('u_major_id' , $ms_major_id)
                            ->get();
        }
        else {
            $majors = Major::whereIn('m_id' , $ms_majors_id)
                            ->get();
            $students = User::where('u_role_id' , 2)
                            ->whereIn('u_major_id' ,  $ms_majors_id)
                            ->get();
        }
        $major = Major::find($ms_major_id);
        return view('project.assistant.students.index' , ['students' => $students , 'majors' => $majors , 'major' => $major , 'user' => $user]);
    }
    public function search(Request $request)
    {
        $students = null;
        if($request->m_id != null) {
            $students = User::where('name' , 'like' , '%' . $request->word_to_search . '%')
                            ->where('u_role_id' , 2)
                            ->where('u_major_id', $request->m_id)
                            ->get();
        }
        else {
            $supervisors = SupervisorAssistant::where('sa_assistant_id' , auth()->user()->u_id)
                                                ->pluck('sa_supervisor_id')
                                                ->toArray();
            $majors_supervisors = MajorSupervisor::whereIn('ms_super_id' , $supervisors)
                                                ->pluck('ms_major_id')
                                                ->toArray();
            $students = User::where('name' , 'like' , '%' . $request->word_to_search . '%')
                            ->where('u_role_id' , 2)
                            ->whereIn('u_major_id', $majors_supervisors)
                            ->get();
        }
        $html = view('project.assistant.students.ajax.studentsList' , ['students' => $students])->render();
        return response()->json(['html' => $html]);
    }
}
