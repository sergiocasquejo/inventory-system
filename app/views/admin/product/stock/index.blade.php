@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <table class="table table-striped table-advance table-hover">
        <thead>
          <tr>
              <th>Name</th>
              <th>Encoded By</th>
              <th>Status</th>
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
                      @if ($product->trashed())
                        <a href="{{{ route('admin_products.restore', $product->id) }}}" data-method="RESTORE" class="btn btn-primary btn-xs" title="Restore"><i class="icon-rotate-left"></i></a>
                      @else
                        <a href="{{{ route('admin_products.edit', $product->id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                      @endif
                      <a href="{{{ route('admin_products.destroy', $product->id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="{{{ $product->trashed() ? 'Delete' : 'Trash' }}}" class="btn btn-danger btn-xs">
                        <i class="icon-{{{ $product->trashed() ? 'remove' : 'trash' }}} "></i>
                      </a>
                  </td>
              </tr>
              @endforeach
          @else
              <tr>
                <td colspan="10">{{{ \Lang::get('agrivate.empty', 'Products') }}}</td>
              </tr>
          @endif
        </tbody>
    </table>
@stop