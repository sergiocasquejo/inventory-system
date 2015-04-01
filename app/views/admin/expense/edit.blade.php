@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Edit Expense
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_expenses.update', $expense->expense_id) }}" id="expenseFormx"   class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input type="hidden" name="_method" value="PUT" />
		  		
		  		<div class="form-group">
		          <label class="col-sm-2 control-label">Branch</label>
		          <div class="col-sm-10">
		              {{ Form::select('branch_id', $branches, Input::old('branch_id', $expense->branch_id), ['class' => 'form-control m-bot15', 'disabled' => true]) }}
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
		       	<div class="form-group">
		          <label class="col-sm-2 control-label">TYPE OF EXPENSE</label>
		          <div class="col-sm-10">
		              {{ Form::select('expense_type', ['STORE EXPENSES' => 'STORE EXPENSES', 'PRODUCT EXPENSES' => 'PRODUCT EXPENSES'], Input::old('expense_type', $expense->expense_type), ['class' => 'form-control m-bot15', 'disabled' => true]) }}
		          </div>
		      	</div>
		       	<div class="form-group">
		          <label class="col-sm-2 control-label">Expense Title</label>
		          <div class="col-sm-10">
		              <input type="text" name="name" maxlength="255" class="form-control"  data-selected="{{ Input::old('name', $expense->name) }}" value="{{ Input::old('name', $expense->name) }}" disabled/>
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
		      	
		      	<div class="form-group">
				  <label class="col-sm-2 control-label">Unit of measure</label>
				  <div class="col-sm-10">
				  		{{ Form::select('uom', $measures, Input::old('uom', $expense->uom), ['class' => 'form-control m-bot15' , 'data-selected' => Input::old('uom', $expense->uom), 'disabled' => true]) }}
				  </div>
				</div>


		      	<div class="form-group">
				  <label class="col-sm-2 control-label">Quantity</label>
				  <div class="col-sm-10">
				      <input type="number" step="any" name="quantity" data-selected="{{ Input::old('quantity', $expense->quantity) }}" class="form-control" value="{{ Input::old('quantity', $expense->quantity) }}" disabled/>
				  </div>
				</div>


				<div class="form-group">
				  <label class="col-sm-2 control-label">Total Amount</label>
				  <div class="col-sm-10">
                      <span class="total_amount" data-selected="{{ Input::old('total_amount', $expense->total_amount) }}">{{ Helper::nf((float)Input::old('total_amount', $expense->total_amount)) }}</span>
				      <input type="hidden"  step="any" name="total_amount" class="form-control"  data-selected="{{ Input::old('total_amount', $expense->total_amount) }}" value="{{ Input::old('total_amount', $expense->total_amount) }}" disabled/>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Comments</label>
				  <div class="col-sm-10">
				      <textarea name="comments" class="form-control">{{{ Input::old('comments', $expense->comments) }}}</textarea>
				  </div>
				</div>


				<div class="form-group">
				  <label class="col-sm-2 control-label">Date of expense</label>
				  <div class="col-sm-10">
				      <input type="text" name="date_of_expense" class="form-control datepicker" value="{{ Input::old('date_of_expense', $expense->date_of_expense) }}" />
				  </div>
				</div>
				<button type="submit" class="btn btn-shadow btn-primary">Update</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop