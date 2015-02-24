@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
		<header class="panel-heading">
		 Create Product 
		</header>
		<div class="row">
			<div class="col-lg-12">
          		<ul class="nav nav-tabs">
          			<li class="active">
	                  <a data-toggle="tab" href="#general">General</a>
	              	</li>
	              	<li class="">
	                  <a data-toggle="tab" href="#stock">Stocks On Hand</a>
	              	</li>
	              	<li class="">
	                  <a data-toggle="tab" href="#pricing">Pricing</a>
	              	</li>
         	 	</ul>
         	</div>
      	</div>
		<div class="panel-body tab-container">
		  	<form action="{{ route('admin_products.store') }}"  class="form-horizontal tasi-form tab-content" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<div class="tab-pane active" id="general">
			       <div class="form-group">
			          <label class="col-sm-2 control-label">Name</label>
			          <div class="col-sm-10">
			              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name') }}" />
			              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
			          </div>
			      	</div>
					<div class="form-group">
					  <label class="col-sm-2 control-label">Description</label>
					  <div class="col-sm-10">
					      <textarea name="description" class="form-control">{{ Input::old('description') }}</textarea>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Comments</label>
					  <div class="col-sm-10">
					      <textarea name="comments" class="form-control">{{ Input::old('comments') }}</textarea>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Status</label>
					  <div class="col-sm-10">
					      {{ Form::select('status', \Config::get('agrivate.statuses'), Input::old('status'), ['class' => 'form-control m-bot15']) }}
					  </div>
					</div>
				</div>
				<div class="tab-pane" id="stock">
					<table class="table table-striped table-advance table-hover">
				        <thead>
				          <tr>
				              <th>Stocks</th>
				              <th>Unit of measure</th>
				              <th>Branch</th>
				              <th>Status</th>
				              <th></th>
				          </tr>
				        </thead>
				        <tbody>

				          @if ($product->stocks)
				              @foreach ($product->stocks as $stock)
				              <tr>
				                  <td>{{{ $stock->total_stocks }}}</td>
				                  <td>{{{ $stock->uom->name }}}</td>
				                  <td>{{{ $stock->branch->name }}}</td>
				                  <td>
				                      <span class="label label-{{{ $stock->status ? 'success' : 'warning' }}} label-mini">
				                          {{{ $stock->status ? 'Active' : 'Inactive' }}}
				                      </span>
				                  </td>
				                  <td>
				                      <a href="{{{ route('admin_products.edit', $product->id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
				    
				                      <a href="{{{ route('admin_products.destroy', $product->id) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
				                        <i class="icon-remove"></i>
				                      </a>
				                  </td>
				              </tr>
				              @endforeach
				          @else
				              <tr>
				                <td colspan="4">{{{ \Lang::get('agrivate.empty', 'Stocks') }}}</td>
				              </tr>
				          @endif
				        </tbody>
				    </table>
				</div>
				<div class="tab-pane" id="pricing">
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop