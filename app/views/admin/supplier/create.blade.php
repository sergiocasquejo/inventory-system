@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
        <header class="panel-heading">
            Create Supplier
        </header>
        <div class="panel-body">
            <form action="{{ route('admin_suppliers.store') }}"  class="form-horizontal tasi-form" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group">
                    <label class="col-sm-2 control-label">Supplier Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="supplier_name" class="form-control" value="{{ Input::old('supplier_name') }}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Location</label>
                    <div class="col-sm-10">
                        {{ Form::select('location', $branches, Input::old('location'), ['class' => 'form-control m-bot15', (!\Confide::user()->isAdmin() ? 'disabled="disabled"' : '')]) }}
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Contact Number</label>
                    <div class="col-sm-10">
                        <input type="text" name="contact_no" class="form-control" value="{{ Input::old('contact_no') }}" />
                    </div>
                </div>
                <button type="submit" class="btn btn-shadow btn-primary">Create</button>
            </form>
        </div>
    </section>
    <!-- page end-->
@stop