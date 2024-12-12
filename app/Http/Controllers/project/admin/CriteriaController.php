<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use App\Models\CitiesModel;
use App\Models\CriteriaModel;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $data = CriteriaModel::get();
        return view('project.admin.criteria.index', ['data'=>$data]);
    }

    public function add()
    {
        return view('project.admin.criteria.add');
    }

    public function create(Request $request)
    {
        $data = new CriteriaModel();
        $data->c_criteria_name = $request->c_criteria_name;
        $data->c_max_score = $request->c_max_score;
        if ($data->save()){
            return redirect()->route('admin.criteria.index')->with(['success' => 'تم انشاء المعيار بنجاح']);
        }
    }

    public function edit($id)

    {
        $data = CriteriaModel::where('c_id',$id)->first();
        return view('project.admin.criteria.edit', ['data'=>$data]);
    }

    public function update(Request $request)
    {
        $data = CriteriaModel::where('c_id',$request->id)->first();
        $data->c_criteria_name = $request->c_criteria_name;
        $data->c_max_score = $request->c_max_score;
        if ($data->save()){
            return redirect()->route('admin.criteria.index')->with(['success' => 'تم انشاء المعيار بنجاح']);
        }
    }
}
