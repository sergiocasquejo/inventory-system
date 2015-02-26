@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_sales.index') }}"  class="form-horizontal tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading">
                    Sales <a class="btn btn-info btn-xs" href="{{ route('admin_sales.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_sales.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
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
                        <th>Branch</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit of measure</th>
                        <th>Total Amount</th>
                        <th>Date of Sale</th>
                        <th>Comments</th>
                        <th>Encoded By</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody>

                    @if ($sales)
                        @foreach ($sales as $sale)
                        <tr>
                            <td>{{{ $sale->branch->name }}}</td>
                            <td>{{{ $sale->product->name }}}</td>
                            <td>{{{ $sale->quantity }}}</td>
                            <td>{{{ $sale->uom }}}</td>
                            <td>{{{ $sale->total_amount }}}</td>
                            <td>{{{ Helper::df($sale->date_of_sale) }}}</td>
                            <td><span data-title="{{{ $sale->comments }}}">?</span></td>
                            <td>{{{ $sale->user->username }}}</td>
                            <td>
                                <span class="label label-{{{ $sale->status ? 'success' : 'warning' }}} label-mini">
                                    {{{ $sale->status ? 'Active' : 'Inactive' }}}
                                </span>
                            </td>
                            <td>
                                @if ($sale->trashed())
                                  <a href="{{{ route('admin_sales.restore', $sale->sale_id) }}}" data-method="RESTORE" class="btn btn-primary btn-xs" title="Restore"><i class="icon-rotate-left"></i></a>
                                @else
                                  <a href="{{{ route('admin_sales.edit', $sale->sale_id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                @endif
                                <a href="{{{ route('admin_sales.destroy', $sale->sale_id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="{{{ $sale->trashed() ? 'Delete' : 'Trash' }}}" class="btn btn-danger btn-xs">
                                  <i class="icon-{{{ $sale->trashed() ? 'remove' : 'trash' }}} "></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                          <td colspan="10">{{{ \Lang::get('agrivate.empty', 'Expense') }}}</td>
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
                      <label>{{ $sales->appends($appends)->links() }}</label>
                    </div>
                  </div>
                </div>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop