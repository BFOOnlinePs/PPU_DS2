<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\Course;
use App\Models\Major;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $additionalData , $startRow , $cnt , $students_numbers , $students_names;

    public function __construct($additionalData)
    {
        $this->additionalData = $additionalData;
        $this->startRow = 1;
        $this->cnt = 0;
        $this->students_numbers = array();
        $this->students_names = array();
    }
    public function model(array $row)
    {
        return 'Mohamad Maraqa';
        $company_id = 0;
        $major_id = 0;
        if ($this->startRow == 1) {
            $this->startRow++;
            return null;
        }
        if (empty($row[$this->additionalData['student_id']]) || empty($row[$this->additionalData['student_name']]) || empty($row[$this->additionalData['student_gender']])) {
            return null; // Skip this row if student_id or student_name is empty
        }
        $gender = null;
        if($row[$this->additionalData['student_gender']] == "أنثى" || $row[$this->additionalData['student_gender']] == "انثى" || $row[$this->additionalData['student_gender']] == "Female" || $row[$this->additionalData['student_gender']] == "female") {
            $gender = 1;
        }
        else if($row[$this->additionalData['student_gender']] == "ذكر" || $row[$this->additionalData['student_gender']] == "Male" || $row[$this->additionalData['student_gender']] == "male") {
            $gender = 0;
        }
        $user = User::where('u_username' , $row[$this->additionalData['student_id']])
            ->where('name' , $row[$this->additionalData['student_name']])
            ->where('u_gender' , $gender)
            ->exists();
        if($user) {
            return null;
        }

        $new_major = Major::firstOrCreate(
            [
                'm_reference_code' => $row[$this->additionalData['major_id']],
            ],
            [
                'm_name' => $row[$this->additionalData['major_name']],
            ]
        );

        $return_user = new User();
        $return_user->u_username = $row[$this->additionalData['student_id']];
        $return_user->name = $row[$this->additionalData['student_name']];
        $return_user->u_gender = $gender;
        $return_user->password = bcrypt("123456789");
        $return_user->email = $row[$this->additionalData['student_id']] . '@ppu.edu.ps';
        $return_user->u_role_id = 2;
        $return_user->u_major_id = $new_major->m_id;
        $return_user->u_status = 1;
        $return_user->u_tawjihi_gpa = $row[$this->additionalData['u_tawjihi_gpa']] ?? 0;
        $return_user->u_company_id = $company_id;
        $return_user->u_phone1 = $row[$this->additionalData['u_phone1']];
        $return_user->u_date_of_birth = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$this->additionalData['u_date_of_birth']])->format('Y-m-d');

        if($return_user->save()) {
            $check_course_id_if_exist = Company::where('c_name',$row[$this->additionalData['course_name']])->first();
            $new_course = Course::firstOrCreate([
                'c_name' => $row[$this->additionalData['course_name']],
            ]);
            $registration = Registration::firstOrCreate([
                'r_course_id' => $new_course->c_id,
                'r_student_id' => $return_user->u_id,
                'r_semester' => $row[$this->additionalData['semester']],
                'r_year' => $row[$this->additionalData['year']],
            ]);
            if (!empty($row[$this->additionalData['manager_name']])) {
                $user_manager = User::firstOrCreate(
                    [
                        'u_username' => $row[$this->additionalData['email']]
                    ],
                    [
                        'name' => $row[$this->additionalData['manager_name']] ?? 'empty',
                        'email' => $row[$this->additionalData['email']] ?? $row[$this->additionalData['manager_phone']] . '@mail.com',
                        'password' => bcrypt('123456789'),
                        'u_phone1' => $row[$this->additionalData['manager_phone']],
                        'u_role_id' => 6,
                        'u_major_id' => -1
                    ]
                );

                if (!empty($row[$this->additionalData['u_company_id']])){
                    $user_company_manager = Company::firstOrCreate(
                        [
                            'c_name' => $row[$this->additionalData['u_company_id']]
                        ],
                        [
                            'c_manager_id' => $user_manager->u_id,
                        ]

                    );
                }



                $company_id = $user_company_manager->c_id;


//                $check_manager = User::firstOrCreate([
//                    'name' => $row[$this->additionalData['manager_name']],
//                    'email' => !empty($row[$this->additionalData['email']]) ?? $row[$this->additionalData['manager_phone']] . '@mail.com',
//                    'password' => bcrypt("123456789"),
//                    'u_role_id' => 6,
//                    'u_status' => 1,
//                ]);
//                $check_company_id_if_exist = Company::where('c_name',$row[$this->additionalData['u_company_id']])->first();
//                $new_company = Company::firstOrCreate([
//                    'c_name' => $row[$this->additionalData['u_company_id']],
//
//                ]);
//                $company_id = $new_company->c_id;

//                $user_manager = new User();
//                $user_manager->u_username = $row[$this->additionalData['email']];
//                $user_manager->name = $row[$this->additionalData['manager_name']] ?? 'test';
//                $user_manager->email = $row[$this->additionalData['email']] ?? $row[$this->additionalData['manager_phone']] . '@mail.com';
//                $user_manager->password = bcrypt('123456789');
//                $user_manager->u_phone1 = $row[$this->additionalData['manager_phone']];
//                $user_manager->u_role_id = 6;
//                $user_manager->save();
//                $user_company_manager = new Company();
//                $user_company_manager->c_name = $row[$this->additionalData['manager_name']];
//                $user_company_manager->save();
            }

            if (!empty($user) && !empty($registration) && !empty($check_company_id_if_exist) && !empty($user_company_manager)){
                $data = new StudentCompany();
                $data->sc_registration_id = $registration->r_id;
                $data->sc_student_id = $return_user->u_id;
                $data->sc_company_id = $check_company_id_if_exist->c_id;
                $data->sc_status = 1;
                $data->save();
            }

            $user_manager = User::firstOrCreate(
                [
                    'u_username' => $row[$this->additionalData['email']]
                ],
                [
                    'name' => $row[$this->additionalData['manager_name']] ?? 'empty',
                    'email' => $row[$this->additionalData['email']] ?? $row[$this->additionalData['manager_phone']] . '@mail.com',
                    'password' => bcrypt('123456789'),
                    'u_phone1' => $row[$this->additionalData['manager_phone']],
                    'u_role_id' => 6,
                    'u_major_id' => -1
                ]
            );

            $user_company_manager = Company::firstOrCreate(
                [
                    'c_name' => $row[$this->additionalData['u_company_id']]
                ],
                [
                    'c_manager_id' => $user_manager->u_id,
                ]

            );

            $new_barnch_company = CompanyBranch::firstOrCreate(
                [
                    'b_company_id' => $user_company_manager->c_id
                ]
                ,
                [
                    'b_address' => '-',
                    'b_phone1' => $row[$this->additionalData['manager_phone']] ?? 0000000000,
                    'b_main_branch' => $user_company_manager->c_id,
                    'b_company_id' => $user_company_manager->c_id,
                    'b_manager_id' => $user_manager->u_id,
                ]
            );

            $new_students_companies = StudentCompany::firstOrCreate(
                [
                    'sc_registration_id' => $registration->r_id,
                    'sc_student_id' => $return_user->u_id
                ],
                [
                    'sc_status' => 1 ,
                    'sc_company_id' => $company_id,
                    'sc_branch_id' => $company_id
                ]
            );
//            if (empty($check_course_id_if_exist)) {
//                $new_course = new Course();
//                $new_course->c_name = $row[$this->additionalData['course_name']];
//                $new_course->save();
//                $course_id = $new_course->c_id;
//                $registration = new Registration();
//                $registration->r_course_id = $course_id;
//                $registration->r_student_id = $return_user->u_id;
//                $registration->save();
//            } else {
//                $course_id = $check_course_id_if_exist->c_id;
//                $registration = new Registration();
//                $registration->r_course_id = $course_id;
//                $registration->r_student_id = $return_user->u_id;
//                $registration->save();
//            }


//        $major = Major::where('m_name' , $row[$this->additionalData['major_name']])
//            ->where('m_reference_code' , $row[$this->additionalData['major_id']])
//            ->first();
            $check_major_id_if_exist = Major::where('m_name' , $row[$this->additionalData['major_name']])
                ->where('m_reference_code' , $row[$this->additionalData['major_id']])
                ->first();

            if (empty($check_major_id_if_exist)) {
                $new_major = new Major();
                $new_major->m_name = $row[$this->additionalData['major_name']];
                $new_major->m_reference_code = $row[$this->additionalData['major_id']];
                $new_major->save();
                $major_id = $new_major->m_id;
            } else {
                $major_id = $check_major_id_if_exist->c_id;
            }



            $this->cnt++;
            array_push($this->students_numbers, $row[$this->additionalData['student_id']]);
            array_push($this->students_names, $row[$this->additionalData['student_name']]);
            return $return_user;
        }
        else {
            return null;
        }
    }
    public function getCount()
    {
        return $this->cnt;
    }
    public function getArrayStudentsNumbers()
    {
        return $this->students_numbers;
    }
    public function getArrayStudentsNames()
    {
        return $this->students_names;
    }
}
