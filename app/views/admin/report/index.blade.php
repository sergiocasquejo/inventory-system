@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_sales.index') }}"  class="form-horizontal tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading">
                    Reports <a class="btn btn-warning btn-xs" href="{{ route('admin_reports.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
                </header>
                <div class="dataTables_wrapper form-inline">
                  <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-sm-4">
                        <div class="dataTables_filter input-group m-bot15">
                            <input type="text" name="date" value="{{ Input::get('date') }}" class="datepicker form-control">
                            <div class="input-group-btn">
                                <button tabindex="-1" class="btn btn-info" type="button">Filter</button>
                            </div>
                        </div>
                    </div>
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
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                   
                  </tbody>
                  <tfoot>
                      <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                      </tr>
                  </tfoot>
              </table>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop