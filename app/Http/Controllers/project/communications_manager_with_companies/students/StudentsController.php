<?php

namespace App\Http\Controllers\project\communications_manager_with_companies\students;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index()
    {
        $majors = Major::get();
        $students = User::where('u_role_id' , 2)
                        ->get();
        return view('project.communications_manager_with_companies.students.index' , ['majors' => $majors , 'students' => $students]);
    }
    public function search(Request $request)
    {
        $students = null;
        if($request->m_id != null) {
            $students = User::where('u_role_id' , 2)
                        ->where('name' , 'like' , '%' . $request->word_to_search . '%')
                        ->where('u_major_id' , $request->m_id)
                        ->get();
        }
        else {
            $students = User::where('u_role_id' , 2)
                        ->where('name' , 'like' , '%' . $request->word_to_search . '%')
                        ->get();
        }
        $html = view('project.admin.users.ajax.supervisorStudentsList' , ['students' => $students])->render();
        return response()->json(['html' => $html]);
    }
}
