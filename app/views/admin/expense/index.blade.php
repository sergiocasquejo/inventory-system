@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <div class="row state-overview">
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol terques">
                        <strong>Daily</strong>
                    </div>
                    <div class="value">
                        <p>1, 3000</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol red">
                        <strong>Weekly</strong>
                    </div>
                    <div class="value">
                        <p>1, 3000</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol yellow">
                        <strong>Monthly</strong>
                    </div>
                    <div class="value">
                        <p>1, 3000</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <strong>Yearly</strong>
                    </div>
                    <div class="value">
                        <p>1, 3000</p>
                    </div>
                </section>
            </div>
          </div>
      </div>
      
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_expenses.index') }}"  class="form-horizontal tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading">
                    Expenses <a class="btn btn-info btn-xs" href="{{ route('admin_expenses.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_expenses.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
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
                          <th>Name</th>
                          <th>Total Amount</th>
                          <th>Quantity</th>
                          <th>Unit of measure</th>
                          <th>Comments</th>
                          <th>Expense Date</th>
                          <th>Encoded By</th>
                          <th>Status</th>
                          <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      @if ($expenses)
                          @foreach ($expenses as $expense)
                          <tr>
                              <td>{{{ $expense->branch->name }}}</td>
                              <td>{{{ $expense->name }}}</td>
                              <td>{{{ $expense->total_amount }}}</td>
                              <td>{{{ $expense->quantity }}}</td>
                              <td>{{{ $expense->uom }}}</td>
                              <td><a class="badge bg-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="{{{ $expense->comments }}}">?</a></td>
                              <td>{{{ $expense->date_of_expense }}}</td>
                              <td>{{{ $expense->user->username }}}</td>
                              <td>
                                     <span class="label label-{{{ $expense->status ? 'success' : 'warning' }}} label-mini">
                                      {{{ $expense->status ? 'Active' : 'Inactive' }}}
                                  </span>
                              </td>
                               <td>
                                  @if ($expense->trashed())
                                    <a href="{{{ route('admin_expenses.restore', $expense->expense_id) }}}" data-method="RESTORE" class="btn btn-primary btn-xs" title="Restore"><i class="icon-rotate-left"></i></a>
                                  @else
                                    <a href="{{{ route('admin_expenses.edit', $expense->expense_id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                  @endif
                                  <a href="{{{ route('admin_expenses.destroy', $expense->expense_id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="{{{ $expense->trashed() ? 'Delete' : 'Trash' }}}" class="btn btn-danger btn-xs">
                                    <i class="icon-{{{ $expense->trashed() ? 'remove' : 'trash' }}} "></i>
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
                      <label>{{ $expenses->appends($appends)->links() }}</label>
                    </div>
                  </div>
                </div>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop