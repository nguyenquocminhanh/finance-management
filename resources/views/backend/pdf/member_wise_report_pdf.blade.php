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
                 
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    @php 
                                        $member = App\Models\Member::findOrfail($member_id);
                                    @endphp
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>{{ $member->name }}</strong></h3>
                                    </div>

                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        
                                                        <td class="text-center"><strong>Period</strong></td>
                                                        <td class="text-center"><strong>Total Income</strong></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            
                                                    @foreach($income_amount as $key => $amount)
                                                    <tr>
                                                        @php
                                                            $period = date('F-y', strtotime(App\Models\Report::findOrFail($key)->period));
                                                        @endphp
                                                        <td class="text-center">{{ $period }}</td>
                                                        <td class="text-center">${{ $amount }}</td>
                                                    </tr>
                                                    @endforeach
        
                                                
                                      

                                                    <tr>
                                                        <td class="text-center"><strong></strong></td>
                                                        <td class="text-center"><strong>Total Outcome</strong></td>                                                   
                                                    </tr>
                                             
                            
                                                    @foreach($outcome_amount as $key => $amount)
                                                    <tr>
                                                        @php
                                                            $period = date('F-y', strtotime(App\Models\Report::findOrFail($key)->period));
                                                        @endphp
                                                        <td class="text-center">{{ $period }}</td>
                                                        <td class="text-center">${{ $amount }}</td>
                                                    </tr>
                                                    @endforeach

                                                    <tr>
                                                        <td class="text-center"><strong></strong></td>
                                                        <td class="text-center"><strong>Total Gain</strong></td>                                                   
                                                    </tr>
                                             
                            
                                                    @foreach($income_amount as $key => $in_amount)
                                                    <tr>
                                                        @php
                                                            $period = date('F-y', strtotime(App\Models\Report::findOrFail($key)->period));
                                                            $out_amount = App\Models\ReportOutcome::where('member_id', $member_id)->where('report_id', $key)->sum('amount');
                                                        @endphp
                                                        <td class="text-center">{{ $period }}</td>
                                                        <td class="text-center">${{ $in_amount - $out_amount }}</td>
                                                    </tr>
                                                    @endforeach
        
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <canvas id="myIncomeChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <canvas id="myOutcomeChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <canvas id="myGainChart"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div> 

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
    var coloR = [];
    
    var dynamicColors = function() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    };

    for (var i in {!!$income_periods!!}) {
        coloR.push(dynamicColors());
    }

    const data = {
        labels: {!!$income_periods!!},

        datasets: [{
            data: Object.values({!!$income_amount_json!!}),
            backgroundColor: coloR,
            borderColor: coloR,
            borderWidth: 1,
        }]
    };
  
    const config = {
        type: 'line',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: "Line Chart For Income"
                },
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: (item) => (`$${item.parsed.y}`)
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
        labels: {!!$outcome_periods!!},

        datasets: [{
            data: Object.values({!!$outcome_amount_json!!}),
            backgroundColor: coloR,
            borderColor: coloR,
            borderWidth: 1,
        }]
    };
  
    const config_2 = {
        type: 'line',
        data: data_2,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: "Line Chart For Outcome"
                },
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: (item) => (`$${item.parsed.y}`)
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
        labels: {!!$income_periods!!},

        datasets: [{
            data: {!!$gain_amount!!},
            backgroundColor: coloR,
            borderColor: coloR,
            borderWidth: 1,
        }]
    };
  
    const config_3 = {
        type: 'line',
        data: data_3,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: "Line Chart For Capital Gain"
                },
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: (item) => (`$${item.parsed.y}`)
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