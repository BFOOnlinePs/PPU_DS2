<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey = 'r_id';

    protected $fillable = [
        'r_name'
    ];

    // relations:
    public function users()
    {
        return $this->hasMany(User::class, 'u_role_id', 'r_id');
    }
}
