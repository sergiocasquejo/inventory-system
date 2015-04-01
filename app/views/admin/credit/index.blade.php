@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
            <form action="{{ route('admin_credits.index') }}"  class="form-inline tasi-form" method="GET">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <header class="panel-heading">
                  Credits <a class="btn btn-info btn-xs" href="{{ route('admin_credits.create') }}">Add New</a>
                  <a class="btn btn-warning btn-xs" href="{{ route('admin_credits.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
                  <a class="btn btn-primary btn-xs pull-right" data-toggle="modal" href="#partialPaymentModal">Make Partial Payment</a>
              </header>
              <div class="dataTables_wrapper form-inline">
                <div class="row">
                  <div class="col-sm-6">
                    <div id="sample_1_length" class="dataTables_length">
                      <label>
                        {{ Form::select('records_per_page', \Config::get('agrivet.records_per_page'), Input::get('records_per_page', 10), ['class' => 'form-control', 'size' => '1', 'onchange' => 'this.form.submit();']) }}
                        records per page
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <input type="text" name="s"  value="{{ Input::get('s') }}" placeholder="Search" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-info">Filter</button>
                </div>
                <table class="table table-striped table-advance table-hover">
                    <thead>
                      <tr>
                          <th>
                            @if (\Confide::user()->isAdmin())
                            {{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control input-xs']) }}
                            @else
                              Branch
                            @endif
                          </th>
                          <th>Cust. Name</th>
                          <th>Cust. Info</th>
                          <th>Quantity</th>
                          <th>{{ Form::select('total', $totals, Input::get('total', ''), ['class' => 'form-control input-xs']) }}</th>
                          <th>Comments</th>
                          <th><div class="form-group">
                              <div class="col-md-4 padding-2px">
                                  {{ Form::select('year', $years, Input::get('year', ''), ['class' => 'form-control input-xs']) }} 
                              </div>
                              <div class="col-md-4 padding-2px">
                                {{ Form::select('month', $months, Input::get('month', ''), ['class' => 'form-control input-xs']) }} 
                              </div>
                              <div class="col-md-4 padding-2px">
                                {{ Form::select('day', $days, Input::get('day', ''), ['class' => 'form-control input-xs']) }} 
                              </div>
                           </div>
                         </th>
                          <th>Encoded By</th>
                          <th>Date of encode</th>
                          <th>Last update</th>
                          <th><button type="submit" class="btn btn-info btn-xs">Filter</button></th>
                      </tr>
                    </thead>
                    <tbody>

                      @if ($credits)
                          @foreach ($credits as $credit)
                          <tr>
                              <td>{{{ !$credit->sale || !$credit->sale->branch ?'':$credit->sale->branch->name.' '.$credit->sale->branch->address }}}</td>
                              <td><a href="{{ !$credit->customer?'#':route('admin_customers.show',$credit->customer->customer_id) }}">{{{ !$credit->customer?'':$credit->customer->customer_name }}}</a></td>
                              <td><a class="badge bg-primary" data-html="true" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ '<p> Addres: '.(!$credit->customer?'':$credit->customer->address).'</p>'.'<p> Contact #: '.(!$credit->customer?'':$credit->customer->contact_no).'</p>' }}">?</a></td>
                              <td>{{{ !$credit->sale?'':$credit->sale->quantity }}}</td>
                              <td>{{{ !$credit->sale?'':\Helper::nf($credit->sale->total_amount) }}}</td>
                              <td><a class="badge bg-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="{{{ $credit->comments }}}">?</a></td>
                              <td>{{{ !$credit->sale?'':$credit->sale->date_of_sale }}}</td>

                              <td>{{{ !$credit->sale || !$credit->sale->user?'': $credit->sale->user->username }}}</td>
                              <td>{{{ \Helper::timeElapsedString(strtotime($credit->created_at)) }}}</td>
                              <td>{{{ \Helper::timeElapsedString(strtotime($credit->updated_at)) }}}</td>
                              <td>
                                  @if ($credit->trashed())
                                    <a href="{{{ route('admin_credits.restore', $credit->credit_id) }}}" data-method="RESTORE" class="btn btn-primary btn-xs" title="Restore"><i class="icon-rotate-left"></i></a>
                                  @else
                                    <a href="{{{ route('admin_credits.edit', $credit->credit_id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                  @endif
                                  @if (!$credit->trashed())
                                  <a href="{{{ route('admin_credits.destroy', $credit->credit_id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Trash" class="btn btn-danger btn-xs">
                                    <i class="icon-trash"></i>
                                  </a>
                                  @endif
                                  <a href="{{{ route('admin_credits.destroy', ['id' => $credit->credit_id, 'remove' => 1]) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                    <i class="icon-remove"></i>
                                  </a>

                              </td>
                          </tr>
                          @endforeach
                      @else
                          <tr>
                            <td colspan="11">{{{ \Lang::get('agrivet.empty', ['name' => 'Credits']) }}}</td>
                          </tr>
                      @endif
                    </tbody>
                    <tfoot>
                      <tr>
                          <td colspan="3"></td>
                          <td><strong>{{{ $credits->sum('quantity') }}}</strong></td>
                          <td><strong>{{{ \Helper::nf($credits->sum('total_amount')) }}}</strong></td>
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
                      <label>{{ $credits->appends($appends)->links() }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </section>
      </div>
  </div>
  <!-- page end-->
    @include ('admin._partials.payment_form', ['customer_id' => ''])


@stop