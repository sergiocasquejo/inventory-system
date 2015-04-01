@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
        <header class="panel-heading">
            Customer information
            <a class="btn btn-primary btn-xs pull-right" data-toggle="modal" href="#partialPaymentModal">Make Partial Payment</a>
        </header>
        <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>:
                    {{ !$customer->branch?'':$customer->branch->address }}
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Customer Name</label>
                        {{ Input::old('customer_name', $customer->customer_name) }}
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Address</label>{{ $customer->address }}
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Contact Number</label>{{ $customer->contact_no }}
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Credits</label>
                        <h3>{{ \Helper::nf($customer->total_credits) }}</h3>
                </div>
        </div>
    </section>
    <!-- page end-->
    @include ('admin._partials.payment_form', ['customer_id' => $customer->customer_id])
@stop