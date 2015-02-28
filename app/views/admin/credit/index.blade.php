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
                        <p>{{{ \Helper::nf($daily) }}}</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol red">
                        <strong>Weekly</strong>
                    </div>
                    <div class="value">
                        <p>{{{ \Helper::nf($weekly) }}}</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol yellow">
                        <strong>Monthly</strong>
                    </div>
                    <div class="value">
                        <p>{{{ \Helper::nf($monthly) }}}</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 col-sm-6">
                <section class="panel">
                    <div class="symbol blue">
                        <strong>Yearly</strong>
                    </div>
                    <div class="value">
                        <p>{{{ \Helper::nf($yearly) }}}</p>
                    </div>
                </section>
            </div>
          </div>
      </div>
      
      <div class="col-lg-12">
          <section class="panel">
            <form action="{{ route('admin_credits.index') }}"  class="form-horizontal tasi-form" method="GET">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <header class="panel-heading">
                  Credits <a class="btn btn-info btn-xs" href="{{ route('admin_credits.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_credits.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
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
                          <th>{{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control input-xs']) }}</th>
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
                          <th>{{ Form::select('status', $statuses, Input::get('status', ''), ['class' => 'form-control input-xs']) }}</th>
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
                              <td>{{{ !$credit->branch?'':$credit->branch->name }}}</td>
                              <td>{{{ $credit->customer_name }}}</td>
                              <td><a class="badge bg-primary" data-html="true" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ '<p> Addres: '.$credit->customer.'</p>'.'<p> Contact #: '.$credit->contact_number.'</p>' }}">?</a></td>
                              <td>{{{ $credit->quantity }}}</td>
                              <td>{{{ \Helper::nf($credit->total_amount) }}}</td>
                              <td><a class="badge bg-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="{{{ $credit->comments }}}">?</a></td>
                              <td>{{{ $credit->date_of_credit }}}</td>
                              <td> <span class="label label-{{{ $credit->is_paid ? 'success' : 'warning' }}} label-mini">
                                      {{{ $credit->is_paid ? 'Paid' : 'Not Paid' }}}
                                  </span></td>
                              <td>{{{ $credit->user->username }}}</td>
                              <td>{{{ \Helper::timeElapsedString(strtotime($credit->created_at)) }}}</td>
                              <td>{{{ \Helper::timeElapsedString(strtotime($credit->updated_at)) }}}</td>
                              <td>
                                    <a href="{{{ route('admin_credits.edit', $credit->credit_id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                               
                                  <a href="{{{ route('admin_credits.destroy', $credit->credit_id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                    <i class="icon-remove"></i>
                                  </a>
                              </td>
                          </tr>
                          @endforeach
                      @else
                          <tr>
                            <td colspan="8">{{{ \Lang::get('agrivate.empty', 'Credits') }}}</td>
                          </tr>
                      @endif
                    </tbody>
                    <tfoot>
                      <tr>
                          <td colspan="3"></td>
                          <td><strong>{{{ \Helper::nf($credits->sum('quantity')) }}}</strong></td>
                          <td><strong>{{{ \Helper::nf($credits->sum('total_amount')) }}}</strong></td>
                          <td colspan="7"></td>
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
          </section>
      </div>
  </div>
  <!-- page end-->
@stop