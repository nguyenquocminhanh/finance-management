@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Edit Family Member Page</h4>
                        <br></br>
                       
                        <form id="myForm" method="POST" action="{{ route('member.update') }}" enctype="multipart/form-data">
                            @csrf

                             <!-- hidden input -->
                             <input type="hidden" name="id" value="{{ $member->id }}">

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Member Name</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="name" value="{{ $member->name }}">
                                </div>
                            </div>

  
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Member Age</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="age" value="{{ $member->age }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Member Image</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="file" name="image" id="image">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <img id="showImage" class="rounded avatar-lg" src="{{ $member->image != null ? asset($member->image) : url('upload/no_image.jpg') }}" class="card-img-top">
                                </div>
                            </div>

                            <center>
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Member">
                            </center>
                        </form>
                        
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                name: {
                    required : true,
                }, 
                age: {
                    required : true,
                }, 
            },
            messages :{
                name: {
                    required : 'Please Enter Member Name',
                },
                age: {
                    required : 'Please Enter Member Age',
                },
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>


@endsection