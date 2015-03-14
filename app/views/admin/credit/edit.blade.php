@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Edit Credit
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_credits.update', $credit->credit_id) }}" id="creditForm"   class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input type="hidden" name="_method" value="PUT" />
		  		<div class="form-group">
		          <label class="col-sm-2 control-label">Branch</label>
		          <div class="col-sm-10">
		              {{ Form::select('branch_id', $branches, Input::old('branch_id', $credit->sale->branch_id), ['class' => 'form-control m-bot15', (!\Confide::user()->isAdmin() ? 'disabled="disabled"' : '')]) }}
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>
		       <div class="form-group">
		          <label class="col-sm-2 control-label">Customer Name</label>
		          <div class="col-sm-10">
		              <input type="text" name="customer_name" maxlength="255" class="form-control" value="{{ Input::old('customer_name', $credit->customer_name) }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
		          <label class="col-sm-2 control-label">Address</label>
		          <div class="col-sm-10">
		              <textarea name="address" class="form-control">{{ Input::old('address', $credit->address) }}</textarea>
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
		          <label class="col-sm-2 control-label">Customer Contact Number</label>
		          <div class="col-sm-10">
		              <input type="text" name="contact_number" maxlength="255" class="form-control" value="{{ Input::old('contact_number', $credit->contact_number) }}" />
		              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
		          </div>
		      	</div>

		      	<div class="form-group">
					<label class="col-sm-2 control-label">Product</label>
					<div class="col-sm-10">
						{{ Form::select('product_id', $products, Input::old('product_id', $credit->sale->product_id), ['class' => 'form-control m-bot15', 'data-selected' => Input::old('product_id', $credit->sale->product_id)]) }}
						<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
					</div>
		      	</div>

		      	<div class="form-group">
				  <label class="col-sm-2 control-label">Quantity</label>
				  <div class="col-sm-10">
				      <input type="number"  step="any" name="quantity" value="{{ Input::old('quantity', $credit->sale->quantity) }}" data-selected="{{ Input::old('quantity', $credit->sale->quantity) }}" class="form-control">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Unit of measure</label>
				  <div class="col-sm-10">
				      {{ Form::select('uom', $measures, Input::old('uom', $credit->sale->uom), ['class' => 'form-control m-bot15', 'data-selected' => Input::old('uom',$credit->sale->uom)]) }}
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Total Amount</label>
				  <div class="col-sm-10">
				      <input type="number" name="total_amount" value="{{ Input::old('total_amount', $credit->sale->total_amount) }}" class="form-control" data-selected="{{ Input::old('total_amount', $credit->sale->total_amount) }}" readonly>
				  </div>
				</div>


				<div class="form-group">
					<label class="col-sm-2 control-label">Comments</label>
					<div class="col-sm-10">
						<textarea name="comments" class="form-control">{{ Input::old('comments', $credit->sale->comments) }}</textarea>
					</div>
				</div>

				<div class="form-group">
				  <label class="col-sm-2 control-label">Date of credit</label>
				  <div class="col-sm-10">
				      <input type="text" name="date_of_sale" value="{{ Input::old('date_of_sale', $credit->sale->date_of_sale) }}" class="form-control datepicker">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-sm-2 control-label">Is Paid</label>
				  <div class="col-sm-10">
				      {{ Form::select('is_paid', \Config::get('agrivate.credit_statuses'), Input::old('is_paid' , $credit->is_paid), ['class' => 'form-control m-bot15']) }}
				  </div>
				</div>


				<button type="submit" class="btn btn-shadow btn-primary">Update</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop