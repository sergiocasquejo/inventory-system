@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_products.index') }}"  class="form-inline tasi-form" method="GET">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <header class="panel-heading">
                  Products <a class="btn btn-info btn-xs" href="{{ route('admin_products.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_products.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
              </header>
              <div class="dataTables_wrapper form-inline">
                
                  <br />
                  <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::select('records_per_page', \Config::get('agrivet.records_per_page'), Input::get('records_per_page', 10), ['class' => 'form-control', 'size' => '1', 'onchange' => 'this.form.submit();']) }}
                    </div>
                    <div class="form-group">
                        <input type="text" name="s"  value="{{ Input::get('s') }}" placeholder="Search" class="form-control">
                    </div>
                    <div class="form-group">
                      {{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control', 'size' => '1']) }} 
                    </div>
                    <div class="form-group">
                      {{ Form::select('brand', $brands, Input::get('brand', ''), ['class' => 'form-control', 'size' => '1']) }}
                    </div>
                    <button type="submit" class="btn btn-info">Filter</button>
                  </div>
                <table class="table table-striped table-advance table-hover">
                    <thead>
                      <tr>
                          <th>SUPPLIER NAME</th>
                          <th>PRODUCT NAME</th>
                          <th>SELLING PRICE PER KILO/BTL/PCK/PCS/SACKS</th>
                          <th>STATUS</th>
                          <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      @if ($products)
                          @foreach ($products as $product)
                          <tr>
                              <td>{{{ !$product->supplier?'':$product->supplier->supplier_name }}}</td>
                              <td>{{{ $product->name }}}</td>
                              <td><span class="label label-info label-mini mr-10px">{{ str_replace(',', '</span><span class="label label-info label-mini mr-10px">', $product->selling_price) }}</span></td>
                              <td>
                                  <span class="label label-{{{ $product->status ? 'success' : 'warning' }}} label-mini">
                                      {{{ $product->status ? 'Active' : 'Inactive' }}}
                                  </span>
                              </td>
                              <td>
                                  @if ($product->deleted_at != null)
                                    <a href="{{{ route('admin_products.restore', $product->id) }}}" data-method="RESTORE" class="btn btn-primary btn-xs" title="Restore"><i class="icon-rotate-left"></i></a>
                                  @else
                                    <a href="{{{ route('admin_products.edit', $product->id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                  @endif
                                  @if (!$product->deleted_at != null)
                                  <a href="{{{ route('admin_products.destroy', $product->id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Trash" class="btn btn-danger btn-xs">
                                    <i class="icon-trash"></i>
                                  </a>
                                  @endif
                                  <a href="{{{ route('admin_products.destroy', ['id' => $product->id, 'remove' => 1]) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                    <i class="icon-remove"></i>
                                  </a>


                              </td>
                          </tr>
                          @endforeach
                      @else
                          <tr>
                            <td colspan="10">{{{ \Lang::get('agrivet.empty', ['name' => 'Products']) }}}</td>
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
                      <label>{{ $products->appends($appends)->links() }}</label>
                    </div>
                  </div>
                </div>
              </div>
              </form>
          </section>
      </div>
  </div>
  <!-- page end-->
@stop