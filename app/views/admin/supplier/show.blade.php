@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
        <header class="panel-heading">
            Supplier information
            <a class="btn btn-primary btn-xs pull-right" data-toggle="modal" href="#partialPaymentModal">Make Partial Payment</a>
        </header>
        <div class="panel-body">

            <div class="form-group">
                <label class="col-sm-2 control-label">Supplier Name</label>
                {{ $supplier->supplier_name }}
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Location</label>:
                {{ $supplier->location }}
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">Contact Number</label>{{ $supplier->contact_no }}
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Payables</label>
                <h3>{{ \Helper::nf($supplier->total_payables) }}</h3>
            </div>
        </div>
    </section>
    <!-- page end-->
    @include ('admin._partials.payables_form', ['supplier_id' => $supplier->supplier_id])
@stop