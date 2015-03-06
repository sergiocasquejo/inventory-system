@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Create Stock
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_stocks.store') }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Product</label>
		          <div class="col-sm-10">
		              {{ Form::select('product_id', $products, Input::old('product_id', ''), ['class' => 'form-control']) }}
		          </div>
		      	</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label">Stocks</label>
				  <div class="col-sm-10">
				     <input type="number" step="any"  name="total_stocks" min="0" placeholder="Stocks" value="{{ Input::old('total_stocks') }}" class="form-control"> 
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Unit of Measure</label>
				  <div class="col-sm-10">
				      {{ Form::select('uom', $dd_measures, Input::old('uom'), ['class' => 'form-control']) }}
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label">Branch</label>
				  <div class="col-sm-10">
				     	{{ Form::select('branch_id', $branches, Input::old('branch_id'), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop