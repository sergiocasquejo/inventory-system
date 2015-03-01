@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
		<header class="panel-heading">
		 Edit Sale
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_sales.update', $sale->sale_id) }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input type="hidden" name="_method" value="PUT" />

		       	<div class="form-group">
					<label class="col-sm-2 control-label">Branch</label>
					<div class="col-sm-10">
						{{ Form::select('branch_id', $branches, Input::old('branch_id', $sale->branch_id), ['class' => 'form-control m-bot15']) }}
						<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
					</div>
		      	</div>

		      	<div class="form-group">
					<label class="col-sm-2 control-label">Product</label>
					<div class="col-sm-10">
						{{ Form::select('product_id', $products, Input::old('product_id', $sale->product_id), ['class' => 'form-control m-bot15']) }}
						<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
					</div>
		      	</div>

		      	<div class="form-group">
				  <label class="col-sm-2 control-label">Quantity</label>
				  <div class="col-sm-10">
				      <input type="number" name="quantity" value="{{ Input::old('quantity', $sale->quantity) }}" class="form-control">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Unit of measure</label>
				  <div class="col-sm-10">
				      {{ Form::select('uom', array_add(\Config::get('agrivate.unit_of_measure'), '', 'Select Measure'), Input::old('uom', $sale->uom), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Total Amount</label>
				  <div class="col-sm-10">
				      <input type="number" name="total_amount" value="{{ Input::old('total_amount', $sale->total_amount) }}" class="form-control">
				  </div>
				</div>


				<div class="form-group">
					<label class="col-sm-2 control-label">Comments</label>
					<div class="col-sm-10">
						<textarea name="comments" class="form-control">{{ Input::old('comments', $sale->comments) }}</textarea>
					</div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Date of sale</label>
				  <div class="col-sm-10">
				      <input type="text" name="date_of_sale" value="{{ Input::old('date_of_sale', $sale->date_of_sale) }}" class="form-control datepicker">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status', \Config::get('agrivate.statuses'), Input::old('status', $sale->status), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Update</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop