<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outcome;
use App\Models\OutcomeDetail;
use App\Models\Member;
use DB;
use Auth;
use Illuminate\Support\Carbon;

class OutcomeController extends Controller
{
    public function OutcomeAll() {
        $outcomes = Outcome::latest()->get();
        return view('backend.outcome.outcome_all', compact('outcomes'));
    }

    public function OutcomeAdd() {
        $members = Member::all();
        return view('backend.outcome.outcome_add', compact('members'));
    }

    public function OutcomeStore(Request $request) {

        // add data to Outcome
        $outcome = new Outcome();
        $outcome->name = $request->name;
        $outcome->total_amount = $request->total_amount;
       
        $outcome->created_by = Auth::user()->id;
        $outcome->created_at = Carbon::now()->timezone('America/New_York');

        // use DB when insert multi data to multible tables, if there are any issues -> stop all inserts in any tables
        DB::transaction(function() use($request, $outcome){
            if($outcome->save()) {
                $count_member = count($request->member_id);
                // add data to OutcomeDetail
                for($i=0; $i < $count_member; $i++) {
                    $outcome_details = new OutcomeDetail();
                    $outcome_details->outcome_id = $outcome->id;
                    $outcome_details->member_id = $request->member_id[$i];
                    $outcome_details->amount = $request->amount[$i];
                    $outcome_details->save();
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
            'message' => 'Outcome Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('outcome.all')->with($notification);
    }

    public function OutcomeEdit($id) {
        $outcome = Outcome::findOrFail($id);
        $members = Member::all();
        return view('backend.outcome.outcome_edit', compact('outcome', 'members'));
    }

    public function OutcomeUpdate(Request $request) {
        // hidden input
        $outcome_id = $request->id;

        $outcome = Outcome::findOrFail($outcome_id);
        $outcome->name = $request->name;
        $outcome->total_amount = $request->total_amount;
       
        $outcome->updated_by = Auth::user()->id;
        $outcome->updated_at = Carbon::now();
            
        DB::transaction(function() use($request, $outcome){
            if($outcome->save()) {
                $count_member = count($request->member_id);
                // update data to OutcomeDetail
                for($i=0; $i < $count_member; $i++) {
                    $member_id = $request->member_id[$i];
                    $outcome_details = OutcomeDetail::where('member_id', $member_id)
                        ->where('outcome_id', $outcome->id)->first();

                    $outcome_details->amount = $request->amount[$i];
                    $outcome_details->save();
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
            'message' => 'Outcome Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('outcome.all')->with($notification);
    }

    public function OutcomeDelete($id) {
        $outcome = Outcome::findOrFail($id);
        $outcome->delete();

        // delete in other tables
        OutcomeDetail::where('outcome_id', $id)->delete();
        
        $notification = array(
            'message' => 'Outcome Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('outcome.delete')->with($notification);
    }
}
