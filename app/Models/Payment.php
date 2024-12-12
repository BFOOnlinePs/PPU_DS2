<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'p_id';

    protected $fillable = [
        'p_student_id',
        'p_student_company_id',
        'p_company_id',
        'p_reference_id',
        'p_payment_value',
        'p_file',
        'p_inserted_by_id',
        'p_status',
        'p_currency_id',
        'p_company_notes',
        'p_supervisor_notes',
        'p_student_notes'
    ];

    public function userStudent()
    {
        return $this->belongsTo(User::class, 'p_student_id', 'u_id');
    }
    public function userInsertedById()
    {
        return $this->belongsTo(User::class, 'p_inserted_by_id', 'u_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'p_currency_id' , 'c_id');
    }
    public function payments()
    {
        return $this->belongsTo(Company::class, 'p_company_id' , 'c_id');
    }
}
