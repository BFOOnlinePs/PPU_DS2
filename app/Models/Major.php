<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;
    protected $table = 'majors';
    protected $primaryKey = 'm_id';
    protected $fillable = [
        'm_id',
        'm_name',
        'm_description',
        'm_reference_code'
    ];


    public function majorSupervisors(){
        return $this->hasMany(MajorSupervisor::class, 'ms_major_id', 'm_id');
    }
    public function majorStudent(){
        return $this->hasMany(User::class, 'u_major_id', 'm_id');
    }

}
