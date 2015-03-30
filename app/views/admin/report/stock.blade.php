@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_reports.stocks') }}"  class="form-inline tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading m-bot15">
                    Stocks <a class="btn btn-warning btn-xs" href="{{ route('admin_reports.stocks') }}" title="Reset"><i class=" icon-refresh"></i></a>
                </header>
                  <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control']) }}
                    </div>
                    <div  class="form-group">
                        <input type="text" placeholder="Search" class="form-control" name="s" value="{{{ Input::get('s') }}}" />
                    </div>
                    <div class="form-group">
                      <button tabindex="-1" class="btn btn-info" type="submit">Filter</button>
                    </div>
                  </div>
                  <table class="table table-striped table-advance table-hover">
                      <thead>
                        <tr>
                            <th>BRANCH</th>
                            <th>PRODUCT NAME</th>
                            <th>NO OF KILO/BTL/PCK/PCS ON HAND</th>
                            <th>NO OF SACKS ON HAND</th>
                            <th>TOTAL NO OF STOCKS</th>
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
                          </tr>
                          @endforeach
                       @endif
                      </tbody>
                  </table>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop