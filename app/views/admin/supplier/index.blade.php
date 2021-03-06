@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <form action="{{ route('admin_suppliers.index') }}"  class="form-inline tasi-form" method="GET">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <header class="panel-heading">
                        Suppliers <a class="btn btn-info btn-xs" href="{{ route('admin_suppliers.create') }}">Add New</a>
                        <a class="btn btn-warning btn-xs" href="{{ route('admin_suppliers.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
                    </header>
                    <div class="dataTables_wrapper form-inline">
                        <br />
                        <div class="col-sm-1">
                            <label>
                                {{ Form::select('records_per_page', \Config::get('agrivet.records_per_page'), Input::get('records_per_page', 10), ['class' => 'form-control', 'size' => '1', 'onchange' => 'this.form.submit();']) }}
                                per page
                            </label>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="s"  value="{{ Input::get('s') }}" placeholder="Search" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-info">Filter</button>
                        </div>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Location</th>
                                <th>Contact No</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @if ($suppliers)
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td><a href="{{ route('admin_suppliers.show', $supplier->supplier_id)  }}">{{{ $supplier->supplier_name }}}</a></td>
                                        <td>{{{ !$supplier->branch? '': $supplier->branch->address }}}</td>
                                        <td>{{{ $supplier->contact_no }}}</td>
                                        <td>
                                            <a href="{{{ route('admin_suppliers.edit', $supplier->supplier_id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                            <a href="{{{ route('admin_suppliers.destroy', ['id' => $supplier->supplier_id, 'remove' => 1]) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                                <i class="icon-remove"></i>
                                            </a>


                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">{{{ \Lang::get('agrivet.empty', ['name' => 'Suppliers']) }}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_length">
                                    {{{ $totalRows }}} entries</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter pagination-sm">
                                    <label>{{ $suppliers->appends($appends)->links() }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <!-- page end-->

@stop