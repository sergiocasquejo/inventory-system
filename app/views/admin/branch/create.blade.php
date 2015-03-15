@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Create Branch
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_branches.store') }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Name</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name') }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label">Address</label>
				  <div class="col-sm-10">
				      <input type="text" name="address" class="form-control" value="{{ Input::old('address') }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">City</label>
				  <div class="col-sm-10">
				      <input type="text" name="city" class="form-control" value="{{ Input::old('city') }}" />
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label">Zip code/Postal code</label>
				  <div class="col-sm-10">
				     	<input type="text" name="post_code" class="form-control" value="{{ Input::old('post_code') }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status', \Config::get('agrivet.statuses'), Input::old('status'), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop