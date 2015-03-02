@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Create Brand
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_uoms.store') }}"  class="form-horizontal tasi-form" autocomplete="off" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Name</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name') }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
		          <label class="col-sm-2 control-label">Label</label>
		          <div class="col-sm-10">
		              <input type="text" name="label" maxlength="255" class="form-control" value="{{ Input::old('label') }}" />
		          </div>
		      	</div>
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop