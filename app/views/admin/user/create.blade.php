@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

	<div class="row">

      <aside class="profile-info col-lg-9">
      		<form action="{{ route('admin_users.store') }}"  class="form-horizontal tasi-form" enctype="multipart/form-data" method="POST">
         	 <section class="panel">
              <div class="bio-graph-heading">
                  <h1> Profile Info</h1>
              </div>
              <div class="panel-body bio-graph-info">
                  
			  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		       	<div class="form-group">
					<label class="col-sm-2 control-label" required>Branch</label>
					<div class="col-sm-10">
						{{ Form::select('branch_id', $branches, Input::old('branch_id', 0), ['class' => 'form-control m-bot15']) }}
						<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
					</div>
		      	</div>

		      	<div class="form-group">
				  <label class="col-sm-2 control-label">Username</label>
				  <div class="col-sm-10">
				      <input type="text" name="username" value="{{ Input::old('username') }}" class="form-control" minlength="5" required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Email Address</label>
				  <div class="col-sm-10">
				      <input type="email" name="email" value="{{ Input::old('email') }}" class="form-control" minlength="5" required>
				  </div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label">Contact No.</label>
				 	<div class="col-sm-10">
				      <input type="text" name="contact_no" value="{{ Input::old('contact_no') }}" class="form-control" minlength="5" required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Address</label>
				  <div class="col-sm-10">
				      <textarea name="address" class="form-control" minlength="5" required>{{ Input::old('address') }}</textarea>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Birth Day</label>
				  <div class="col-sm-10">
				      <input type="text" name="birthdate" value="{{ Input::old('birthdate') }}" class="form-control datepicker" minlength="5" required>
				  </div>
				</div>



				


				<div class="form-group">
				  <label class="col-sm-2 control-label">Display Name</label>
				  <div class="col-sm-10">
				      <input type="text" name="display_name" value="{{ Input::old('display_name') }}" class="form-control" minlength="5" required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">First Name</label>
				  <div class="col-sm-10">
				      <input type="text" name="first_name" value="{{ Input::old('first_name') }}" class="form-control"  required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Last Name</label>
				  <div class="col-sm-10">
				      <input type="text" name="last_name" value="{{ Input::old('last_name') }}" class="form-control"  required>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Is Administrator?</label>
				  <div class="col-sm-10">
				      {{ Form::select('is_admin', ['0' => 'No', '1' => 'Yes'], Input::old('is_admin', 0), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Confirmed</label>
				  <div class="col-sm-10">
				      {{ Form::select('confirmed', ['0' => 'No', '1' => 'Yes'], Input::old('confirmed', 0), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Photo</label>
				  <div class="col-sm-10">
				      <input type="file" name="photo" accept="image/*"/>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status',  \Config::get('agrivet.statuses'), Input::old('status'), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>
					
			  
              </div>
          </section>
          <section>
              <div class="panel panel-primary">
                  <div class="panel-heading"> Sets New Password &amp; Avatar</div>
                  <div class="panel-body">
                  	<div class="form-group">
						  <label class="col-sm-2 control-label">Password</label>
						  <div class="col-sm-10">
						      <input type="password" id="password" name="password" value="{{ Input::old('password') }}" class="form-control" minlength="5" required>
						  </div>
						</div>

						<div class="form-group">
						  <label class="col-sm-2 control-label">Confirm Password</label>
						  <div class="col-sm-10">
						      <input type="password" name="confirm_password" value="{{ Input::old('confirm_password') }}" class="form-control" minlength="5" required>
						  </div>
						</div>
					<button type="submit" class="btn btn-shadow btn-primary">Create</button>
                  </div>
              </div>
          </section>
          </form>
      </aside>
  </div>
@stop