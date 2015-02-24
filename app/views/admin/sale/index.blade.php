@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <header class="panel-heading">
                  Sales
              </header>
              <table class="table table-striped table-advance table-hover">
                  <thead>
                    <tr>
                        <th><i class="icon-bullhorn"></i> Branch</th>
                        <th class="hidden-phone"><i class="icon-question-sign"></i> Product</th>
                        <th><i class=" icon-edit"></i> Quantity</th>
                        <th><i class=" icon-edit"></i> Unit of measure</th>
                         <th><i class="icon-bookmark"></i> Total Amount</th>
                        <th><i class=" icon-edit"></i> Comments</th>
                        <th><i class=" icon-edit"></i> Encoded By</th>
                        <th><i class=" icon-edit"></i> Status</th>
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
                            <td><span data-title="{{{ $sale->comments }}}">?</span></td>
                            <td>{{{ $sale->user->username }}}</td>
                            <td>
                                <span class="label label-{{{ $sale->status ? 'success' : 'warning' }}} label-mini">
                                    {{{ $sale->status ? 'Active' : 'Inactive' }}}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-xs"><i class="icon-ok"></i></button>
                                <button onclick="javascript:window.location.href='{{{ route('admin_sales.edit', $sale->sale_id) }}}'" class="btn btn-primary btn-xs"><i class="icon-pencil"></i></button>
                                <button class="btn btn-danger btn-xs"><i class="icon-trash "></i></button>
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
          </section>
      </div>
  </div>
  <!-- page end-->
@stop