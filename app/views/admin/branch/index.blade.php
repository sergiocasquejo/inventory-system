@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
            <form action="{{ route('admin_branches.index') }}"  class="form-horizontal tasi-form" method="GET">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <header class="panel-heading">
                  Branches
              </header>
              <div class="dataTables_wrapper form-inline">
                <div class="row">
                  <div class="col-sm-6">
                    <div id="sample_1_length" class="dataTables_length">
                      <label>
                        {{ Form::select('records_per_page', \Config::get('agrivate.records_per_page'), Input::old('records_per_page', 10), ['class' => 'form-control', 'size' => '1']) }} 
                        records per page
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="dataTables_filter" id="sample_1_filter">
                      <label>Search: <input type="text" name="s" class="form-control"></label>
                    </div>
                  </div>
                </div>
                <table class="table table-striped table-advance table-hover">
                    <thead>
                      <tr>
                          <th>Name</th>
                          <th>Address</th>
                          <th>City</th>
                          <th>State</th>
                          <th>Postcode</th>
                          <th>Country</th>
                          <th>Status</th>
                          <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      @if ($branches)
                          @foreach ($branches as $branch)
                          <tr>
                              <td>{{{ $branch->name }}}</td>
                              <td>{{{ $branch->address }}}</td>
                              <td>{{{ $branch->state }}}</td>
                              <td>{{{ $branch->city }}}</td>
                              <td>{{{ $branch->post_code }}}</td>
                              <td>{{{ $countries[$branch->country] }}}</td>
                              <td>
                                  <span class="label label-{{{ $branch->status ? 'success' : 'warning' }}} label-mini">
                                      {{{ $branch->status ? 'Active' : 'Inactive' }}}
                                  </span>
                              </td>
                              <td>
                                  @if ($branch->trashed())
                                    <a href="{{{ route('admin_branches.restore', $branch->id) }}}" data-method="RESTORE" class="btn btn-primary btn-xs" title="Restore"><i class="icon-rotate-left"></i></a>
                                  @else
                                    <a href="{{{ route('admin_branches.edit', $branch->id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                  @endif
                                  <a href="{{{ route('admin_branches.destroy', $branch->id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="{{{ $branch->trashed() ? 'Delete' : 'Trash' }}}" class="btn btn-danger btn-xs">
                                    <i class="icon-{{{ $branch->trashed() ? 'remove' : 'trash' }}} "></i>
                                  </a>
                              </td>
                          </tr>
                          @endforeach
                      @else
                          <tr>
                            <td colspan="8">{{{ \Lang::get('agrivate.empty', 'Branch') }}}</td>
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
                      <label>{{ $branches->links() }}</label>
                    </div>
                  </div>
                </div>
              </div>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop