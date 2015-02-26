@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Form Elements
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_expenses.store') }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		
		  		<div class="form-group">
		          <label class="col-sm-2 control-label">Branch</label>
		          <div class="col-sm-10">
		              {{ Form::select('branch_id', $branches, Input::old('branch_id'), ['class' => 'form-control m-bot15']) }}
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
		       	<div class="form-group">
		          <label class="col-sm-2 control-label">Expense for</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name') }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label">Total Amount</label>
				  <div class="col-sm-10">
				      <input type="number" name="total_amount" class="form-control" value="{{ Input::old('total_amount') }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Quantity</label>
				  <div class="col-sm-10">
				      <input type="number" name="quantity" class="form-control" value="{{ Input::old('quantity') }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Unit of measure</label>
				  <div class="col-sm-10">
				  		{{ Form::select('uom', array_add(\Config::get('agrivate.unit_of_measure'), '', 'Select Measure'), Input::old('uom'), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Comments</label>
				  <div class="col-sm-10">
				      <textarea name="comments" class="form-control">{{{ Input::old('quantity') }}}</textarea>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status', \Config::get('agrivate.statuses'), Input::old('status'), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Date of expense</label>
				  <div class="col-sm-10">
				      <input type="date" name="date_of_expense" class="form-control" value="{{ Input::old('date_of_expense') }}" />
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop