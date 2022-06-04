@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Member Wise Report</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Member Wise Report</h4>
                    
                        <div class="show_member">
                            <form method="GET" action="{{ route('member.wise.report.pdf') }}" id="myForm" target="_blank">
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>Member Name</label>
                 
                                        <select id="member_id" name="member_id" class="form-select select2" aria-label="Default select example">
                                            <option value="">Select Member</option>
                                            @foreach($members as $mem)
                                            <option value="{{ $mem->id }}">{{ $mem->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4" style="padding-top: 28px;">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>

@endsection