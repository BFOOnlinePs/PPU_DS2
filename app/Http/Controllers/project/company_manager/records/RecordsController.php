<?php

namespace App\Http\Controllers\project\company_manager\records;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\StudentAttendance;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class RecordsController extends Controller
{
    public function index()
    {
        return view('project.company_manager.records.index');
    }
    public function search(Request $request)
    {
        $company_id = Company::where('c_manager_id', auth()->user()->u_id)->pluck('c_id')->first();
        $students_company = StudentCompany::where('sc_company_id', $company_id)->pluck('sc_id')->toArray();
        $users_id = User::where('name', 'like', '%' . $request->searchByName . '%')->pluck('u_id')->toArray();
        $records = StudentAttendance::whereIn('sa_student_company_id', $students_company)
                    ->whereIn('sa_student_id' , $users_id)
                    ->whereBetween(DB::raw('DATE(sa_in_time)'), [$request->from, $request->to])
                    ->orderBy('created_at', 'desc')
                    ->get();
        $view = view('project.company_manager.records.includes.recordsList' , ['records' => $records])->render();
        return response()->json(['html' => $view]);
    }

}
