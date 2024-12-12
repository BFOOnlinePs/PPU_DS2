<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Major;
use App\Models\User;
use App\Models\MajorSupervisor;

class MajorsController extends Controller
{



    public function index()
    {
        $data = Major::with('majorSupervisors.users')->get();
        $superVisors=User::where('u_role_id',3)->where('u_status',1)->get();
        return view('project.admin.majors.index',['data'=>$data , 'superVisors'=>$superVisors]);
    }
    public function create(Request $request){
        $major=new Major;
        $major->m_name=$request->m_name;
        $major->m_description=$request->m_description;
        $major->m_reference_code=$request->m_reference_code;
        if ($major->save()) {
            $data = Major::with('majorSupervisors.users')->get();
            $superVisors=User::where('u_role_id',3)->get();
            $majorSuper = MajorSupervisor::with('users')->get();
            return response()->json([
                'success'=>'true',
                'view'=>view('project.admin.majors.ajax.majorsList',['data'=>$data, 'superVisors'=>$superVisors,'majorSuper'=>$majorSuper])->render()

            ]);
        }

    }
    public function addSuperVisor(Request $request){
       $superVisor=$request->superVisor;
       for($i = 0 ; $i < count($superVisor) ; $i++){
        $majorSuperVisor=new MajorSupervisor;
        $majorSuperVisor->ms_super_id=$superVisor[$i];
        $majorSuperVisor->ms_major_id=$request->selected_m_id;
    
        $majorSuperVisor->save();
     
    }
    $data = Major::with('majorSupervisors.users')->get();
    $superVisors=User::where('u_role_id',3)->where('u_status',1)->get();
      return response()->json([
          'success'=>'true',
          'view'=>view('project.admin.majors.ajax.majorsList',['data'=>$data, 'superVisors'=>$superVisors ])->render()
      ]);


    }
    public function updateSuperVisor(Request $request){
        $superVisor=$request->superVisor;
        $majorSuperVisor= MajorSupervisor::where('ms_major_id',$request->selected_m_id)->get();
        $majorSuperVisor1 = MajorSupervisor::where('ms_major_id', $request->selected_m_id)->pluck('ms_super_id')->toArray();
        $collection1 = collect($superVisor);
        $collection2 = collect($majorSuperVisor1);
        $commonItems = $collection1->intersect($collection2)->values()->all();
        $uniqueCollection1 = $collection1->diff($collection2)->values()->all();
        $uniqueCollection2 = $collection2->diff($collection1)->values()->all();
        if(count($collection1) == count($collection2)){
            for($i = 0 ; $i<count($uniqueCollection1); $i++)
            if($uniqueCollection1[$i] != $majorSuperVisor1[$i])
            {
            $majorSuperVisor[$i]->ms_super_id=$uniqueCollection1[$i];
            $majorSuperVisor[$i]->save();

        }
    }
    else {
            for($i = 0 ; $i<count($uniqueCollection1) ; $i++){
                        $majorSuperVisor=new MajorSupervisor;
                        $majorSuperVisor->ms_super_id=$uniqueCollection1[$i];
                        $majorSuperVisor->ms_major_id=$request->selected_m_id;
                        $majorSuperVisor->save();

                            }
                            for($i = 0 ; $i<count($uniqueCollection2) ; $i++)
                                {
                                    $majorSuperVisor3 = MajorSupervisor::where('ms_major_id', $request->selected_m_id)
                                    ->where('ms_super_id', $uniqueCollection2[$i])
                                    ->get();

                            $majorSuperVisor3[$i]->delete();

                                }

    }

             $data = Major::with('majorSupervisors.users')->get();
             $superVisors=User::where('u_role_id',3)->where('u_status',1)->get();
             return response()->json([
                   'success'=>'true',
                   'view'=>view('project.admin.majors.ajax.majorsList',['data'=>$data, 'superVisors'=>$superVisors ])->render()
               ]);



    }
    public function update(Request $request){
        $major = Major::Where('m_id',$request->m_id)->first();
        $major->m_name=$request->m_name;
        $major->m_description=$request->m_description;
        $major->m_reference_code=$request->m_reference_code;
        if ($major->save()) {
            $data = Major::with('majorSupervisors.users')->get();
            $superVisors=User::where('u_role_id',3)->get();
            $majorSuper = MajorSupervisor::with('users')->get();
            return response()->json([
                'success'=>'true',
                'view'=>view('project.admin.majors.ajax.majorsList',['data'=>$data, 'superVisors'=>$superVisors ,'majorSuper'=>$majorSuper])->render()
            ]);
        }

    }
    public function search(Request $request){
       // $data = Major::where('m_name','like','%'.$request->search.'%')->get();
        $data = Major::with('majorSupervisors.users')->where('m_name','like','%'.$request->search.'%')->get();
        $superVisors=User::where('u_role_id',3)->where('u_status',1)->get();
        return response()->json([
            'success'=>'true',
            'view'=>view('project.admin.majors.ajax.majorsList',['data'=>$data,'superVisors'=>$superVisors])->render()
        ]);

    }

    function checkMajorCode(Request $request){
        $data = Major::where('m_reference_code',$request->search)->first();
        return response()->json([
            'success'=>'true',
            'data'=>$data
        ]);
    }

}
