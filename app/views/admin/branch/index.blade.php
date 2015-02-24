@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <header class="panel-heading">
                  Branches
              </header>
              <table class="table table-striped table-advance table-hover">
                  <thead>
                    <tr>
                        <th><i class="icon-bullhorn"></i> Name</th>
                        <th class="hidden-phone"><i class="icon-question-sign"></i> Address</th>
                        <th><i class="icon-bookmark"></i> City</th>
                        <th><i class=" icon-edit"></i> State</th>
                        <th><i class=" icon-edit"></i> Postcode</th>
                        <th><i class=" icon-edit"></i> Country</th>
                        <th><i class=" icon-edit"></i> Status</th>
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
                            <td>{{{ $branch->country }}}</td>
                            <td>
                                <span class="label label-{{{ $branch->status ? 'success' : 'warning' }}} label-mini">
                                    {{{ $branch->status ? 'Active' : 'Inactive' }}}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-xs"><i class="icon-ok"></i></button>
                                <button onclick="javascript:window.location.href='{{{ route('admin_branches.edit', $branch->id) }}}'" class="btn btn-primary btn-xs"><i class="icon-pencil"></i></button>
                                <button class="btn btn-danger btn-xs"><i class="icon-trash "></i></button>
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
          </section>
      </div>
  </div>
  <!-- page end-->
@stop