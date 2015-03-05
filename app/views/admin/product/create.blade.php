@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
		<header class="panel-heading">
		 Create Product 
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_products.store') }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  			<div class="form-group">
			          <label class="col-sm-2 control-label">Brand</label>
			          <div class="col-sm-10">
			              {{ Form::select('brand_id', $brands, Input::old('brand_id', 0), ['class' => 'form-control m-bot15']) }}
			              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
			          </div>
			      	</div>
			      	<div class="form-group">
			          <label class="col-sm-2 control-label">Category</label>
			          <div class="col-sm-10">
			              {{ Form::select('category_id', $categories, Input::old('category_id', 0), ['class' => 'form-control m-bot15', 'data-selected' => Input::old('category_id', 0)]) }}
			              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
			          </div>
			      	</div>
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
					  <label class="col-sm-2 control-label">Unit of Measures</label>
					  <div class="col-sm-10">
					  		@if ($measures)
					  			@foreach ($measures as $key => $value)
					  				<input type="checkbox" name="uom[]" value="{{ $key }}" 
					  				{{ in_array( $key, Input::old('uom[]', []) ) ? 'checked="checked"' : '' }} /> {{ $value }}
					  			@endforeach
					  		@endif
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
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->

@stop