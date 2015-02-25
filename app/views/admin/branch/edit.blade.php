@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Edit Branch
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_branches.update', $branch->id) }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input name="_method" type="hidden" value="PUT">
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Name</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name', $branch->name) }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label">Address</label>
				  <div class="col-sm-10">
				      <input type="text" name="address" class="form-control" value="{{ Input::old('address', $branch->address) }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">City</label>
				  <div class="col-sm-10">
				      <input type="text" name="city" class="form-control" value="{{ Input::old('city', $branch->city) }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Zip code/Postal code</label>
				  <div class="col-sm-10">
				     	<input type="text" name="post_code" class="form-control" value="{{ Input::old('post_code', $branch->post_code) }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status', \Config::get('agrivate.statuses'), Input::old('status', $branch->status), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Update</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop