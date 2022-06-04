<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function income_details(){
        return $this->hasMany(IncomeDetail::class, 'income_id', 'id');
    }
}
