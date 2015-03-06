@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_users.index') }}"  class="form-inline tasi-form" method="GET">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <header class="panel-heading">
                    Users <a class="btn btn-info btn-xs" href="{{ route('admin_users.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_users.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
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
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Display Name</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Is Admin?</th>
                        <th>Confirmed?</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody>

                    @if ($users)
                        @foreach ($users as $user)
                        <tr>
                            <td>{{{ $user->username }}}</td>
                            <td>{{{ $user->email }}}</td>
                            <td>{{{ $user->display_name }}}</td>
                            <td>{{{ $user->first_name }}}</td>
                            <td>{{{ $user->last_name }}}</td>
                            <td>
                              <span class="label label-{{{ $user->is_admin ? 'info' : 'warning' }}} label-mini">
                                {{{ $user->is_admin ? 'Yes' : 'No' }}}
                              </span>
                            </td>
                            <td>
                              <span class="label label-{{{ $user->confirmed ? 'success' : 'warning' }}} label-mini">
                                    {{{ $user->confirmed ? 'Yes' : 'No' }}}
                                </span>
                            </td>
                             <td>{{{ !$user->branch?'':$user->branch->name }}}</td>
                            <td>
                                <span class="label label-{{{ $user->status ? 'success' : 'warning' }}} label-mini">
                                    {{{ $user->status ? 'Active' : 'Inactive' }}}
                                </span>
                            </td>
                           
                            <td>
                                @if ($user->trashed())
                                  <a href="{{{ route('admin_users.restore', $user->id) }}}" data-method="RESTORE" class="btn btn-primary btn-xs" title="Restore"><i class="icon-rotate-left"></i></a>
                                @else
                                  <a href="{{{ route('admin_users.edit', $user->id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                @endif
                                @if (!$user->is_admin)
                                <a href="{{{ route('admin_users.destroy', $user->id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="{{{ $user->trashed() ? 'Delete' : 'Trash' }}}" class="btn btn-danger btn-xs">
                                  <i class="icon-{{{ $user->trashed() ? 'remove' : 'trash' }}} "></i>
                                </a>
                                @endif
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
                      <label>{{ $users->appends($appends)->links() }}</label>
                    </div>
                  </div>
                </div>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop
