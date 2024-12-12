<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('project.admin.report.index');
    }

    public function report_history_ajax(Request $request)
    {
        $courseName = $request->input('course_name');
        $year = $request->input('year');
        $semester = $request->input('semester');

        $results = DB::table('registration as r')
            ->join('courses as c', 'r.r_course_id', '=', 'c.c_id')
            ->join('users as u', 'r.r_student_id', '=', 'u.u_id')
            ->select(
                'c.c_name as course_name',
                'r.r_year',
                'r.r_semester',
                DB::raw('SUM(CASE WHEN u.u_gender = 0 THEN 1 ELSE 0 END) AS male_count'),
                DB::raw('SUM(CASE WHEN u.u_gender = 1 THEN 1 ELSE 0 END) AS female_count'),
                DB::raw('COUNT(r.r_student_id) AS total_count')
            );

        if (!empty($courseName)) {
            $results = $results->where('c.c_name', 'like', '%' . $courseName . '%');
        }

        if (!empty($year)) {
            $results = $results->where('r.r_year', $year);
        }

        if (!empty($semester)) {
            $results = $results->where('r.r_semester', $semester);
        }

        $results = $results->groupBy('c.c_name', 'r.r_year', 'r.r_semester')
            ->orderBy('r.r_year')
            ->orderBy('c.c_name')
            ->orderBy('r.r_semester')
            ->get();

        return response()->json([
            'success' => true,
            'view' => view('project.admin.report.ajax.report_history_ajax', ['results' => $results])->render()
        ]);
    }
}
