<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function outcome_details(){
        return $this->hasMany(OutcomeDetail::class, 'outcome_id', 'id');
    }
}
