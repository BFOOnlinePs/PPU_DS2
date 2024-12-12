<?php

namespace App\Http\Controllers\apisControllers\training_supervisor;

use App\Http\Controllers\Controller;
use App\Models\FieldVisitsModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingSupervisorVisitsController extends Controller
{
    public function getUserVisits()
    {
        $user = auth()->user();

        $visits = FieldVisitsModel::join('users', 'fv_supervisor_id', 'users.u_id')
            ->join('companies', 'fv_company_id', 'companies.c_id')
            ->where('fv_supervisor_id', $user->u_id)
            ->orderBy('fv_id', 'desc')
            ->select('field_visits.*', 'users.name as supervisor_name', 'companies.c_name as company_name')
            // ->paginate(6);
            ->get();

        // $visits = FieldVisitsModel::with(['supervisor:u_id,name', 'company:c_id,c_name'])
        //     ->where('fv_supervisor_id', $user->u_id)
        //     ->orderBy('fv_id', 'desc')
        //     ->paginate(6);

        // show students names from fv_student_id that is as json
        foreach ($visits as $visit) {
            $students_ids = json_decode($visit->fv_student_id);
            $visit->students_names = User::whereIn('u_id', $students_ids)->pluck('name');
        }

        return response()->json([
            'status' => true,
            // 'pagination' => [
            //     'current_page' => $visits->currentPage(),
            //     'last_page' => $visits->lastPage(),
            //     'per_page' => $visits->perPage(),
            //     'total_items' => $visits->total(),
            // ],
            // 'visits' => $visits->items(),
            'visits' => $visits,
        ]);
    }

    public function addVisit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'company_id' => 'required|exists:companies,c_id',
            'visiting_place' => 'required',
            'visit_duration' => 'required',
            'visit_time' => 'nullable',
            'notes' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $user = auth()->user();
        $visit = new FieldVisitsModel();
        $visit->fv_student_id = $request->input('student_id');
        $visit->fv_supervisor_id = $user->u_id;
        $visit->fv_company_id = $request->input('company_id');
        $visit->fv_visiting_place = $request->input('visiting_place');
        $visit->fv_visit_duration = $request->input('visit_duration');
        $visit->fv_vistit_time = Carbon::now();
        $visit->fv_notes = $request->input('notes');

        if ($visit->save()) {
            return response()->json([
                'status' => true,
                'message' => trans('messages.visit_added'),
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => trans('messages.visit_not_added'),
        ]);
    }
}
