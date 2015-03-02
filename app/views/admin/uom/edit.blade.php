@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Edit Brand <a class="btn btn-info btn-xs" href="{{ route('admin_uoms.create') }}">Add New</a> 
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_uoms.update', $uom->uom_id) }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input type="hidden" name="_method" value="PUT" />
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Name</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name', $uom->name) }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
		          <label class="col-sm-2 control-label">Description</label>
		          <div class="col-sm-10">
		              <input type="text" name="label" maxlength="255" class="form-control" value="{{ Input::old('label', $uom->label) }}" />
		          </div>
		      	</div>



				<button type="submit" class="btn btn-shadow btn-primary">Update</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop