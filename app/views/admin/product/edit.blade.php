@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
		<header class="panel-heading">
		 Create Product
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_products.update') }}"  class="form-horizontal tasi-form" method="PUT">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
				  <label class="col-sm-2 control-label">Quantity</label>
				  <div class="col-sm-10">
				      <textarea name="comments" class="form-control">{{ Input::old('comments', $product->comments) }}</textarea>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status', ['' => 'Select'], Input::old('status', $product->status), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop