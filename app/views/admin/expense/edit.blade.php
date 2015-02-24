@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Form Elements
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_expenses.update') }}"  class="form-horizontal tasi-form" method="PUT">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Expense for</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name', $expense->name) }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label">Total Amount</label>
				  <div class="col-sm-10">
				      <input type="number" name="total_amount" class="form-control" value="{{ Input::old('total_amount', $expense->total_amount) }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Quantity</label>
				  <div class="col-sm-10">
				      <input type="number" name="quantity" class="form-control" value="{{ Input::old('quantity', $expense->quantity) }}" />
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Unit of measure</label>
				  <div class="col-sm-10">
				  		{{ Form::select('uom', ['' => 'Select'], Input::old('uom', $expense->uom), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Comments</label>
				  <div class="col-sm-10">
				      <textarea name="comments" class="form-control">{{{ Input::old('quantity', $expense->quantity) }}}</textarea>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Status</label>
				  <div class="col-sm-10">
				      {{ Form::select('status', ['' => 'Select'], Input::old('status', $expense->status), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Date of expense</label>
				  <div class="col-sm-10">
				      <input type="date" name="date_of_expense" class="form-control" value="{{ Input::old('date_of_expense', $expense->date_of_expense) }}" />
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Update</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop