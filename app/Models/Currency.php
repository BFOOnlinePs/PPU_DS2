<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $table = 'currencies';
    protected $primaryKey = "c_id";
    public function currency()
    {
        return $this->hasMany(Payment::class, 'p_currency_id' , 'c_id');
    }
}
