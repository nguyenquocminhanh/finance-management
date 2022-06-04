<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\OutcomeDetail;
use App\Models\Report;

class DefaultController extends Controller
{
    public function CheckIncomeAmount(Request $request) {
        $member_id_income = $request->member_id_income;
        $income_id = $request->income_id;

        if (IncomeDetail::where('member_id', $member_id_income)->where('income_id', $income_id)->first()) {   
            $amount = IncomeDetail::where('member_id', $member_id_income)->where('income_id', $income_id)->first()->amount;
        } else {
            $amount = 0;
            
        }
        return response()->json($amount);
    }

    public function CheckOutcomeAmount(Request $request) {
        $member_id_outcome = $request->member_id_outcome;
        $outcome_id = $request->outcome_id;

        if (OutcomeDetail::where('member_id', $member_id_outcome)->where('outcome_id', $outcome_id)->first()) {
            $amount = OutcomeDetail::where('member_id', $member_id_outcome)->where('outcome_id', $outcome_id)->first()->amount;
        } else {
            $amount = 0;
        }
        return response()->json($amount);
    }

    

    public function CheckExistPeriodForAdd(Request $request) {
        $result = false;
        if (Report::where('period', $request->period)->first()){
            $result = true;
        }  
        return response()->json($result);
    }

    public function CheckExistPeriodForEdit(Request $request) {
        $result = false;
        if (Report::where('id', '!=', $request->report_id)->where('period',$request->period)->first()){
            $result = true;
        }
        return response()->json($result);
    }
}
