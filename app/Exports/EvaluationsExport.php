<?php

namespace App\Exports;

use App\Models\Registration;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EvaluationsExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        // Start the query on the Registration model
        $data = Registration::query();
        $data = $data->with('users');  // Load related users

        // Filter by student name if provided
        if ($this->request->filled('student_name')) {
            $data = $data->whereHas('users', function($query) {
                $query->where('name', 'like', '%' . $this->request->student_name . '%');
            });
        }

        // Filter by course ID if provided
        if ($this->request->filled('course_id')) {
            $data = $data->where('r_course_id', $this->request->course_id);
        }

        // Filter by supervisor ID if provided
        if ($this->request->filled('supervisor_id')) {
            $data = $data->where('supervisor_id', $this->request->supervisor_id);
        }

        // Filter by company ID if provided
        if ($this->request->filled('company_id')) {
            $data = $data->whereHas('studentsCompanies', function($query) {
                $query->where('sc_company_id', $this->request->company_id);
            });
        }

        // Handle radio button filter
        if ($this->request->filled('selectedRadio')) {
            if ($this->request->selectedRadio == 'company') {
                $data = $data->whereNull('company_score');
            } elseif ($this->request->selectedRadio == 'university') {
                $data = $data->whereNull('university_score');
            }
        }

        // Retrieve the data
        $data = $data->get();

        // Return the view with the filtered data
        return view('project.admin.evaluations.excel.evaluation_export', [
            'data' => $data,
        ]);
    }
}