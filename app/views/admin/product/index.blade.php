@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
      <div class="col-lg-12">
          <section class="panel">
              <form action="{{ route('admin_products.index') }}"  class="form-horizontal tasi-form" method="GET">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <header class="panel-heading">
                  Products <a class="btn btn-info btn-xs" href="{{ route('admin_products.create') }}">Add New</a> <a class="btn btn-warning btn-xs" href="{{ route('admin_products.index') }}" title="Reset"><i class=" icon-refresh"></i></a>
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
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                      <input type="text" name="s"  value="{{ Input::get('s') }}" placeholder="Search" class="form-control">               
                    </div>
                    <div class="col-md-4">
                      {{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control', 'size' => '1']) }} 
                    </div>
                    <div class="col-md-4">
                      {{ Form::select('category', $categories, Input::get('category', ''), ['class' => 'form-control', 'size' => '1']) }} 
                    </div>
                    <div class="col-md-1">
                      <button type="submit" class="btn btn-info">Filter</button>
                    </div>
                </div>
                <table class="table table-striped table-advance table-hover">
                    <thead>
                      <tr>
                          <th>BRANCH</th>
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
                              <td>{{{ $product->branch_name  }}}</td>
                              <td>{{{ $product->name }}}</td>
                              <td>{{{ \Helper::nf($product->selling_price).' '.$product->per_unit }}}</td>
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