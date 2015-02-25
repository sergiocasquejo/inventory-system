@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Create Category <a class="btn btn-info btn-xs" href="{{ route('admin_categories.create') }}">Add New</a> 
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_categories.update', $category->category_id) }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input type="hidden" name="_method" value="PUT" />
		  		<div class="form-group">
				  <label class="col-sm-2 control-label">Brand</label>
				  <div class="col-sm-10">
				  		@if ($brands)
				  			@foreach ($brands as $brand)
				  				<input type="checkbox" name="brand[]" value="{{ $brand->brand_id }}" {{{ in_array($brand->brand_id, $category_brands) ? 'checked' : '' }}}> {{ $brand->name }}
				  			@endforeach
				  		@endif
				  </div>
				</div>
		       	<div class="form-group">
		          <label class="col-sm-2 control-label">Name</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name', $category->name) }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
		          <label class="col-sm-2 control-label">Description</label>
		          <div class="col-sm-10">
		              <textarea name="description" class="form-control">{{ Input::old('description', $category->description) }}</textarea>
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>



				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop