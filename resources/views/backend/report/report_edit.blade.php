@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ route('report.all') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>

                        <h4 class="card-title">Add Monthly Report Page</h4>
                        <br>

                        <h5 class="card-title">Monthly Income</h5>

                        <div class="row">

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Period</label>
                                    <input class="form-control period" type="month" value="{{ $report->period }}" name="period" id="period" style="background-color: #ddd">
                                </div>
                            </div>

                        
                            <div class="col-md-3 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Member Name</label>
                                    <select id="member_id_income" name="member_id" class="form-select select2" aria-label="Default select example">
                                        <option value="" selected="">Select Member</option>
                                        @foreach($members as $mem)
                                            <option value="{{ $mem->id }}">{{ $mem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Income Type</label>
                                    <select id="income_id" name="income_id" class="select2 income_id form-select" aria-label="Default select example">
                                        <option value="" selected="">Select Income</option>
                                        @foreach($incomes as $income)
                                            <option value="{{ $income->id }}">{{ $income->name }}</option>
                                        @endforeach
                        
                                        <option value="0">New Income</option>
                                    </select>
                        
                                    <!-- Hide Add Income Form -->
                                    <div class="row new_income mt-3" style="display: none;">
                                        <div class="form-group col-md-12">
                                            <input type="text" name="new_income_name" id="new_income_name" class="form-control" placeholder="Income Type">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Amount($)</label>
                                    <input class="form-control" type="number" name="current_income_amount" value="0" id="current_income_amount">
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label" style="margin-top: 43px;"></label>
                                    <!-- <input type="submit" class="btn btn-secondary btn-rounded waves-effect waves-light" value="Add More"> -->
                                    <i class="btn btn-secondary btn-rounded waves-effect waves-light fas fa-plus-circle addincomemore"> Add More</i>
                                </div>
                            </div>
                        </div>

                        <br>

                        <h5 class="card-title">Monthly Outcome</h5>

                        <div class="row">

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Period</label>
                                    <input class="form-control period" type="month" value="{{ $report->period }}" id="period" style="background-color: #ddd">
                                </div>
                            </div>

                        
                            <div class="col-md-3 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Member Name</label>
                                    <select id="member_id_outcome" name="member_id" class="form-select select2" aria-label="Default select example">
                                        <option value="" selected="">Select Member</option>
                                        @foreach($members as $mem)
                                            <option value="{{ $mem->id }}">{{ $mem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Outcome Type</label>
                                    <select id="outcome_id" name="outcome_id" class="form-select select2" aria-label="Default select example">
                                        <option value="" selected="">Select Outcome</option>
                                        @foreach($outcomes as $outcome)
                                            <option value="{{ $outcome->id }}">{{ $outcome->name }}</option>
                                        @endforeach
                                        <option value="0">New Outcome</option>
                                    </select>

                                    <!-- Hide Add Customer Form -->
                                    <div class="row new_outcome mt-3" style="display: none;">
                                        <div class="form-group col-md-12">
                                            <input type="text" name="new_outcome_name" id="new_outcome_name" class="form-control" placeholder="Outcome Type">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Amount($)</label>
                                    <input class="form-control" type="number" name="current_outcome_amount" value="0" id="current_outcome_amount">
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label" style="margin-top: 43px;"></label>
                                    <!-- <input type="submit" class="btn btn-secondary btn-rounded waves-effect waves-light" value="Add More"> -->
                                    <i class="btn btn-secondary btn-rounded waves-effect waves-light fas fa-plus-circle addoutcomemore"> Add More</i>
                                </div>
                            </div>
                        </div>

                        <br><br>

                        <h5 class="card-title">Summary Income</h5>
                        <form id="myForm" method="POST" action="{{ route('report.update') }}">
                            @csrf

                            <!-- hidden Input -->
                            <input name="period" id="hidden_period" value="{{ $report->period }}" type="hidden">
                            <input name="id" id="report_id" value="{{ $report->id }}" type="hidden">

                            <!-- table -->
                            <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                <thead>
                                    <tr>
                                        <th width="35%">Member</th>
                                        <th width="35%">Income Type</th>
                                        <th width="23%">Amount($)</th>
                                        <th width="7%">Action</th> 
                                    </tr>
                                </thead>

                                

                                <tbody id="addRowIncome" class="addRowIncome">
                                    @php
                                        $report_incomes = App\Models\ReportIncome::where('report_id', $report->id)->orderBy('member_id', 'ASC')->get();
                                    @endphp
                                    @foreach($report_incomes as $report_income)
                                    <tr class="delete_add_more_item" id="delete_add_more_item">
                                        <!-- hidden de store vao database -->
                                        <td>
                                            <input type="hidden" name="new_income_name[]" value="{{ $report_income['income']['name'] }}">
                                            <input type="hidden" name="member_id_income[]" value="{{ $report_income->member_id }}">
                                            {{ $report_income['member']['name'] }}
                                        </td>
                                        
                                        <td>
                                            <input type="hidden" name="income_id[]" value="{{ $report_income->income_id }}">
                                            {{ $report_income['income']['name'] }}
                                        </td>

                                        <td>
                                            <input type="number" min="0" class="form-control income_amount text-right" name="income_amount[]" value="{{ $report_income->amount }}">
                                        </td>

                                        <td>
                                            <i class="btn btn-danger btn-sm fas fa-window-close removeincome"></i>
                                        </td>
                                    </tr>    
                                    @endforeach


                                </tbody>

                                <tr>
                                    <!-- chiem 2 cot -->
                                    <td colspan="2" style="font-weight: 600;">Total Income</td>
                                    <td>
                                        <input type="text" name="total_amount_income" value="{{ $report->total_income_amount }}" id="total_amount_income" class="form-control total_amount_income" readonly style="background-color: #ddd;" >
                                    </td>
                                </tr>
                            </table>

                            <br>

                            <!-- table -->
                            <h5 class="card-title">Summary Outcome</h5>
                            <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                <thead>
                                    <tr>
                                        <th width="35%">Member</th>
                                        <th width="35%">Outcome Type</th>
                                        <th width="23%">Amount($)</th>
                                        <th width="7%">Action</th> 
                                    </tr>
                                </thead>

                               
                                <tbody id="addRowOutcome" class="addRowOutcome">
                                    @php
                                        $report_outcomes = App\Models\ReportOutcome::where('report_id', $report->id)->orderBy('member_id', 'ASC')->get();
                                    @endphp
                                    @foreach($report_outcomes as $report_outcome)
                                    <tr class="delete_add_more_item" id="delete_add_more_item">
                                        <!-- hidden de store vao database -->
                                        <td>
                                            <input type="hidden" name="new_outcome_name[]" value="{{ $report_outcome['outcome']['name'] }}">
                                            <input type="hidden" name="member_id_outcome[]" value="{{ $report_outcome->member_id }}">
                                            {{ $report_outcome['member']['name'] }}
                                        </td>
                                        
                                        <td>
                                            <input type="hidden" name="outcome_id[]" value="{{ $report_outcome->outcome_id }}">
                                            {{ $report_outcome['outcome']['name'] }}
                                        </td>

                                        <td>
                                            <input type="number" min="0" class="form-control outcome_amount text-right" name="outcome_amount[]" value="{{ $report_outcome->amount }}">
                                        </td>

                                        <td>
                                            <i class="btn btn-danger btn-sm fas fa-window-close removeoutcome"></i>
                                        </td>
                                    </tr>    
                                    @endforeach
                                </tbody>

                                <tbody>
     
                                    <tr>
                                        <!-- chiem 2 cot -->
                                        <td colspan="2" style="font-weight: 600;">Total Outcome</td>
                                        <td>
                                            <input type="text" name="total_amount_outcome" value="{{ $report->total_outcome_amount }}" id="total_amount_outcome" class="form-control total_amount_outcome" readonly style="background-color: #ddd;" >
                                        </td>
                                    </tr>

                                </tbody>       
                                            
                            </table>
                                
                            <br><br>

                            <h5 class="card-title">Description</h5>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <textarea name="description" class="form-control" id="description" placeholder="Write Description Here">{{ $report->description }}</textarea>
                                </div>
                            </div>

                            <br>
                        
                            <center>
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Report">
                            </center>
                        </form>
                        
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>



<!-- template -->
<script id="document-template-income" type="text/x-handlebars-template">
    <tr class="delete_add_more_item" id="delete_add_more_item">
        <!-- hidden de store vao database -->
        <td>
            <input type="hidden" name="new_income_name[]" value="@{{income_name}}">
            <input type="hidden" name="member_id_income[]" value="@{{member_id_income}}">
            @{{ member_name }}
        </td>
        
        <td>
            <input type="hidden" name="income_id[]" value="@{{income_id}}">
            @{{ income_name }}
        </td>

        <td>
            <input type="number" min="0" class="form-control income_amount text-right" name="income_amount[]" value="@{{ income_amount }}">
        </td>

        <td>
            <i class="btn btn-danger btn-sm fas fa-window-close removeincome"></i>
        </td>
    </tr>    
</script>

<!-- template -->
<script id="document-template-outcome" type="text/x-handlebars-template">
    <tr class="delete_add_more_item" id="delete_add_more_item">
        <!-- hidden de store vao database -->
        <td>
            <input type="hidden" name="new_outcome_name[]" value="@{{outcome_name}}">
            <input type="hidden" name="member_id_outcome[]" value="@{{member_id_outcome}}">
            @{{ member_name }}
        </td>
        
        <td>
            <input type="hidden" name="outcome_id[]" value="@{{outcome_id}}">
            @{{ outcome_name }}
        </td>

        <td>
            <input type="number" min="0" class="form-control outcome_amount text-right" name="outcome_amount[]" value="@{{ outcome_amount }}">
        </td>

        <td>
            <i class="btn btn-danger btn-sm fas fa-window-close removeoutcome"></i>
        </td>
    </tr>    
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("click", ".addincomemore", function(){
            var member_id_income = $('#member_id_income').val();
            var member_name = $('#member_id_income').find('option:selected').text();
            var income_id = $('#income_id').val();

            if (income_id != 0) { //khac new income
                var income_name = $('#income_id').find('option:selected').text();
            } else {        // new income value
                var income_name = $('#new_income_name').val();
            }
            var income_amount = $('#current_income_amount').val();

            if(member_id_income == '') {
                $.notify("Member Field is required", {globalPosition: 'top right', className: 'error'});
                return false;
            }
            if(income_id == '' || (income_id == 0 && income_name == '')) {
                $.notify("Income Field is required", {globalPosition: 'top right', className: 'error'});
                return false;
            } 

            var source = $("#document-template-income").html();
            var template = Handlebars.compile(source);
            var data = {
                // pass vao @
                member_id_income: member_id_income,
                member_name: member_name,
                income_id: income_id,               // income_id == 0 la new income
                income_name: income_name,
                income_amount: income_amount
            }
            // data pass vao @
            var html = template(data);
            $('#addRowIncome').append(html);
            totalIncomePrice();
        });

        $(document).on("click", ".addoutcomemore", function(){
            var member_id_outcome = $('#member_id_outcome').val();
            var member_name = $('#member_id_outcome').find('option:selected').text();
            var outcome_id = $('#outcome_id').val();

            if (outcome_id != 0) { //khac new outcome
                var outcome_name = $('#outcome_id').find('option:selected').text();
            } else {        // new income value
                var outcome_name = $('#new_outcome_name').val();
            }
            var outcome_amount = $('#current_outcome_amount').val();

            if(member_id_outcome == '') {
                $.notify("Member Field is required", {globalPosition: 'top right', className: 'error'});
                return false;
            }
            if(outcome_id == '' || (outcome_id == 0 && outcome_name == '')) {
                $.notify("Outcome Field is required", {globalPosition: 'top right', className: 'error'});
                return false;
            }

            var source = $("#document-template-outcome").html();
            var template = Handlebars.compile(source);
            var data = {
                // pass vao @
                member_id_outcome: member_id_outcome,
                member_name: member_name,
                outcome_id: outcome_id,
                outcome_name: outcome_name,
                outcome_amount: outcome_amount
            }
            // data pass vao @
            var html = template(data);
            $('#addRowOutcome').append(html);
            totalOutcomePrice();
        });

        // change period -> change 2 input
        $(document).on("change", "#period", function(){
            var date = $(this).val();
            $('.period').val(date);
            $('#hidden_period').val(date);

            // check period report exists or not
            var report_id = $('#report_id').val();
    
            $.ajax({
                url: "{{ route('check-exist-period-for-edit') }}",
                type: "GET",
                // value truyen vao route
                data: {period: date, report_id: report_id},
                // data tra ve tu route
                success: function(data){
                    if (data == true) {
                        $.notify("There exists report for this month already!", {globalPosition: 'top right', className: 'error'});
                        return false;
                    }
                }
            });
        });


        // remove income item
        $(document).on("click", ".removeincome", function(event){
            $(this).closest(".delete_add_more_item").remove();

            totalIncomePrice();
        })

        // remove outcome item
        $(document).on("click", ".removeoutcome", function(event){
            $(this).closest(".delete_add_more_item").remove();

            totalOutcomePrice();
        })

        // calculate total price for one unit
        $(document).on('keyup click', '.income_amount', function(){
            totalIncomePrice();
        })

        // calculate total price for one unit
        $(document).on('keyup click', '.outcome_amount', function(){
            totalOutcomePrice();
        })

        // calculate total sum price
        function totalIncomePrice() {
            var sum = 0;
            $(".income_amount").each(function(){
                var value = $(this).val();
                if(!isNaN(value) && value.length !=0) {
                    sum += parseFloat(value);
                }
            });

            // display total price
            $('#total_amount_income').val(sum);
        }

        // calculate total sum price
        function totalOutcomePrice() {
            var sum = 0;
            $(".outcome_amount").each(function(){
                var value = $(this).val();
                if(!isNaN(value) && value.length !=0) {
                    sum += parseFloat(value);
                }
            });

            // display total price
            $('#total_amount_outcome').val(sum);
        }
    })
</script>

<!-- select Income Type -> show Amount tuong ung -->
<script type="text/javascript">
    $(function(){
        $(document).on('change', '#income_id, #member_id_income', function() {
            var member_id_income = $('#member_id_income').val();
            var income_id = $('#income_id').val();

            if ($('#income_id').val() == '' || $('#member_id_income').val() == '') {        // fail
                $('.addincomemore').css({"backgroundColor":"#6c757d", "borderColor": "#6c757d"});
            } else {            // success
                $('.addincomemore').css({"backgroundColor":"#6fd088", "borderColor": "#6fd088"});
            }

            if($('#income_id').val() == '0') {
                $(".new_income").show();

                if ( $('#new_income_name').val() == '') {       // fail
                    $('.addincomemore').css({"backgroundColor":"#6c757d", "borderColor": "#6c757d"})
                }

                $(document).on('keyup', '#new_income_name', function() {
                    if ( $('#new_income_name').val() == '' || $('#member_id_income').val() == '') {
                        $('.addincomemore').css({"backgroundColor":"#6c757d", "borderColor": "#6c757d"})        // fail
                    } else {        // success
                        $('.addincomemore').css({"backgroundColor":"#6fd088", "borderColor": "#6fd088"});
                    }
                })

            } else {
                $(".new_income").hide();
                // check amount
                $.ajax({
                    url: "{{ route('check-income-amount') }}",
                    type: "GET",
                    // value truyen vao route
                    data: {member_id_income: member_id_income, income_id: income_id},
                    // data tra ve tu route
                    // data tra ve tu table Product
                    success: function(data){
                        $('#current_income_amount').val(data);
                    }
                });
            }
        });
    });
</script>

<!-- select Outcome Type -> show Amount tuong ung -->
<script type="text/javascript">
    $(function(){
        $(document).on('change', '#outcome_id, #member_id_outcome', function() {
            var member_id_outcome = $('#member_id_outcome').val();
            var outcome_id = $('#outcome_id').val();

            if ($('#outcome_id').val() == '' || $('#member_id_outcome').val() == '') {          // fail
                $('.addoutcomemore').css({"backgroundColor":"#6c757d", "borderColor": "#6c757d"});
            } else {            // success
                $('.addoutcomemore').css({"backgroundColor":"#6fd088", "borderColor": "#6fd088"});
            }
      
            if($('#outcome_id').val() == '0') {
                $(".new_outcome").show();

                if ( $('#new_outcome_name').val() == '') {       // fail
                    $('.addoutcomemore').css({"backgroundColor":"#6c757d", "borderColor": "#6c757d"})
                }

                $(document).on('keyup', '#new_outcome_name', function() {
                    if ( $('#new_outcome_name').val() == '' || $('#member_id_outcome').val() == '') {
                        $('.addoutcomemore').css({"backgroundColor":"#6c757d", "borderColor": "#6c757d"})        // fail
                    } else {        // success
                        $('.addoutcomemore').css({"backgroundColor":"#6fd088", "borderColor": "#6fd088"});
                    }
                })
            } else {
                $(".new_outcome").hide();
                $.ajax({
                    url: "{{ route('check-outcome-amount') }}",
                    type: "GET",
                    // value truyen vao route
                    data: {member_id_outcome: member_id_outcome, outcome_id: outcome_id},
                    // data tra ve tu route
                    // data tra ve tu table Product
                    success: function(data){
                        $('#current_outcome_amount').val(data);
                    }
                });
            }
        });
    });
</script>

@endsection