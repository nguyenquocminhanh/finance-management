<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportIncome extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function member(){
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function income(){
        return $this->belongsTo(Income::class, 'income_id', 'id');
    }
}
