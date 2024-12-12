<?php

namespace App\Imports\integration_company;

use App\Models\CompaniesCategory;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    protected $additionalData , $cnt , $company_array;
    /**
    * @param Collection $collection
    */
    public function __construct($additionalData)
    {
        $this->additionalData = $additionalData;
        $this->cnt = 0;
        $this->company_array = array();
    }
    public function collection(Collection $collection)
    {
        foreach($collection as $rowIndex => $row) {
            // To skip first row
            if($rowIndex == 0) {
                continue;
            }
            if(empty($row[$this->additionalData['company_name']]) || empty($row[$this->additionalData['manager_name']]) || empty($row[$this->additionalData['company_email']]) || empty($row[$this->additionalData['company_password']]) || empty($row[$this->additionalData['company_address']]) || empty($row[$this->additionalData['company_type']])) {
                continue;
            }
            $user_exists = User::where('email' , $row[$this->additionalData['company_email']])
            ->exists();
            $user = null;
            if($user_exists) {
                $user = User::where('email' , $row[$this->additionalData['company_email']])
                ->first();
                $user->u_username = $row[$this->additionalData['manager_name']];
                $user->name = $row[$this->additionalData['manager_name']];
                $user->password = bcrypt($row[$this->additionalData['company_password']]);
                if(empty($row[$this->additionalData['phone_number']])) {
                    $user->u_phone1 = '0000000000';
                }
                else {
                    $user->u_phone1 = $row[$this->additionalData['phone_number']];
                }
                $user->u_address = $row[$this->additionalData['company_address']];
                $user->u_role_id = 6;
                $user->u_status = 1;
                $user->u_tawjihi_gpa = $row[$this->additionalData['u_tawjihi_gpa']];
            }
            else {
                $user = new User();
                $user->u_username = $row[$this->additionalData['manager_name']];
                $user->name = $row[$this->additionalData['manager_name']];
                $user->email = $row[$this->additionalData['company_email']];
                $user->password = bcrypt($row[$this->additionalData['company_password']]);
                if(empty($row[$this->additionalData['phone_number']])) {
                    $user->u_phone1 = '0000000000';
                }
                else {
                    $user->u_phone1 = $row[$this->additionalData['phone_number']];
                }
                $user->u_address = $row[$this->additionalData['company_address']];
                $user->u_role_id = 6;
                $user->u_status = 1;
                $user->u_tawjihi_gpa = $row[$this->additionalData['u_tawjihi_gpa']];
            }
            if($user->save()) {
                $company_exists = Company::where('c_manager_id' , $user->u_id)
                ->exists();
                $company_type = null;
                if($row[$this->additionalData['company_type']] == 'قطاع عام') {
                    $company_type = 1;
                }
                else if($row[$this->additionalData['company_type']] == 'قطاع خاص') {
                    $company_type = 2;
                }
                $company_category_exists = CompaniesCategory::where('cc_name' , $row[$this->additionalData['company_category']])
                ->exists();
                $company_category = null;
                if(!($company_category_exists)) {
                    $company_category = new CompaniesCategory();
                    $company_category->cc_name = $row[$this->additionalData['company_category']];
                    $company_category->save();
                }
                $company_category = CompaniesCategory::where('cc_name' , $row[$this->additionalData['company_category']])->first();
                if($company_exists) {
                    $company = Company::where('c_manager_id' , $user->u_id)
                    ->first();
                    $company->c_name = $row[$this->additionalData['company_name']];
                    $company->c_type = $company_type;
                    $company->c_category_id = $company_category->cc_id;
                }
                else {
                    $company = new Company();
                    $company->c_manager_id = $user->u_id;
                    $company->c_name = $row[$this->additionalData['company_name']];
                    $company->c_type = $company_type;
                    $company->c_category_id = $company_category->cc_id;
                }
                if($company->save()) {
                    $company_branch_exists = CompanyBranch::where('b_company_id' , $company->c_id)
                    ->where('b_manager_id', $user->u_id)
                    ->exists();
                    $company_branch = null;
                    if($company_branch_exists) {
                        $company_branch = CompanyBranch::where('b_company_id' , $company->c_id)
                        ->where('b_manager_id', $user->u_id)
                        ->first();
                        $company_branch->b_address = $row[$this->additionalData['company_address']];
                        if(empty($row[$this->additionalData['phone_number']])) {
                            $company_branch->b_phone1 = '0000000000';
                        }
                        else {
                            $company_branch->b_phone1 = $row[$this->additionalData['phone_number']];
                        }
                        $company_branch->b_main_branch = 1;
                    }
                    else {
                        $company_branch = new CompanyBranch();
                        $company_branch->b_company_id = $company->c_id;
                        $company_branch->b_manager_id = $user->u_id;
                        $company_branch->b_address = $row[$this->additionalData['company_address']];
                        if(empty($row[$this->additionalData['phone_number']])) {
                            $company_branch->b_phone1 = '0000000000';
                        }
                        else {
                            $company_branch->b_phone1 = $row[$this->additionalData['phone_number']];
                        }
                        $company_branch->b_main_branch = 1;
                    }
                    if($company_branch->save()) {
                        $this->cnt++;
                        array_push($this->company_array , $company->c_name);
                        array_push($this->company_array , $user->name);
                        // Done :)
                    }

                }
            }
        }
    }
    public function getCount()
    {
        return $this->cnt;
    }
    public function getCompanyArray()
    {
        return $this->company_array;
    }
}
