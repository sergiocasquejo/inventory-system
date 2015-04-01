@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_stocks.index') }}"  class="form-inline tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading m-bot15">
                    Stocks <a class="btn btn-info btn-xs" href="{{ route('admin_stocks.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_stocks.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
                </header>
                  <br />
                  <div class="col-md-12">
                      <div class="form-group">
                          {{ Form::select('records_per_page', \Config::get('agrivet.records_per_page'), Input::get('records_per_page', 10), ['class' => 'form-control', 'size' => '1', 'onchange' => 'this.form.submit();']) }}
                      </div>
                      <div class="form-group">
                          <input type="text" name="s"  value="{{ Input::get('s') }}" placeholder="Search" class="form-control">
                      </div>
                      <div class="form-group">
                          {{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control', 'size' => '1']) }}
                      </div>
                      <div class="form-group">
                          {{ Form::select('brand', $brands, Input::get('brand', ''), ['class' => 'form-control', 'size' => '1']) }}
                      </div>
                      <button type="submit" class="btn btn-info">Filter</button>
                  </div>
                  <table class="table table-striped table-advance table-hover">
                      <thead>
                        <tr>
                            <th>BRANCH</th>
                            <th>PRODUCT NAME</th>
                            <th>NO OF KILO/BTL/PCK/PCS ON HAND</th>
                            <th>NO OF SACKS ON HAND</th>
                            <th>TOTAL NO OF STOCKS</th>
                            <td></td>
                        </tr>
                      </thead>
                      <tbody>
                        @if ($stocks)
                          @foreach ($stocks as $stock)
                          <tr>
                              <td>{{{ $stock['branch'] }}}</td>
                              <td>{{{ $stock['product_name'] }}}</td>
                              <td>{{{ $stock['other_stock'] }}}</td>
                              <td>{{{ $stock['sack_stock'] }}}</td>
                              <td>{{{ $stock['total_stocks'] }}}</td>
                              <td>
                                  <a href="{{{ route('admin_stocks.edit', $stock['stock_id']) }}}" class="btn btn-primary btn-xs"  title="Edit"><i class="icon-pencil"></i></a>
                
                                  <a href="{{{ route('admin_stocks.destroy', $stock['stock_id']) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                    <i class="icon-remove"></i>
                                  </a>
                              </td>
                          </tr>
                          @endforeach
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
                              <label>{{ $pagination->appends($appends)->links() }}</label>
                          </div>
                      </div>
                  </div>
            </form>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop