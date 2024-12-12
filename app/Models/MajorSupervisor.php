<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MajorSupervisor extends Model
{
    use HasFactory;
    protected $table = 'major_supervisors';
    protected $primaryKey = 'ms_id';

    public function majors(){
        return $this->belongsTo(Major::class, 'ms_major_id', 'm_id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'ms_super_id', 'u_id');
    }
}
