<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBranchLocation extends Model
{
    use HasFactory;
    protected $table = 'company_branches_locations';
    protected $primaryKey = 'bl_id';

    public function companyBranch()
    {
        return $this->belongsTo(CompanyBranch::class, 'bl_branch_id', 'b_id');
    }
}
