@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_reports.stocks') }}"  class="form-horizontal tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading m-bot15">
                    Stocks <a class="btn btn-warning btn-xs" href="{{ route('admin_reports.stocks') }}" title="Reset"><i class=" icon-refresh"></i></a>
                </header>
                <div class="dataTables_wrapper form-inline">
                  <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-8">
                        {{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-1">
                      <button tabindex="-1" class="btn btn-info" type="submit">Filter</button>
                    </div>
                  </div>
                  <table class="table table-striped table-advance table-hover">
                      <thead>
                        <tr>
                            <th>PRODUCT NAME</th>
                            <th>STOCKS</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if ($stocks)
                          @foreach ($stocks as $stock)
                          <tr>
                              <td>{{{ $stock->product->name }}}</td>
                              <td>{{{ \Helper::nf($stock->total_stocks).' '.$stock->uom }}}</td>
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