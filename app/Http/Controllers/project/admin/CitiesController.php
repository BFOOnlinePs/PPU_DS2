<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use App\Models\CitiesModel;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(){
        $data = CitiesModel::get();
        return view('project.admin.cities.index',['data'=>$data]);
    }

    public function create(Request $request){
        $data = new CitiesModel();
        $data->city_name_ar = $request->city_name_ar;
        $data->city_name_en = $request->city_name_en;
        $data->city_description = $request->city_description;
        if ($data->save()){
            return redirect()->route('admin.cities.index')->with(['success'=>'تم اضافة المدينة بنجاح']);
        }
        else{
            return redirect()->route('admin.cities.index')->with(['fail'=>'هناك خلل ما لم يتم اضافة المدينة']);
        }
    }

    public function update(Request $request){
        $data = CitiesModel::where('id',$request->city_id)->first();
        $data->city_name_ar = $request->city_name_ar;
        $data->city_name_en = $request->city_name_en;
        $data->city_description = $request->city_description;
        if ($data->save()){
            return redirect()->route('admin.cities.index')->with(['success'=>'تم تعديل البيانات بنجاح']);
        }
        else{
            return redirect()->route('admin.cities.index')->with(['fail'=>'هناك خلل ما لم يتم تعديل البيانات']);
        }
    }
}
