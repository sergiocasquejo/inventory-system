@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
        <header class="panel-heading">
            Edit Payable
        </header>
        <div class="panel-body">
            <form action="{{ route('admin_credits.payables_update', $supplier->supplier_id) }}"  class="form-horizontal tasi-form" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="_method" value="PUT" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-10">
                        <strong>{{  !$supplier->branch?'':$supplier->branch->address  }}</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Supplier</label>
                    <div class="col-sm-10">
                        <strong>{{ $supplier->supplier_name  }}</strong>
                    </div>
                </div>



                <div class="form-group">
                    <label class="col-sm-2 control-label">Total Payables</label>
                    <div class="col-sm-10">
                        <input type="number" step="any" name="total_payables" class="form-control" value="{{ Input::old('total_payables', $supplier->total_payables) }}" />
                    </div>
                </div>
                <button type="submit" class="btn btn-shadow btn-primary">Update</button>
            </form>
        </div>
    </section>
    <!-- page end-->
@stop