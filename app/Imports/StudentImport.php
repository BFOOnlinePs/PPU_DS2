<?php

namespace App\Imports;

use App\Models\Major;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentImport implements ToModel
{
    protected $additionalData, $startRow, $cnt, $students_numbers, $students_names;

    public function __construct($additionalData)
    {
        $this->additionalData = $additionalData;
        $this->startRow = 1;
        $this->cnt = 0;
        $this->students_numbers = [];
        $this->students_names = [];
    }

    public function model(array $row)
    {
        return 'Mohamad Maraqa';
        if ($this->startRow == 1) {
            $this->startRow++;
            return null;
        }

        if (empty($row[$this->additionalData['student_number']]) ||
            empty($row[$this->additionalData['student_name']]) ||
            empty($row[$this->additionalData['student_major']]) ||
            empty($row[$this->additionalData['student_phone']])) {
            return null; // Skip this row if essential fields are empty
        }

        $major = Major::firstOrCreate(['m_name' => $row[$this->additionalData['student_major']]]);

        $email = $row[$this->additionalData['student_number']] . '@ppu.edu.ps';
        $userExists = User::where('u_username', $row[$this->additionalData['student_number']])
            ->where('name', $row[$this->additionalData['student_name']])
            ->where('email', $email)
            ->exists();

        if ($userExists) {
            return null;
        }

        $this->cnt++;
        $password = Uuid::uuid4()->toString(); // Generate a random UUID version 4

        return new User([
            'u_username' => $row[$this->additionalData['student_number']],
            'name' => $row[$this->additionalData['student_name']],
            'email' => $row[$this->additionalData['student_number']] . '@ppu.edu.ps',
            'password' => bcrypt($password),
            'u_phone1' => $row[$this->additionalData['student_phone']],
            'u_major_id' => $major->m_id,
            'u_role_id' => 2,
            'u_gender' => 3,
            'u_status' => 1,
            'u_tawjihi_gpa' => $row[$this->additionalData['u_tawjihi_gpa']],
            'u_company_id' => $row[$this->additionalData['u_company_id']],
            'u_date_of_birth' => $row[$this->additionalData['u_date_of_birth']]
        ]);
    }

    public function getCount()
    {
        return $this->cnt;
    }

    public function getArrayStudentsNames()
    {
        return $this->students_names;
    }
}
