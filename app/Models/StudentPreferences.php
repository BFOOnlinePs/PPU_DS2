<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPreferences extends Model
{
    use HasFactory;
    protected $table = 'student_preferences';
    protected $primaryKey = 'sp_id';

    protected $fillable = [
        'sp_cities',
        'sp_companies',
        'sp_company_type',
        'sp_notes',
    ];
}
