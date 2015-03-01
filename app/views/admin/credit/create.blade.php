@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Create Credit
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_credits.store') }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<div class="form-group">
		          <label class="col-sm-2 control-label">Branch</label>
		          <div class="col-sm-10">
		              {{ Form::select('branch_id', $branches, Input::old('branch_id', 0), ['class' => 'form-control m-bot15']) }}
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Customer Name</label>
		          <div class="col-sm-10">
		              <input type="text" name="customer_name" maxlength="255" class="form-control" value="{{ Input::old('customer_name') }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
		          <label class="col-sm-2 control-label">Address</label>
		          <div class="col-sm-10">
		              <textarea name="address" class="form-control">{{ Input::old('address') }}</textarea>
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
		          <label class="col-sm-2 control-label">Customer Contact Number</label>
		          <div class="col-sm-10">
		              <input type="text" name="contact_number" maxlength="255" class="form-control" value="{{ Input::old('contact_number') }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
					<label class="col-sm-2 control-label">Product</label>
					<div class="col-sm-10">
						{{ Form::select('product_id', $products, Input::old('product_id', 0), ['class' => 'form-control m-bot15']) }}
						<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
					</div>
		      	</div>

		      	<div class="form-group">
				  <label class="col-sm-2 control-label">Quantity</label>
				  <div class="col-sm-10">
				      <input type="number" name="quantity" value="{{ Input::old('quantity') }}" class="form-control">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Unit of measure</label>
				  <div class="col-sm-10">
				      {{ Form::select('uom', array_add(\Config::get('agrivate.unit_of_measure'), '', 'Select Measure'), Input::old('uom'), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Total Amount</label>
				  <div class="col-sm-10">
				      <input type="number" name="total_amount" value="{{ Input::old('total_amount') }}" class="form-control">
				  </div>
				</div>


				<div class="form-group">
					<label class="col-sm-2 control-label">Comments</label>
					<div class="col-sm-10">
						<textarea name="comments" class="form-control">{{ Input::old('comments') }}</textarea>
					</div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Date of credit</label>
				  <div class="col-sm-10">
				      <input type="text" name="date_of_credit" value="{{ Input::old('date_of_credit', date('m-d-Y')) }}" class="form-control datepicker">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-sm-2 control-label">Is Paid</label>
				  <div class="col-sm-10">
				      {{ Form::select('is_paid', \Config::get('agrivate.credit_statuses'), Input::old('is_paid'. 0), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>


				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop