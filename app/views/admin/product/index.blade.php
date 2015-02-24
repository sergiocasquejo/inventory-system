@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <header class="panel-heading">
                  Products
              </header>
              <table class="table table-striped table-advance table-hover">
                  <thead>
                    <tr>
                        <th><i class="icon-bullhorn"></i> Name</th>
                        <th><i class=" icon-edit"></i> Encoded By</th>
                        <th><i class=" icon-edit"></i> Status</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody>

                    @if ($products)
                        @foreach ($products as $product)
                        <tr>
                            <td>{{{ $product->name }}}</td>
                            <td>{{{ $product->user->username }}}</td>
                            <td>
                                <span class="label label-{{{ $product->status ? 'success' : 'warning' }}} label-mini">
                                    {{{ $product->status ? 'Active' : 'Inactive' }}}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-xs"><i class="icon-ok"></i></button>
                                <button onclick="javascript:window.location.href='{{{ route('admin_products.edit', $product->id) }}}'" class="btn btn-primary btn-xs"><i class="icon-pencil"></i></button>
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