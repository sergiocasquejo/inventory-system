@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <header class="panel-heading">
                  Expenses
              </header>
              <table class="table table-striped table-advance table-hover">
                  <thead>
                    <tr>
                        <th><i class="icon-bullhorn"></i> Branch</th>
                        <th class="hidden-phone"><i class="icon-question-sign"></i> Name</th>
                        <th><i class="icon-bookmark"></i> Total Amount</th>
                        <th><i class=" icon-edit"></i> Quantity</th>
                        <th><i class=" icon-edit"></i> Unit of measure</th>
                        <th><i class=" icon-edit"></i> Comments</th>
                        <th><i class=" icon-edit"></i> Expense Date</th>
                        <th><i class=" icon-edit"></i> Encoded By</th>
                        <th><i class=" icon-edit"></i> Status</th>
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
                            <td><span data-title="{{{ $expense->comments }}}">?</span></td>
                            <td>{{{ $expense->date_of_expense }}}</td>
                            <td>{{{ $expense->user->username }}}</td>
                            <td>
                                <span class="label label-{{{ $expense->status ? 'success' : 'warning' }}} label-mini">
                                    {{{ $expense->status ? 'Active' : 'Inactive' }}}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-xs"><i class="icon-ok"></i></button>
                                <button onclick="javascript:window.location.href='{{{ route('admin_expense.edit', $expense->expense_id) }}}'" class="btn btn-primary btn-xs"><i class="icon-pencil"></i></button>
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