<?php

namespace App\Exports;

use App\Models\StudentAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentAttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Fetch the data you want to export
        return StudentAttendance::with('user')->get()->map(function ($attendance) {
            return [
                'sa_student_id' => $attendance->user->name,
            ];
        });
    }

    public function headings(): array
    {
        return ["Student Name", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
    }
}
