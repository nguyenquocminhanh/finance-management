<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Member;
use DB;
use Auth;
use Illuminate\Support\Carbon;

class IncomeController extends Controller
{
    public function IncomeAll() {
        $incomes = Income::latest()->get();
        return view('backend.income.income_all', compact('incomes'));
    }

    public function IncomeAdd() {
        $members = Member::all();
        return view('backend.income.income_add', compact('members'));
    }

    public function IncomeStore(Request $request) {

        // add data to Income
        $income = new Income();
        $income->name = $request->name;
        $income->total_amount = $request->total_amount;
       
        $income->created_by = Auth::user()->id;
        $income->created_at = Carbon::now()->timezone('America/New_York');
 
        // use DB when insert multi data to multible tables, if there are any issues -> stop all inserts in any tables
        DB::transaction(function() use($request, $income){
            if($income->save()) {
                $count_member = count($request->member_id);
                // add data to IncomeDetail
                for($i=0; $i < $count_member; $i++) {
                    $income_details = new IncomeDetail();
                    $income_details->income_id = $income->id;
                    $income_details->member_id = $request->member_id[$i];
                    $income_details->amount = $request->amount[$i];
                    $income_details->save();
                }
            } else {
                $notification = array(
                    'message' => 'Something Went Wrong! Please Try Again',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        });

        $notification = array(
            'message' => 'Income Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('income.all')->with($notification);
    }

    public function IncomeEdit($id) {
        $income = Income::findOrFail($id);
        $members = Member::all();
        return view('backend.income.income_edit', compact('income', 'members'));
    }

    public function IncomeUpdate(Request $request) {
        // hidden input
        $income_id = $request->id;

        $income = Income::findOrFail($income_id);
        $income->name = $request->name;
        $income->total_amount = $request->total_amount;
       
        $income->updated_by = Auth::user()->id;
        $income->updated_at = Carbon::now()->timezone('America/New_York');
            
        DB::transaction(function() use($request, $income){
            if($income->save()) {
                $count_member = count($request->member_id);
                // update data to IncomeDetail
                for($i=0; $i < $count_member; $i++) {
                    $member_id = $request->member_id[$i];
                    $income_details = IncomeDetail::where('member_id', $member_id)
                        ->where('income_id', $income->id)->first();

                    $income_details->amount = $request->amount[$i];
                    $income_details->save();
                }
            } else {
                $notification = array(
                    'message' => 'Something Went Wrong! Please Try Again',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        });

        $notification = array(
            'message' => 'Income Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('income.all')->with($notification);
    }

    public function IncomeDelete($id) {
        $income = Income::findOrFail($id);
        $income->delete();

        // delete in other tables
        IncomeDetail::where('income_id', $id)->delete();
        
        $notification = array(
            'message' => 'Income Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('income.all')->with($notification);
    }
}
