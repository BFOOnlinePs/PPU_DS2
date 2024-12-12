<?php

namespace App\Http\Controllers\project\students\personal_profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Major;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class PersonalProfileController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->u_id);
        $role_name = Role::find($user->u_role_id);
        $major_id = Major::where('m_id' , $user->u_major_id)->first();
        $role_id = Role::where('r_id' , $user->u_role_id)->first();
        $roles = Role::get();
        $majors = Major::get();
        return view('project.student.personal_profile.index' , ['user' => $user , 'role_name' => $role_name->r_name , 'major_id' => $major_id , 'roles' => $roles , 'majors' => $majors , 'role_id' => $role_id]);
    }

    public function add_sv_to_student(Request $request){
        $data = User::where('u_id',$request->id)->first();
        if ($request->hasFile('cv_file')) {
            $file = $request->file('cv_file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension; // Unique filename
            $file->storeAs('uploads', $filename, 'public');
            $data->u_cv = $filename;
        }
        if ($data->save()){
            return redirect()->route('students.personal_profile.index')->with(['success'=>'تم اضافة ال cv بنجاح']);

        }
        else{
            return redirect()->route('students.personal_profile.index')->with(['fail'=>'هناك خلل ما']);
        }
    }

    public function update_password(Request $request){
        $data = User::find(auth()->user()->u_id);
        $data->password = bcrypt($request->password);
        if($data->save()){
            return redirect()->back();
        }
    }

}
