@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">All Period Report List</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <a href="{{ route('period.list.pdf') }}" target="_blank" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-print"> Print All Period Report List</i></a>
                    <br></br>

                    <h4 class="card-title">All Period Report Data</h4>
                    
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Period</th>
                                    <th>Total Income</th>
                                    <th>Total Outcome</th>
                                    <th>Total Gain</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($reports as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ date('F-Y', strtotime($item->period)) }}</td>
                                    <td>{{ $item->total_income_amount }}</td>
                                    <td>{{ $item->total_outcome_amount }}</td>
                                    <td>{{ $item->total_income_amount -  $item->total_outcome_amount}}</td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>

@endsection