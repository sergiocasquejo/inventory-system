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
			<div class="tab-content">
			  	<div class="tab-pane active" id="general">
			  		<form action="{{ route('admin_products.update', $product->id) }}"  class="form-horizontal tasi-form" method="POST">
			  			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			  			<input name="_method" type="hidden" value="PUT">
				       	<div class="form-group">
				          <label class="col-sm-2 control-label">Name</label>
				          <div class="col-sm-10">
				              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name', $product->name) }}" />
				              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
				          </div>
				      	</div>
						<div class="form-group">
						  <label class="col-sm-2 control-label">Description</label>
						  <div class="col-sm-10">
						      <textarea name="description" class="form-control">{{ Input::old('description', $product->description) }}</textarea>
						  </div>
						</div>

						<div class="form-group">
						  <label class="col-sm-2 control-label">Comments</label>
						  <div class="col-sm-10">
						      <textarea name="comments" class="form-control">{{ Input::old('comments', $product->comments) }}</textarea>
						  </div>
						</div>

						<div class="form-group">
						  <label class="col-sm-2 control-label">Status</label>
						  <div class="col-sm-10">
						      {{ Form::select('status', \Config::get('agrivate.statuses'), Input::old('status', $product->status), ['class' => 'form-control m-bot15']) }}
						  </div>
						</div>
						<button type="submit" class="btn btn-shadow btn-primary">Update</button>
					</form>
				</div>
				<div class="tab-pane" id="stock">
					<div class="row">
						<form action="{{ route('admin_product_stocks.store', $product->id) }}"  class="form-horizontal tasi-form" method="POST">
				  			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<div class="col-sm-2">
								<input type="number" name="total_stocks" min="0" placeholder="Stocks" value="{{ Input::old('total_stocks') }}" class="form-control">
							</div>
							<div class="col-sm-2">
								{{ Form::select('uom', array_add(\Config::get('agrivate.unit_of_measure'), '', 'Select Measure'), Input::old('uom'), ['class' => 'form-control m-bot15']) }}
							</div>
							<div class="col-sm-4">
								{{ Form::select('branch_id', $branches, Input::old('branch_id'), ['class' => 'form-control m-bot15']) }}
							</div>
							<div class="col-sm-1">
								<button class="btn btn-info" type="submit" name="action" value="add_stock">Add</button>
							</div>
						</form>
					</div>

					<table class="table table-striped table-advance table-hover">
				        <thead>
				          <tr>
				              <th>Stocks</th>
				              <th>Unit of measure</th>
				              <th>Branch</th>
				              <th></th>
				          </tr>
				        </thead>
				        <tbody>

				          @if ($product->stocks)
				              @foreach ($product->stocks as $stock)
				              <tr>
				                  <td>{{{ $stock->total_stocks }}}</td>
				                  <td>{{{ $stock->uom }}}</td>
				                  <td>{{{ $stock->branch->name }}}</td>
				                  <td>
				                      <a href="{{{ route('admin_product_stocks.edit', ['pid' => $product->id, 'stock_on_hand_id' => $stock->stock_on_hand_id]) }}}" class="btn btn-primary btn-xs" data-fetch="GET" title="Edit"><i class="icon-pencil"></i></a>
				    
				                      <a href="{{{ route('admin_product_stocks.destroy', ['pid' => $product->id, 'stock_on_hand_id' => $stock->stock_on_hand_id]) }}}" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
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
			</div>
			
		</div>
	</section>
	<!-- page end-->
@stop