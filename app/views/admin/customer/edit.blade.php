@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
        <header class="panel-heading">
            Create Branch
        </header>
        <div class="panel-body">
            <form action="{{ route('admin_customers.update', $customer->customer_id) }}"  class="form-horizontal tasi-form" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input name="_method" type="hidden" value="PUT">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branches</label>
                    <div class="col-sm-10">
                        {{ Form::select('branch_id', $branches, Input::old('branch_id', $customer->branch_id), ['class' => 'form-control m-bot15']) }}
                        <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Customer Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="customer_name" class="form-control" value="{{ Input::old('customer_name', $customer->customer_name) }}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Address</label>
                    <div class="col-sm-10">
                        <input type="text" name="address" class="form-control" value="{{ Input::old('address', $customer->address) }}" />
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Contact Number</label>
                    <div class="col-sm-10">
                        <input type="text" name="contact_number" class="form-control" value="{{ Input::old('contact_number', $customer->contact_no) }}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Credits</label>
                    <div class="col-sm-10">
                        <input type="number" step="any" name="total_credits" class="form-control" value="{{ Input::old('total_credits', $customer->total_credits) }}" />
                    </div>
                </div>


                <button type="submit" class="btn btn-shadow btn-primary">Update</button>
            </form>
        </div>
    </section>
    <!-- page end-->
@stop