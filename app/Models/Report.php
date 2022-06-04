<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function report_incomes(){
        return $this->hasMany(ReportIncome::class, 'report_id', 'id');
    }

    public function report_outcomes(){
        return $this->hasMany(ReportOutcome::class, 'report_id', 'id');
    }
}
