@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
		<header class="panel-heading">
		 Create User
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_users.update', $user->id) }}" enctype="multipart/form-data"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input type="hidden" name="_method" value="PUT" />

		       	<div class="form-group">
					<label class="col-sm-2 control-label" required>Branch</label>
					<div class="col-sm-10">
						{{ Form::select('branch_id', $branches, Input::old('branch_id', $user->id), ['class' => 'form-control m-bot15']) }}
						<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
					</div>
		      	</div>

		      	<div class="form-group">
				  <label class="col-sm-2 control-label">Username</label>
				  <div class="col-sm-10">
				      <input type="text" name="username" value="{{ Input::old('username', $user->username) }}" class="form-control" minlength="5" required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Email Address</label>
				  <div class="col-sm-10">
				      <input type="email" name="email" value="{{ Input::old('email', $user->email) }}" class="form-control" minlength="5" required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Password</label>
				  <div class="col-sm-10">
				      <input type="password" id="password" name="password" value="{{ Input::old('password') }}" class="form-control">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Confirm Password</label>
				  <div class="col-sm-10">
				      <input type="password" name="confirm_password" value="{{ Input::old('confirm_password') }}" class="form-control">
				  </div>
				</div>


				<div class="form-group">
				  <label class="col-sm-2 control-label">Display Name</label>
				  <div class="col-sm-10">
				      <input type="text" name="display_name" value="{{ Input::old('display_name', $user->display_name) }}" class="form-control" minlength="5" required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">First Name</label>
				  <div class="col-sm-10">
				      <input type="text" name="first_name" value="{{ Input::old('first_name', $user->first_name) }}" class="form-control"  required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Last Name</label>
				  <div class="col-sm-10">
				      <input type="text" name="last_name" value="{{ Input::old('last_name', $user->last_name) }}" class="form-control"  required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Is Administrator?</label>
				  <div class="col-sm-10">
				      {{ Form::select('is_admin', ['0' => 'No', '1' => 'Yes'], Input::old('is_admin', $user->is_admin), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Confirmed</label>
				  <div class="col-sm-10">
				      {{ Form::select('confirmed', ['0' => 'No', '1' => 'Yes'], Input::old('confirmed', $user->confirmed), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Photo</label>
				  <div class="col-sm-10">
				      <img alt="" width="30" height="30" src="{{ \Confide::user()->avatar($user->id)->thumbnail }}">
				      <input type="file" name="photo" accept="image/*"/>
				  </div>
				</div>

				
				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status',  \Config::get('agrivate.statuses'), Input::old('status', $user->status), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Updated</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop