<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCompany extends Model
{
    use HasFactory;

    protected $table = 'students_companies';
    protected $primaryKey = 'sc_id';

    protected $fillable = [
        'sc_student_id',
        'sc_company_id',
        'sc_branch_id',
        'sc_department_id',
        'sc_registration_id',
        'sc_status',
        'sc_mentor_trainer_id',
        'sc_assistant_id',
        'sc_agreement_file',
    ];

    // student (trainee)
    public function users()
    {
        return $this->belongsTo(User::class, 'sc_student_id', 'u_id');
    }
    public function userMentorTrainer()
    {
        return $this->belongsTo(User::class, 'sc_mentor_trainer_id', 'u_id');
    }
    public function userAssistant()
    {
        return $this->belongsTo(User::class, 'sc_assistant_id', 'u_id');
    }

    public function companyBranch()
    {
        return $this->belongsTo(CompanyBranch::class, 'sc_branch_id', 'b_id');
    }
    public function companyDepartment()
    {
        return $this->belongsTo(CompanyDepartment::class, 'sc_department_id', 'd_id');
    }

    // the training belongs to one company
    public function company()
    {
        return $this->belongsTo(Company::class, 'sc_company_id', 'c_id');
    }

    public function attendance()
    {
        return $this->hasMany(StudentAttendance::class, 'sa_student_company_id', 'sc_id');
    }
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'r_id', 'sc_registration_id');
    }

    public function registration()
    {
        return $this->hasOne(Registration::class, 'r_id', 'sc_registration_id');
    }

    public function trainingPayments()
    {
        return $this->hasMany(Payment::class, 'p_student_company_id', 'sc_id');
    }
}
