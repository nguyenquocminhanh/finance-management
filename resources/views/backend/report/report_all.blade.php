@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Monthly Report All</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <a href="{{ route('report.add') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-plus-circle"> Add Monthly Report</i></a>

                    <br></br>

                    <h4 class="card-title">Monthly Report Data</h4>
                    
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Period</th>
                                    <th>Description</th>
                                    <th>Created Date</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($reports as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ date('F-Y', strtotime($item->period)) }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ date('m-d-Y', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route('report.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('report.delete', $item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash-alt"></i></a>
                                    </td>
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