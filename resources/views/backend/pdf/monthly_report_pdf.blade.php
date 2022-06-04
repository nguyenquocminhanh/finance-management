@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16"><strong>Period: {{ date('F-Y', strtotime($report->period)) }}</strong></h4>
                                    <h3>
                                        <img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="logo" height="24"/> Happy Family
                                    </h3>
                                </div>
                                <hr>
                                
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>Happy Family</strong>
                                            <br>
                                            9Lonsdale St, Apt 3
                                            <br>
                                            Boston, MA
                                            <br>
                                            minhanh.nguyenquoc@gmail.com
                                        </address>
                                    </div>
                                    <div class="col-6 mt-4 text-end">
                                        <address>
                                            <strong>Last Updated:</strong>
                                            <br>
                                            {{ date('Y-m-d', strtotime($report->updated_at)) }}
                                            <br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Income Data</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>SL</strong></td>
                                                    <td class="text-center"><strong>Member Name</strong></td>
                                                    <td class="text-center"><strong>Income Type</strong></td>
                                                    <td class="text-center"><strong>Amount</strong></td>
                                                   
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    
                                                    $total_income_sum = '0';
                                                @endphp

                                                @foreach($report['report_incomes'] as $key => $income)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td class="text-center">{{ $income['member']['name'] }}</td>
                                                    <td class="text-center">{{ $income['income']['name'] }}</td>
                                                    <td class="text-center">${{ $income->amount }}</td>
                                                </tr>

                                                @php 
                                                    $total_income_sum += $income->amount;
                                                @endphp
                                                @endforeach

                                                @php
                                                    $incomes = App\Models\ReportIncome::where('report_id', $report->id)
                                                        ->with('member')->select('member_id')->groupBy('member_id')->get();
                                                @endphp

                                                @foreach ($incomes as $key => $income)
                                                    @php 
                                                        $total_income = App\Models\ReportIncome::where('report_id', $report->id)
                                                            ->where('member_id', $income['member']['id'])->sum('amount');
                                                    @endphp
                                                    <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line text-center">
                                                            <strong>Income {{ $income['member']['name'] }}</strong></td>
                                                        <td class="thick-line text-center">${{ $total_income }}</td>
                                                    </tr>

                                                @endforeach


                                                <tr>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line text-center">
                                                        <strong>Family Income</strong></td>
                                                    <td class="thick-line text-center">${{ $total_income_sum }}</td>
                                                </tr>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="col-3">
                                <div class="card">
                                    <canvas id="myIncomeChart"></canvas>
                                </div>
                            </div>
                       


                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Outcome Data</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>SL</strong></td>
                                                    <td class="text-center"><strong>Member Name</strong></td>
                                                    <td class="text-center"><strong>Outcome Type</strong></td>
                                                    <td class="text-center"><strong>Amount</strong></td>
                                                   
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    
                                                    $total_outcome_sum = '0';
                                                @endphp

                                                @foreach($report['report_outcomes'] as $key => $outcome)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td class="text-center">{{ $outcome['member']['name'] }}</td>
                                                    <td class="text-center">{{ $outcome['outcome']['name'] }}</td>
                                                    <td class="text-center">${{ $outcome->amount }}</td>
                                                </tr>

                                                @php 
                                                    $total_outcome_sum += $outcome->amount;
                                                @endphp
                                                @endforeach

                                                @php
                                                    $outcomes = App\Models\ReportOutcome::where('report_id', $report->id)
                                                        ->with('member')->select('member_id')->groupBy('member_id')->get();
                                                @endphp

                                                @foreach ($outcomes as $key => $outcome)
                                                    @php 
                                                        $total_outcome = App\Models\ReportOutcome::where('report_id', $report->id)
                                                            ->where('member_id', $outcome['member']['id'])->sum('amount');
                                                    @endphp

                                                    <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line text-center">
                                                            <strong>Outcome {{ $outcome['member']['name'] }}</strong></td>
                                                        <td class="thick-line text-center">${{ $total_outcome }}</td>
                                                    </tr>

                                                @endforeach


                                                <tr>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line text-center">
                                                        <strong>Family Outcome</strong></td>
                                                    <td class="thick-line text-center">${{ $total_outcome_sum }}</td>
                                                </tr>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-3">
                                <div class="card">
                                    <canvas id="myOutcomeChart"></canvas>
                                </div>
                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Capital Gain</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>SL</strong></td>
                                                    <td class="text-center"><strong>Member Name</strong></td>
                                                    <td class="text-center"><strong>Gain Amount</strong></td>
                                                   
                                                </tr>
                                                </thead>
                                                <tbody>

            
                                                @foreach ($members as $key => $mem)
                                                    @php 
                                                        if (App\Models\ReportIncome::where('report_id', $report->id)
                                                                ->where('member_id', $mem->id)->first()) {
                                                            $total_income = App\Models\ReportIncome::where('report_id', $report->id)
                                                                ->where('member_id', $mem->id)->sum('amount');
                                                        } else {
                                                            $total_income = 0;
                                                        }

                                                        if (App\Models\ReportOutcome::where('report_id', $report->id)
                                                            ->where('member_id', $mem->id)->get()) {
                                                            $total_outcome = App\Models\ReportOutcome::where('report_id', $report->id)
                                                                ->where('member_id', $mem->id)->sum('amount');
                                                        } else {
                                                            $total_outcome = 0;
                                                        }
                                                        $gain = $total_income - $total_outcome;
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td class="text-center">{{ $mem->name }}</td>
                                                        <td class="text-center">
                                                            <strong>${{ $gain }}</strong>
                                                        </td>
                                                    </tr>

                                                @endforeach

                                                <tr>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line text-center">
                                                        <strong>Family Gain</strong></td>
                                                    <td class="thick-line text-center">
                                                        <strong>${{ $report->total_income_amount - $report->total_outcome_amount }}</strong>
                                                    </td>
                                                </tr>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>       
                            
                            <div class="col-3">
                                <div class="card">
                                    <canvas id="myGainChart"></canvas>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        @php
                            $date = new Datetime('now', new DateTimeZone('America/New_York'));
                        @endphp

                        <i>Printing time: {{ $date->format('F j, Y, g:i a') }}</i>

                        <div class="d-print-none">
                            <div class="float-end">
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">

    const data = {
        labels: {!!$member_income_name!!},

        datasets: [{
            label: 'Pie Chart Income Amount',
            data: Object.values({!!$income_data!!}),
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    };
  
    const config = {
        type: 'doughnut',
        data: data,
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Pie Chart For Income"
                },
                tooltip: {
                    callbacks: {
                        label: (item) => (`${item.label}: $${item.parsed}`)
                    }
                }
            }
            
        }
    };
  
    const myChart = new Chart(
        document.getElementById('myIncomeChart'),
        config
    );  

    const data_2 = {
        labels: {!!$member_outcome_name!!},

        datasets: [{
            label: 'Pie Chart Outcome Amount',
            data: Object.values({!!$outcome_data!!}),
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    };
  
    const config_2 = {
        type: 'doughnut',
        data: data_2,
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Pie Chart For Outcome"
                },
                tooltip: {
                    callbacks: {
                        label: (item) => (`${item.label}: $${item.parsed}`)
                    }
                }
            }
            
        }
    };
  
    const myChart_2 = new Chart(
        document.getElementById('myOutcomeChart'),
        config_2
    );  

    const data_3 = {
        labels: {!!$member_gain_name!!},

        datasets: [{
            label: 'Pie Chart Gain Amount',
            data: {!!$member_gain_data!!},
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    };
  
    const config_3 = {
        type: 'doughnut',
        data: data_3,
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Pie Chart For Capital Gain"
                },
                tooltip: {
                    callbacks: {
                        label: (item) => (`${item.label}: $${item.parsed}`)
                    }
                }
            }
            
        }
    };
  
    const myChart_3 = new Chart(
        document.getElementById('myGainChart'),
        config_3
    );  
</script>


@endsection