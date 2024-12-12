<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class companyBranchDepartments extends Model
{
    use HasFactory;
    protected $table = 'company_branches_departments';
    protected $primaryKey = 'cbd_id';

    protected $fillable = [
        'cbd_d_id',
        'cbd_company_branch_id',
        'cbd_status'
    ];

    public function studentCompany()
    {
        return $this->hasMany(StudentCompany::class, 'sc_department_id', 'cbd_id');
    }
    public function departments()
    {
        return $this->belongsTo(CompanyDepartment::class, 'd_id', 'cbd_d_id');
    }

    public function companyDepartment()
    {
        return $this->belongsTo(CompanyDepartment::class, 'cbd_d_id', 'd_id');
    }
}
