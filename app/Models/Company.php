<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'c_id';

    protected $fillable = [
        'c_name',
        'c_english_name',
        'c_description',
        'c_english_description',
        'c_website',
        'c_type',
        'c_category_id',
        'c_manager_id',
        'c_status',
        'c_capacity',
    ];

    public function companyBranch()
    {
        return $this->hasMany(CompanyBranch::class, 'b_company_id', 'c_id');
    }
    public function companyDepartments()
    {
        return $this->hasMany(CompanyDepartment::class, 'd_company_id', 'c_id');
    }

    public function companyCategories()
    {
        return $this->belongsTo(CompaniesCategory::class, 'c_category_id', 'cc_id');
    }

    // the company has many trainings
    public function trainings()
    {
        return $this->hasMany(StudentCompany::class, 'sc_company_id', 'c_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'c_manager_id', 'u_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'p_company_id', 'c_id');
    }
}
