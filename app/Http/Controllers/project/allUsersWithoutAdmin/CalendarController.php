<?php

namespace App\Http\Controllers\project\allUsersWithoutAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Course;
use App\Models\Event;
use App\Models\Major;
use App\Models\Registration;
use App\Models\SemesterCourse;
use App\Models\StudentCompany;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function display_events(Request $request)
    {
        $events = array();
        $data = null;
        $system_settings = SystemSetting::first();
        if(Auth::user()->u_role_id == 2) {
            $firstQuery = Event::where('e_type' , 0);
            $secondQuery = Event::where('e_type' , 1)
            ->where('e_major_id' , Auth::user()->u_major_id);
            $registration_course_id = Registration::where('r_student_id' , Auth::user()->u_id)
            ->where('r_semester' , $system_settings->ss_semester_type)
            ->where('r_year' , $system_settings->ss_year)
            ->pluck('r_course_id')
            ->toArray();
            $thirdQuery = Event::where('e_type' , 2)
            ->whereIn('e_course_id' , $registration_course_id);
            $registration_id = Registration::where('r_student_id' , Auth::user()->u_id)
            ->where('r_semester' , $system_settings->ss_semester_type)
            ->where('r_year' , $system_settings->ss_year)
            ->pluck('r_id')
            ->toArray();
            $student_companies = StudentCompany::where('sc_student_id' , Auth::user()->u_id)
            ->whereIn('sc_registration_id' , $registration_id)
            ->pluck('sc_company_id')
            ->toArray();
            $fourthQuery = Event::where('e_type' , 3)
            ->whereIn('e_company_id' , $student_companies);
            $data = $firstQuery->union($secondQuery)
            ->union($thirdQuery)
            ->union($fourthQuery)
            ->get();
        }
        else if(Auth::user()->u_role_id == 3) {
            $firstQuery = Event::where('e_type' , 0);
            $secondQuery = Event::where('e_type' , 4);
            $data = $firstQuery->union($secondQuery)->get();
        }
        else {
            $data = Event::where('e_type' , 0)->get();
        }
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
    public function show_event_information(Request $request)
    {
        $event = Event::find($request->id);
        $event_name_type = null;
        if(!empty($event->e_course_id)) {
            $course = Course::find($event->e_course_id);
            $event_name_type = $course->c_name;
            $event_id_type = $course->c_id;
        }
        else if(!empty($event->e_major_id)) {
            $major = Major::find($event->e_major_id);
            $event_name_type = $major->m_name;
        }
        else if(!empty($event->e_company_id)) {
            $company = Company::find($event->e_company_id);
            $event_name_type = $company->c_name;
        }
        return response()->json([
            'event' => $event ,
            'event_name_type' => $event_name_type ,
        ]);
    }
}
