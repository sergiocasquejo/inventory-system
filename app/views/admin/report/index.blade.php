@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_reports.index') }}"  class="form-inline tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading">
                    Reports <a class="btn btn-warning btn-xs" href="{{ route('admin_reports.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
                </header>
                <div class="dataTables_wrapper">
                  <br />
                  <div class="col-md-12">
                      <div class="form-group">
                        <input type="text" placeholder="From" name="date_from" value="{{ Input::get('date_from') }}" class="datepicker form-control">
                      </div>
                      <div class="form-group">
                        <input type="text" name="date_to" placeholder="To" value="{{ Input::get('date_to') }}" class="datepicker form-control">
                      </div>
                      <button type="submit" class="btn btn-info">Filter</button>
                  </div>
                
                  <table class="table table-striped table-advance table-hover">
                      <thead>
                        <tr>
                            <th>KM AGRIVATE BRANCHES</th>
                            <th>TOTAL AMOUNT OF EXPENSE</th>
                            <th>TOTAL AMOUNT OF CREDITS</th>
                            <th>TOTAL AMOUNT OF INCOME</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if ($reports)
                          @foreach ($reports as $report)
                          <tr>
                              <td>{{{ $report->name }}}</td>
                              <td>{{{ \Helper::nf($report->expenses) }}}</td>
                              <td>{{{ \Helper::nf($report->credits) }}}</td>
                              <td>{{{ \Helper::nf($report->sales) }}}</td>
                          </tr>
                          @endforeach
                       @endif
                      </tbody>
                      <tfoot>
                          <tr>
                              <td>TOTAL</td>
                              <td>{{{ \Helper::nf($total_expenses) }}}</td>
                              <td>{{{ \Helper::nf($total_credits) }}}</td>
                              <td>{{{ \Helper::nf($total_sales) }}}</td>
                          </tr>
                      </tfoot>
                  </table>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop