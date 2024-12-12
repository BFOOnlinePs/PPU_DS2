<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Course;
use App\Models\Event;
use App\Models\Major;
use App\Models\SemesterCourse;
use App\Models\StudentCompany;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function create_event(Request $request)
    {
        $event = new Event();
        $event->e_title = $request->e_title;
        $event->e_description = $request->e_description;
        $event->e_start_date = $request->e_start_date;
        $event->e_end_date = $request->e_end_date;
        $event->e_title = $request->e_title;
        $event->e_type = $request->e_type;
        if($request->has('e_id_type') && $event->e_type == 2) {
            $event->e_course_id = $request->e_id_type;
        }
        else if($request->has('e_id_type') && $event->e_type == 1) {
            $event->e_major_id = $request->e_id_type;
        }
        else if($request->has('e_id_type') && $event->e_type == 3) {
            $event->e_company_id = $request->e_id_type;
        }
        $event->e_color = $request->e_color;
        if($event->save()) {
            return response()->json([]);
        }
    }
    public function display_events(Request $request)
    {
        $events = array();
        $data = Event::get();
        foreach($data as $key) {
            $events[] = [
                'id' => $key->e_id ,
                'title' => $key->e_title ,
                'start' => $key->e_start_date ,
                'end' => $key->e_end_date ,
                'backgroundColor' => $key->e_color,
                'borderColor' => $key->e_color
            ];
        }
        return response()->json(['events' => $events]);
    }
    public function ajax_to_get_courses(Request $request)
    {
        $semester_courses = SemesterCourse::get();
        foreach($semester_courses as $key) {
            $key->sc_course = Course::find($key->sc_course_id);
        }
        return response()->json(['semester_courses' => $semester_courses]);
    }
    public function ajax_to_get_majors(Request $request)
    {
        $majors = Major::get();
        return response()->json(['majors' => $majors]);
    }
    public function ajax_to_get_companies(Request $request)
    {
        $companies = Company::get();
        return response()->json(['companies' => $companies]);
    }
    public function show_event_information(Request $request)
    {
        $event = Event::find($request->id);
        $event_name_type = null;
        $data = null;
        $event_id_type = null;
        if(!empty($event->e_course_id)) {
            $course = Course::find($event->e_course_id);
            $event_name_type = $course->c_name;
            $event_id_type = $course->c_id;
            $data = SemesterCourse::where('sc_course_id' , '!=' , $event->e_course_id)->get();
            foreach($data as $key) {
                $course = Course::where('c_id' , $key->sc_course_id)->first();
                $key->course_name = $course->c_name;
            }
        }
        else if(!empty($event->e_major_id)) {
            $major = Major::find($event->e_major_id);
            $event_name_type = $major->m_name;
            $data = Major::where('m_id' , '!=' , $event->e_major_id)->get();
        }
        else if(!empty($event->e_company_id)) {
            $company = Company::find($event->e_company_id);
            $event_name_type = $company->c_name;
            $data = Company::where('c_id' , '!=' , $event->e_compnay_id)->get();
        }
        return response()->json([
            'event' => $event ,
            'event_name_type' => $event_name_type ,
            'event_id_type' => $event_id_type ,
            'data' => $data
            // 'company' => $company
        ]);
    }
    public function delete_event(Request $request)
    {
        $delete_event = Event::where('e_id' , $request->e_id)
        ->delete();
        if($delete_event > 0) {
            return response()->json([]);
        }
    }
    public function edit_event(Request $request)
    {
        $event = Event::where('e_id' , $request->e_id)->first();
        $event->e_title = $request->e_title;
        $event->e_description = $request->e_description;
        $event->e_start_date = $request->e_start_date;
        $event->e_end_date = $request->e_end_date;
        $event->e_title = $request->e_title;
        $event->e_type = $request->e_type;
        $event->e_major_id = null;
        $event->e_course_id = null;
        $event->e_company_id = null;
        if($request->has('e_id_type') && $event->e_type == 2) {
            $event->e_course_id = $request->e_id_type;
            $event->e_major_id = null;
            $event->e_company_id = null;
        }
        else if($request->has('e_id_type') && $event->e_type == 1) {
            $event->e_major_id = $request->e_id_type;
            $event->e_course_id = null;
            $event->e_company_id = null;
        }
        else if($request->has('e_id_type') && $event->e_type == 3) {
            $event->e_company_id = $request->e_id_type;
            $event->e_major_id = null;
            $event->e_course_id = null;
        }
        $event->e_color = $request->e_color;
        if($event->save()) {
            return response()->json([]);
        }
    }
}
