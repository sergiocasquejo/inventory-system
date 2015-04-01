@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
        <header class="panel-heading">
            Edit Supplier
        </header>
        <div class="panel-body">
            <form action="{{ route('admin_suppliers.update', $supplier->supplier_id) }}"  class="form-horizontal tasi-form" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="_method" value="PUT" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">Supplier Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="supplier_name" class="form-control" value="{{ Input::old('supplier_name', $supplier->supplier_name) }}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Location</label>
                    <div class="col-sm-10">
                        {{ Form::select('location', $branches, Input::old('location', $supplier->location), ['class' => 'form-control m-bot15', (!\Confide::user()->isAdmin() ? 'disabled="disabled"' : '')]) }}
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Contact Number</label>
                    <div class="col-sm-10">
                        <input type="text" name="contact_no" class="form-control" value="{{ Input::old('contact_no', $supplier->contact_no) }}" />
                    </div>
                </div>
                <button type="submit" class="btn btn-shadow btn-primary">Update</button>
            </form>
        </div>
    </section>
    <!-- page end-->
@stop