<?php

namespace App\Http\Controllers\apisControllers\students\preferences;

use App\Http\Controllers\Controller;
use App\Models\StudentPreferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentPreferencesController extends Controller
{

    public function getCurrentStudentPreferences()
    {
        $student = auth()->user();

        $student_preferences = StudentPreferences::where('sp_student_id', $student->u_id)->first();

        if ($student_preferences) {
            return response()->json([
                'status' => true,
                'student_preference' => $student_preferences,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'messages' => trans('messages.no_preferences_yet'),
            ]);
        }
    }

    public function addEditStudentPreferences(Request $request)
    {
        $student = auth()->user();

        $student_preferences = StudentPreferences::where('sp_student_id', $student->u_id)->first();

        if ($student_preferences) {
            // edit
            $student_preferences->update([
                'sp_cities' => $request->input('cities'),
                'sp_companies' => $request->input('companies'),
                'sp_company_type' =>  $request->input('company_type'),
                'sp_notes' => $request->input('notes'),
            ]);

            return response()->json([
                'status' => true,
                'message' => trans('messages.student_preferences_updated'),
                'preferences' => $student_preferences,
            ]);
        } else {
            // add
            $student_preferences = new StudentPreferences();
            $student_preferences->sp_student_id = $student->u_id;
            $student_preferences->sp_cities = $request->input('cities');
            $student_preferences->sp_companies = $request->input('companies');
            $student_preferences->sp_company_type = $request->input('company_type');
            $student_preferences->sp_notes = $request->input('notes');
            if ($student_preferences->save()) {
                return response()->json([
                    'status' => true,
                    'message' => trans('messages.student_preferences_added'),
                    'preferences' => $student_preferences,
                ]);
            }
        }


        return response()->json([
            'status' => false,
            'message' => trans('messages.something_wrong'),
        ]);
    }
}
