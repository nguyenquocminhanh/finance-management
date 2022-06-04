<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Outcome;
use App\Models\OutcomeDetail;
use App\Models\Report;
use App\Models\ReportIncome;
use App\Models\ReportOutcome;
use App\Models\Member;
use DB;
use Auth;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function ReportAll() {
        $reports = Report::orderBy('period', 'DESC')->get();
        return view('backend.report.report_all', compact('reports'));
    }

    public function ReportAdd() {
        $members = Member::all();
        $incomes = Income::all();
        $outcomes = Outcome::all();
        $period = date('Y-m');

        return view('backend.report.report_add', compact('members', 'period', 'incomes', 'outcomes'));
    }

    public function ReportStore(Request $request) {   
        // chua nhap so lieu nao     
        if ($request->member_id_income == null && $request->member_id_outcome == null) {
            $notification = array(
                'message' => 'You Did Not Select Any Item',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        } else {
            // so lieu da co san
            if (Report::where('period',$request->period)->first()){
                $notification = array(
                    'message' => 'There exists report for this month already!',
                    'alert-type' => 'error'
                );
                
                return redirect()->back()->with($notification);
            } else {
                // add data to Report
                $report = new Report();
                $report->period = $request->period;
                $report->total_income_amount = $request->total_amount_income;
                $report->total_outcome_amount = $request->total_amount_outcome;
                $report->description = $request->description;
                $report->created_by = Auth::user()->id;
                $report->created_at = Carbon::now()->timezone('America/New_York');

                // use DB when insert multi data to multible tables, if there are any issues -> stop all inserts in any tables
                DB::transaction(function() use($request, $report){
                    if($report->save()) {
                        if($request->income_id) {
                            $count_income = count($request->income_id);
                            // add data to ReportIncome
                            for($i=0; $i < $count_income; $i++) {

                                // NEW INCOME type => add new income type with amount to that member
                                if ($request->income_id[$i] == '0') {
                                    // co 2 member cung tao ra 1 new income type, member sau chi phai update income type co san roi
                                    if (Income::where('name', $request->new_income_name[$i])->first()) {
                                        $income = Income::where('name', $request->new_income_name[$i])->first();
                                        // update Income total price
                                        $income->total_amount += $request->income_amount[$i];
                                        $income->updated_by = Auth::user()->id;
                                        $income->updated_at = Carbon::now()->timezone('America/New_York');
                                        DB::transaction(function() use($request, $income, $i, $report){
                                            if($income->save()) {
                                                // store data to IncomeDetail
                                                $income_details = new IncomeDetail();
                                                $income_details->income_id = $income->id;
                                                $income_details->member_id = $request->member_id_income[$i];
                                                $income_details->amount = $request->income_amount[$i];
                                                $income_details->save();

                                                // store data to ReportIncome
                                                $report_income = new ReportIncome();
                                                $report_income->report_id = $report->id;
                                                $report_income->member_id = $request->member_id_income[$i];
                                                $report_income->income_id = $income->id;
                                                $report_income->amount = $request->income_amount[$i];
                                            
                                                $report_income->save();
                                            }
                                        });
                                    } else {     
                                        // add data to Income
                                        $income = new Income();
                                        $income->name = $request->new_income_name[$i];
                                        $income->total_amount = $request->income_amount[$i];

                                        $income->created_by = Auth::user()->id;
                                        $income->created_at = Carbon::now()->timezone('America/New_York');

                                        DB::transaction(function() use($request, $income, $i, $report){
                                            if($income->save()) {
                                                // store data to IncomeDetail
                                                $income_details = new IncomeDetail();
                                                $income_details->income_id = $income->id;
                                                $income_details->member_id = $request->member_id_income[$i];
                                                $income_details->amount = $request->income_amount[$i];
                                                $income_details->save();

                                                // store data to ReportIncome
                                                $report_income = new ReportIncome();
                                                $report_income->report_id = $report->id;
                                                $report_income->member_id = $request->member_id_income[$i];
                                                $report_income->income_id = $income->id;
                                                $report_income->amount = $request->income_amount[$i];
                                            
                                                $report_income->save();
                                            } 
                                        });
                                    }
                                } // end if

                                else {
                                    $report_income = new ReportIncome();
                                    $report_income->report_id = $report->id;
                                    $report_income->member_id = $request->member_id_income[$i];
                                    $report_income->income_id = $request->income_id[$i];
                                    $report_income->amount = $request->income_amount[$i];
                                
                                    $report_income->save();
                                }
                            }
                        }
                        
                        // OUTCOME

                        if($request->outcome_id) {
                            // add data to ReportOutcome
                            $count_outcome = count($request->outcome_id);
                            for($i=0; $i < $count_outcome; $i++) {
                                // NEW OUTCOME type => add new outcome type with amount to that member
                                if ($request->outcome_id[$i] == '0') {
                                    // co 2 member cung tao ra 1 new outcome type, member sau chi phai update outcome type co san roi
                                    if (Outcome::where('name', $request->new_outcome_name[$i])->first()) {
                                        $outcome = Outcome::where('name', $request->new_outcome_name[$i])->first();
                                        // update Outcome total price
                                        $outcome->total_amount += $request->outcome_amount[$i];
                                        $outcome->updated_by = Auth::user()->id;
                                        $outcome->updated_at = Carbon::now()->timezone('America/New_York');
                                        DB::transaction(function() use($request, $outcome, $i, $report){
                                            if($outcome->save()) {
                                                // store data to OutcomeDetail
                                                $outcome_details = new OutcomeDetail();
                                                $outcome_details->outcome_id = $outcome->id;
                                                $outcome_details->member_id = $request->member_id_outcome[$i];
                                                $outcome_details->amount = $request->outcome_amount[$i];
                                                $outcome_details->save();

                                                // store data to ReportOutcome
                                                $report_outcome = new ReportOutcome();
                                                $report_outcome->report_id = $report->id;
                                                $report_outcome->member_id = $request->member_id_outcome[$i];
                                                $report_outcome->outcome_id = $outcome->id;
                                                $report_outcome->amount = $request->outcome_amount[$i];
                                            
                                                $report_outcome->save();
                                            }
                                        });
                                    } else {     
                                        // add data to Outcome
                                        $outcome = new Outcome();
                                        $outcome->name = $request->new_outcome_name[$i];
                                        $outcome->total_amount = $request->outcome_amount[$i];

                                        $outcome->created_by = Auth::user()->id;
                                        $outcome->created_at = Carbon::now()->timezone('America/New_York');

                                        DB::transaction(function() use($request, $outcome, $i, $report){
                                            if($outcome->save()) {
                                                // store data to OutcomeDetail
                                                $outcome_details = new OutcomeDetail();
                                                $outcome_details->outcome_id = $outcome->id;
                                                $outcome_details->member_id = $request->member_id_outcome[$i];
                                                $outcome_details->amount = $request->outcome_amount[$i];
                                                $outcome_details->save();

                                                // store data to ReportOutcome
                                                $report_outcome = new ReportOutcome();
                                                $report_outcome->report_id = $report->id;
                                                $report_outcome->member_id = $request->member_id_outcome[$i];
                                                $report_outcome->outcome_id = $outcome->id;
                                                $report_outcome->amount = $request->outcome_amount[$i];
                                            
                                                $report_outcome->save();
                                            } 
                                        });
                                    }
                                } // end if
                                else {
                                    $report_outcome = new ReportOutcome();
                                    $report_outcome->report_id = $report->id;
                                    $report_outcome->member_id = $request->member_id_outcome[$i];
                                    $report_outcome->outcome_id = $request->outcome_id[$i];
                                    $report_outcome->amount = $request->outcome_amount[$i];
                                
                                    $report_outcome->save();
                                }
                            }
                        }
                    }
                });
                $notification = array(
                    'message' => 'Report Data Inserted Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('report.all')->with($notification);
            
            }
        }
    }

    public function ReportEdit($id) {
        $report = Report::findOrFail($id);
        $members = Member::all();
        $incomes = Income::all();
        $outcomes = Outcome::all();
        return view('backend.report.report_edit', compact('report', 'members', 'incomes', 'outcomes'));
    }
    
    public function ReportUpdate(Request $request) {
        $report_id = $request->id;

        // chua nhap so lieu nao     
        if ($request->member_id_income == null && $request->member_id_outcome == null) {
            $notification = array(
                'message' => 'You Did Not Select Any Item',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        } else {
            // co report nao khac report nay co period giong ko?
            // vi dang update report hien tai
            if (Report::where('id', '!=', $report_id)->where('period',$request->period)->first()){
                $notification = array(
                    'message' => 'There exists report for this month already!',
                    'alert-type' => 'error'
                );
                
                return redirect()->back()->with($notification);
            } else {
                // delete all old reportDetails data
                ReportIncome::where('report_id', $report_id)->delete();
                ReportOutcome::where('report_id', $report_id)->delete();

                // add all new data vao - same as ReportStore, but with old report_id

                // update data to Report
                $report = Report::where('id', $report_id)->first();
                $report->period = $request->period;
                $report->total_income_amount = $request->total_amount_income;
                $report->total_outcome_amount = $request->total_amount_outcome;
                $report->description = $request->description;
                $report->updated_by = Auth::user()->id;
                $report->updated_at = Carbon::now()->timezone('America/New_York');

                // use DB when insert multi data to multible tables, if there are any issues -> stop all inserts in any tables
                DB::transaction(function() use($request, $report){
                    if($report->save()) {
                        if ($request->income_id) {
                            $count_income = count($request->income_id);
                            // add data to ReportIncome
                            for($i=0; $i < $count_income; $i++) {
                                // NEW INCOME type => add new income type with amount to that member
                                if ($request->income_id[$i] == '0') {
                                    // co 2 member cung tao ra 1 new income type, member sau chi phai update income type co san roi
                                    if (Income::where('name', $request->new_income_name[$i])->first()) {
                                        $income = Income::where('name', $request->new_income_name[$i])->first();
                                        // update Income total price
                                        $income->total_amount += $request->income_amount[$i];
                                        $income->updated_by = Auth::user()->id;
                                        $income->updated_at = Carbon::now()->timezone('America/New_York');
                                        DB::transaction(function() use($request, $income, $i, $report){
                                            if($income->save()) {
                                                // store data to IncomeDetail
                                                $income_details = new IncomeDetail();
                                                $income_details->income_id = $income->id;
                                                $income_details->member_id = $request->member_id_income[$i];
                                                $income_details->amount = $request->income_amount[$i];
                                                $income_details->save();

                                                // store data to ReportIncome
                                                $report_income = new ReportIncome();
                                                $report_income->report_id = $report->id;
                                                $report_income->member_id = $request->member_id_income[$i];
                                                $report_income->income_id = $income->id;
                                                $report_income->amount = $request->income_amount[$i];
                                            
                                                $report_income->save();
                                            }
                                        });
                                    } else {     
                                        // add data to Income
                                        $income = new Income();
                                        $income->name = $request->new_income_name[$i];
                                        $income->total_amount = $request->income_amount[$i];

                                        $income->created_by = Auth::user()->id;
                                        $income->created_at = Carbon::now()->timezone('America/New_York');

                                        DB::transaction(function() use($request, $income, $i, $report){
                                            if($income->save()) {
                                                // store data to IncomeDetail
                                                $income_details = new IncomeDetail();
                                                $income_details->income_id = $income->id;
                                                $income_details->member_id = $request->member_id_income[$i];
                                                $income_details->amount = $request->income_amount[$i];
                                                $income_details->save();

                                                // store data to ReportIncome
                                                $report_income = new ReportIncome();
                                                $report_income->report_id = $report->id;
                                                $report_income->member_id = $request->member_id_income[$i];
                                                $report_income->income_id = $income->id;
                                                $report_income->amount = $request->income_amount[$i];
                                            
                                                $report_income->save();
                                            } 
                                        });
                                    }
                                } // end if
                                else { 
                                    $report_income = new ReportIncome();
                                    $report_income->report_id = $report->id;
                                    $report_income->member_id = $request->member_id_income[$i];
                                    $report_income->income_id = $request->income_id[$i];
                                    $report_income->amount = $request->income_amount[$i];
                                
                                    $report_income->save();
                                }
                            }
                        }

                        if ($request->outcome_id) {
                            // add data to ReportOutcome
                            $count_outcome = count($request->outcome_id);
                            for($i=0; $i < $count_outcome; $i++) {
                                // NEW OUTCOME type => add new outcome type with amount to that member
                                if ($request->outcome_id[$i] == '0') {
                                    // co 2 member cung tao ra 1 new outcome type, member sau chi phai update outcome type co san roi
                                    if (Outcome::where('name', $request->new_outcome_name[$i])->first()) {
                                        $outcome = Outcome::where('name', $request->new_outcome_name[$i])->first();
                                        // update Outcome total price
                                        $outcome->total_amount += $request->outcome_amount[$i];
                                        $outcome->updated_by = Auth::user()->id;
                                        $outcome->updated_at = Carbon::now()->timezone('America/New_York');
                                        DB::transaction(function() use($request, $outcome, $i, $report){
                                            if($outcome->save()) {
                                                // store data to OutcomeDetail
                                                $outcome_details = new OutcomeDetail();
                                                $outcome_details->outcome_id = $outcome->id;
                                                $outcome_details->member_id = $request->member_id_outcome[$i];
                                                $outcome_details->amount = $request->outcome_amount[$i];
                                                $outcome_details->save();

                                                // store data to ReportOutcome
                                                $report_outcome = new ReportOutcome();
                                                $report_outcome->report_id = $report->id;
                                                $report_outcome->member_id = $request->member_id_outcome[$i];
                                                $report_outcome->outcome_id = $outcome->id;
                                                $report_outcome->amount = $request->outcome_amount[$i];
                                            
                                                $report_outcome->save();
                                            }
                                        });
                                    } else {     
                                        // add data to Outcome
                                        $outcome = new Outcome();
                                        $outcome->name = $request->new_outcome_name[$i];
                                        $outcome->total_amount = $request->outcome_amount[$i];

                                        $outcome->created_by = Auth::user()->id;
                                        $outcome->created_at = Carbon::now()->timezone('America/New_York');

                                        DB::transaction(function() use($request, $outcome, $i, $report){
                                            if($outcome->save()) {
                                                // store data to OutcomeDetail
                                                $outcome_details = new OutcomeDetail();
                                                $outcome_details->outcome_id = $outcome->id;
                                                $outcome_details->member_id = $request->member_id_outcome[$i];
                                                $outcome_details->amount = $request->outcome_amount[$i];
                                                $outcome_details->save();

                                                // store data to ReportOutcome
                                                $report_outcome = new ReportOutcome();
                                                $report_outcome->report_id = $report->id;
                                                $report_outcome->member_id = $request->member_id_outcome[$i];
                                                $report_outcome->outcome_id = $outcome->id;
                                                $report_outcome->amount = $request->outcome_amount[$i];
                                            
                                                $report_outcome->save();
                                            } 
                                        });
                                    }
                                } // end if
                                else {
                                    $report_outcome = new ReportOutcome();
                                    $report_outcome->report_id = $report->id;
                                    $report_outcome->member_id = $request->member_id_outcome[$i];
                                    $report_outcome->outcome_id = $request->outcome_id[$i];
                                    $report_outcome->amount = $request->outcome_amount[$i];
                                
                                    $report_outcome->save();
                                }
                            }
                        }
                    }
                });

                $notification = array(
                    'message' => 'Report Data Updated Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('report.all')->with($notification);
            }            
        }
    }

    public function ReportDelete($id) {
        $report = Report::findOrFail($id);
        $report->delete();

        // delete in other tables
        ReportIncome::where('report_id', $id)->delete();
        ReportOutcome::where('report_id', $id)->delete();
        
        $notification = array(
            'message' => 'Report Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('report.all')->with($notification);
    }

    public function MonthlyReport() {
        $reports = Report::orderBy('period', 'DESC')->get();
        return view('backend.report.monthly_report', compact('reports'));
    }

    public function MonthlyReportPrint($id) {
        $members = Member::orderBy('id', 'ASC')->get();
        $report = Report::with('report_incomes', 'report_outcomes')->findOrFail($id);

        // data
        $data = ReportIncome::where('report_id', $id)->groupBy('member_id')->selectRaw('sum(amount) as sum, member_id')->pluck('sum', 'member_id');
        $income_data = $data->tojson();
        $member_income_name = [];
        $member_id_income_arr = array_keys($data->toArray());
        foreach($member_id_income_arr as $item) {
            $mem_name = Member::findOrFail($item)->name;
            array_push($member_income_name, $mem_name);
        }
        $member_income_name = json_encode($member_income_name);

        // data
        $raw_data = ReportOutcome::where('report_id', $id)->groupBy('member_id')->selectRaw('sum(amount) as sum, member_id')->pluck('sum', 'member_id');
        $outcome_data = $raw_data->tojson();
        $member_outcome_name = [];
        $member_id_outcome_arr = array_keys($raw_data->toArray());
        foreach($member_id_outcome_arr as $item) {
            $mem_name = Member::findOrFail($item)->name;
            array_push($member_outcome_name, $mem_name);
        }
        $member_outcome_name = json_encode($member_outcome_name);

        // data
        $member_gain_name = [];
        $member_gain_data = [];
        foreach($members->toArray() as $item) {
            array_push($member_gain_name, $item['name']);

            $total_income = 0;
            if (ReportIncome::where('report_id', $id)->where('member_id', $item['id'])->first()) {
                $total_income = ReportIncome::where('report_id', $id)->where('member_id', $item['id'])->sum('amount');
            }
            $total_outcome = 0;
            if (ReportOutcome::where('report_id', $id)->where('member_id', $item['id'])->first()) {
                $total_outcome = ReportOutcome::where('report_id', $id)->where('member_id', $item['id'])->sum('amount');
            }

            $gain = $total_income - $total_outcome;
            array_push($member_gain_data, $gain);
        }

        $member_gain_name = json_encode($member_gain_name);
        $member_gain_data = json_encode($member_gain_data);

        return view('backend.pdf.monthly_report_pdf', compact('report', 'members', "income_data", "outcome_data", "member_income_name", "member_outcome_name", "member_gain_name", "member_gain_data"));
    }

    public function PeriodReportList() {
        $reports = Report::orderBy('period', 'DESC')->get();
        return view('backend.report.period_report_list', compact('reports'));
    }

    public function PeriodListPdf() {
        $reports = Report::orderBy('period', 'DESC')->get();

        // data
        $reports_raw = Report::orderBy('period', 'ASC')->get();
        $periods = [];
        foreach($reports_raw->toArray() as $key => $period) {
            array_push($periods, date('F-Y', strtotime($period['period'])));
        }
        $periods = json_encode($periods);

        $income_amount = [];
        foreach($reports_raw->toArray() as $key => $period) {
            array_push($income_amount, $period['total_income_amount']);
        }
        $income_amount = json_encode($income_amount);

        $outcome_amount = [];
        foreach($reports_raw->toArray() as $key => $period) {
            array_push($outcome_amount, $period['total_outcome_amount']);
        }
        $outcome_amount = json_encode($outcome_amount);

        $gain_amount = [];
        foreach($reports_raw->toArray() as $key => $period) {
            array_push($gain_amount, $period['total_income_amount'] - $period['total_outcome_amount']);
        }
        $gain_amount = json_encode($gain_amount);

        return view('backend.pdf.period_report_list_pdf', compact('reports', 'periods', 'income_amount', 'outcome_amount', 'gain_amount'));
    }

    public function MemberWiseReport() {
        $members = Member::orderBy('id', 'ASC')->get();
        return view('backend.report.member_wise_report', compact('members'));
    }

    public function MemberWiseReportPdf(Request $request) {
        $member_id = $request->member_id;
   
        $income_amount = ReportIncome::where('member_id', $member_id)->groupBy('report_id')->selectRaw('sum(amount) as sum, report_id')->pluck('sum', 'report_id');
        $outcome_amount = ReportOutcome::where('member_id', $member_id)->groupBy('report_id')->selectRaw('sum(amount) as sum, report_id')->pluck('sum', 'report_id');

        // data
        $income_periods = [];
        foreach($income_amount->toArray() as $key => $amount) {
            array_push($income_periods, Report::findOrFail($key)->period);
        }
        $income_periods = json_encode($income_periods);
        $income_amount_json = $income_amount->tojson();


        $outcome_periods = [];
        foreach($outcome_amount->toArray() as $key => $amount) {
            array_push($outcome_periods, Report::findOrFail($key)->period);
        }
        $outcome_periods = json_encode($outcome_periods);
        $outcome_amount_json = $outcome_amount->tojson();

        // gain amout
        $gain_amount = [];
        foreach($income_amount->toArray() as $key => $amount) {
            $out_amount = ReportOutcome::where('report_id', $key)->where('member_id', $member_id)->sum('amount');
            array_push($gain_amount, $amount - $out_amount);
        }
        $gain_amount = json_encode($gain_amount);
    
        return view('backend.pdf.member_wise_report_pdf', compact('income_amount', 'outcome_amount', 'member_id', 'income_periods', 'outcome_periods', 'income_amount_json', 'outcome_amount_json', 'gain_amount'));
    }
}
