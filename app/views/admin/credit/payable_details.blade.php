@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_credits.payable_details') }}" id="payableList"  class="form-inline tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading">
                    Payables  <a class="btn btn-warning btn-xs" href="{{ route('admin_credits.payables') }}" title="Reset"><i class=" icon-refresh"></i></a>
                    <a class="btn btn-primary btn-xs pull-right" data-toggle="modal" href="#partialPaymentModal">Make Partial Payment</a>
                </header>
                <div class="dataTables_wrapper form-inline">
                  <br />
                  <div class="col-md-12">
                      <div class="col-md-4"><strong>BRANCH LOCATION: {{ $branch }}</strong></div>
                      <div class="col-md-4"><strong>SUPPLIER: {{ $supplier }}</strong></div>
                  </div>

                  <table class="table table-striped table-advance table-hover">
                    <thead>
                      <tr>
                          <th>PRODUCT</th>
                          <th>UNIT OF MEASURE</th>
                          <th>QTY</th>
                          <th>AMOUNT </th>
                          <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      @if ($payables)
                          @foreach ($payables as $payable)
                          <tr>
                              <td>
                                  {{{ $payable->product->name }}}
                              </td>
                              <td>{{{ $payable->uom }}}</td>
                              <td>{{{ $payable->quantity }}}</td>
                              <td>{{{ \Helper::nf($payable->total_amount) }}}</td>
                               <td>

                                  <a href="{{{ route('admin_credits.payables_destroy',  $payable->payable_id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                    <i class="icon-remove"></i>
                                  </a>

                              </td>
                          </tr>
                          @endforeach
                      @else
                          <tr>
                            <td colspan="10">{{{ \Lang::get('agrivet.empty', ['name' => 'Expense']) }}}</td>
                          </tr>
                      @endif
                    </tbody>
                    <tfoot>
                      <tr>
                          <td colspan="2"></td>
                          <td><strong>{{{ !$payables ? 0 :$payables->sum('quantity') }}}</strong></td>
                          <td><strong>{{{ \Helper::nf(!$payables ? 0 : $payables->sum('total_amount')) }}}</strong></td>
                          <td colspan="6"></td>
                      </tr>
                  </tfoot>
                </table>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="dataTables_length">
                      {{{ $totalRows }}} entries</div>
                  </div>
                  <div class="col-sm-6">
                    <div class="dataTables_filter pagination-sm">
                        @if ($payables)
                      <label>{{ $payables->appends($appends)->links() }}</label>
                            @endif
                    </div>
                  </div>
                </div>
              </div>
              </form>
          </section>
      </div>
  </div>
  <!-- page end-->
    @include ('admin._partials.payables_form', ['supplier_id' => ''])
@stop