@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('income.all') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>
                        <h4 class="card-title">Edit Income Page</h4>
                        <br></br>
                       
                        <form id="myForm" method="POST" action="{{ route('income.update') }}">
                            @csrf

                            <input type="hidden" name="id" value="{{ $income->id }}">
                            
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Income Type Name</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="name" placeholder="Ex: Sallary" value="{{ $income->name }}">
                                </div>
                            </div>

                            <br><br>

                            <!-- table -->

                            <table class="table-sm table-bordered" width="75%" style="border-color: #ddd;">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Amount ($)</th>
                                        <th width="7%">Action</th> 
                                    </tr>
                                </thead>

                                <tbody id="addRow" class="addRow">
                                    @foreach ($members as $mem)
                                        <tr class="delete_add_more_item" id="delete_add_more_item">
                                            <!-- hidden de store vao database // do hidden user ko nhap duoc nen phai co value -->
                                            <!-- <input type="hidden" name="date" value="@{{date}}">
                                            <input type="hidden" name="invoice_number" value="@{{invoice_number}}"> -->

                                            <td>
                                                <input type="hidden" name="member_id[]" value="{{ $mem->id }}">
                                                {{ $mem->name }}
                                            </td>

                                            @php
                                                if(App\Models\IncomeDetail::where('income_id', $income->id)
                                                    ->where('member_id', $mem->id)->first()) {
                                                        $amount_value = App\Models\IncomeDetail::where('income_id', $income->id)
                                                            ->where('member_id', $mem->id)->first()->amount;
                                                } else {
                                                    $amount_value = 0;
                                                }
                                            @endphp

                                            <td>
                                                <input type="number" value="{{ $amount_value }}" min="0" class="form-control amount text-right" name="amount[]" placeholder="0">
                                            </td>

                                            <td>
                                                <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
                                            </td>
                                        </tr>    
                                    @endforeach
                                </tbody>

                                <tbody>
                                    <!-- <tr>
                                        <td>All Members</td>

                                        <td>
                                            <input type="text" name="discount_amount" value="0" id="discount_amount" class="form-control estimated_amount" placeholder="Discount Amount" >
                                        </td>
                                    </tr> -->

                                    <tr>
                                        <!-- chiem 4 cot -->
                                        <td>Total</td>
                                        <td colspan="2">
                                            <input type="text" name="total_amount" value="{{ $income->total_amount }}" id="total_amount" class="form-control total_amount" readonly style="background-color: #ddd;" >
                                        </td>
                                    </tr>

                                </tbody>                
                            </table>
                                
                            <br>
                        
                            <center>
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Income">
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
                }
            },
            messages :{
                name: {
                    required : 'Please Enter Income Type Name',
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


        // calculate total price for one unit
        $(document).on('keyup click', '.amount', function(){
            totalAmountPrice();
        })

        // calculate total sum price
        function totalAmountPrice() {
            var sum = 0;
            $(".amount").each(function(){
                var value = $(this).val();
                if(!isNaN(value) && value.length !=0) {
                    sum += parseFloat(value);
                }
            });

            // display total price
            $('#total_amount').val(sum);
        }
    });
</script>

@endsection