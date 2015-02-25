@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
            <form action="{{ route('admin_brands.index') }}"  class="form-horizontal tasi-form" method="GET">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <header class="panel-heading">
                  Credits <a class="btn btn-info btn-xs" href="{{ route('admin_brands.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_brands.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
              </header>
              <div class="dataTables_wrapper form-inline">
                <div class="row">
                  <div class="col-sm-6">
                    <div id="sample_1_length" class="dataTables_length">
                      <label>
                        {{ Form::select('records_per_page', \Config::get('agrivate.records_per_page'), Input::get('records_per_page', 10), ['class' => 'form-control', 'size' => '1', 'onchange' => 'this.form.submit();']) }} 
                        records per page
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    
                    <div class="dataTables_filter">
                        <label class="pull-left">Search: <input type="text" name="s" class="form-control"> </label>
                    </div>
                  </div>
                </div>
                <table class="table table-striped table-advance table-hover">
                    <thead>
                      <tr>
                          <th>Name</th>
                          <th>Description</th>
                          <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      @if ($brands)
                          @foreach ($brands as $brand)
                          <tr>
                              <td>{{{ $brand->name }}}</td>
                              <td>{{{ $brand->description }}}</td>
                              <td>
                                    <a href="{{{ route('admin_brands.edit', $brand->brand_id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                               
                                  <a href="{{{ route('admin_brands.destroy', $brand->brand_id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                    <i class="icon-remove"></i>
                                  </a>
                              </td>
                          </tr>
                          @endforeach
                      @else
                          <tr>
                            <td colspan="8">{{{ \Lang::get('agrivate.empty', 'Brand') }}}</td>
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
                      <label>{{ $brands->appends($appends)->links() }}</label>
                    </div>
                  </div>
                </div>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop