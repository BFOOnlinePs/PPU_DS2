<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\announcements;

class announcementController extends Controller
{
    public function index(){

        $data=announcements::with('users')->get();
        return view('project.admin.announcements.index', ['data' => $data]);
    }

    public function announcementSearch(Request $request){

        $data = announcements::where('a_title','like','%'.$request->search.'%')->get();
        return response()->json([
            'success'=>'true',
            'view'=>view('project.admin.announcements.ajax.announcementsList',['data'=>$data])->render()
        ]);
    }

    public function create(Request $request){
         $announcements=new announcements();
        if ($request->hasFile('a_image')) {
            $file = $request->file('a_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->storeAs('uploads/announcements', $filename, 'public');
            $announcements->a_image=$filename;
        }
        else {
            $announcements->a_image="";

        }

        $announcements->a_title=$request->a_title;
        $announcements->a_content=$request->a_content;
        $announcements->a_added_by=auth()->user()->u_id;
        if($announcements->save()){
        $data=announcements::get();
        return response()->json([
            'success'=>'true',
            'view'=>view('project.admin.announcements.ajax.announcementsList',['data'=>$data])->render()
        ]);
    }

    }
   public function updateStutas(Request $request){
    // $requestencode= json_encode($request);
    //return $request->selected_a_status;
    $announcement=announcements::where('a_id',$request->selected_a_id)->first();
    $announcement->a_status=$request->selected_a_status;
    if($announcement->save()){
        $data=announcements::with('users')->get();
        return response()->json([
                'success'=>'true',
                'view'=>view('project.admin.announcements.ajax.announcementsList',['data'=>$data])->render()
            ]);
    }

   }
   public function update(Request $request){
// return $request->a_image;
    $announcement=announcements::where('a_id',$request->a_id)->first();
    $announcement->a_title=$request->a_title;
    $announcement->a_content=$request->a_content;
    // $x=$request->hasFile('a_image');
    // return $announcement->a_image;
    if($request->hasFile('a_image') && $announcement->a_image !=0 ) {
        $file = $request->file('a_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->storeAs('uploads/announcements', $filename, 'public');
        $announcement->a_image=$filename;
    }
    else {
        $announcement->a_image=0;

    }
    if($announcement->save()){
        $data=announcements::with('users')->get();
        return response()->json([
                'success'=>'true',
                'view'=>view('project.admin.announcements.ajax.announcementsList',['data'=>$data])->render()
            ]);
    }

   }
   public function edit($id){

    $data=announcements::where('a_id',$id)->first();
    return view('project.admin.announcements.edit', ['data' => $data]);
}
}
