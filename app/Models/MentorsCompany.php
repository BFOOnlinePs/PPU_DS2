<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorsCompany extends Model
{
    use HasFactory;
    protected $table = 'mentors_companies';
    protected $primaryKey = 'mc_id';
}
