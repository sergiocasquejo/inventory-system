@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	 <section class="form-group">
    	<div class="col-md-5" style="padding-left:0;">
    		<div class="panel">
	    		<header class="panel-heading">
				 	Preview
				</header>
			</div>
			<div class="panel-body" style="padding:0;">
				<form action="{{ route('admin_expenses.saveReview') }}" method="POST" />
					<table class="table table-striped table-advance table-hover">
						<tr>
                            @if (\Confide::user()->isAdmin())
							<th>Branch</th>
                            @endif
							<th>Type</th>
							<th>Name</th>
							<th>Qty.</th>
							<th>Total</th>
							<th>Date</th>
							<th>Cmnts</th>
							<th></th>
						</tr>
						@if ($reviews)
							@foreach ($reviews as $key => $review)
							<tr>
                                @if (\Confide::user()->isAdmin())
								<td>{{ \Branch::find($review['branch_id'])->address }}</td>
                                @endif
								<td data-branch="{{{ $review['branch_id'] }}}" data-expense_type="{{{ $review['expense_type'] }}}">{{{ $review['expense_type'] }}}</td>
								<td data-name="{{{ $review['name'] }}}">
									<strong>{{{ is_numeric($review['name']) ? \Product::find($review['name'])->name : $review['name']  }}}</strong>
								</td>
								<td data-quantity="{{{ $review['quantity'] }}}" data-uom="{{{ $review['uom'] }}}">{{ \Helper::nf($review['quantity']) .' '.$review['uom'] }}</td>
								<td data-total_amount="{{{ $review['total_amount'] }}}">{{ $review['total_amount'] }}</td>
								<td data-date_of_expense="{{{ $review['date_of_expense'] }}}">{{ $review['date_of_expense'] }}</td>
								<td data-comments="{{{ $review['comments'] }}}"><a class="badge bg-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="{{{ $review['comments'] }}}">?</a></td>
								<td>
									<a data-review-id="{{{ $key }}}" href="#" class="btn btn-primary btn-xs edit-expense-review" title="Edit"><i class="icon-pencil"></i></a>
									<a data-review-id="{{{ $key }}}" href="{{ route('admin_expenses.deleteReview', $key) }}" title="Delete" class="btn btn-danger btn-xs delete-expense-review">
	                                  <i class="icon-remove"></i>
	                                </a>
								</td>
							</tr>
							@endforeach
						@endif
					</table>
					@if (count($reviews) != 0)
					<button type="submit" name="action" value="save" class="btn btn-shadow btn-primary pull-right">Save</button>
					@endif
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				</form>
				
			</div>
    	</div>
    	<div class="col-md-7 panel">
			<header class="panel-heading">
			 Create Expense
			</header>
			<div class="panel-body">
			  	<form action="{{ route('admin_expenses.store') }}" id="expenseForm"  class="form-horizontal tasi-form" method="POST">
			  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			  		
			  		<div class="form-group">
			          <label class="col-sm-2 control-label">Branch</label>
			          <div class="col-sm-10">
			              {{ Form::select('branch_id', $branches, Input::old('branch_id'), ['class' => 'form-control m-bot15', (!\Confide::user()->isAdmin() ? 'disabled="disabled"' : '')]) }}
			              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
			          </div>
			      	</div>
			      	<div class="form-group">
			          <label class="col-sm-2 control-label">TYPE OF EXPENSE</label>
			          <div class="col-sm-10">
			              {{ Form::select('expense_type', ['STORE EXPENSES' => 'STORE EXPENSES', 'PRODUCT EXPENSES' => 'PRODUCT EXPENSES'], Input::old('expense_type'), ['class' => 'form-control m-bot15', 'data-selected' => Input::old('expense_type')]) }}
			          </div>
			      	</div>
			       	<div class="form-group">
			          <label class="col-sm-2 control-label">Expense Title</label>
			          <div class="col-sm-10">
			              <input type="text" name="name" maxlength="255" class="form-control" data-selected="{{ Input::old('name') }}" value="{{ Input::old('name') }}" />
			              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
			          </div>
			      	</div>
			      	
			      	<div class="form-group">
					  <label class="col-sm-2 control-label">Unit of measure</label>
					  <div class="col-sm-10">
					  		{{ Form::select('uom', $measures, Input::old('uom'), ['class' => 'form-control m-bot15', 'data-selected' => Input::old('expense_type')]) }}
					  </div>
					</div>

			      	<div class="form-group">
					  <label class="col-sm-2 control-label">Quantity</label>
					  <div class="col-sm-10">
					      <input type="number" step="any" name="quantity" class="form-control" value="{{ Input::old('quantity') }}" />
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Total Amount</label>
					  <div class="col-sm-10">
					      <input type="number"  step="any" name="total_amount" class="form-control" value="{{ Input::old('total_amount') }}" />
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Comments</label>
					  <div class="col-sm-10">
					      <textarea name="comments" class="form-control">{{{ Input::old('quantity') }}}</textarea>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Date of expense</label>
					  <div class="col-sm-10">
					      <input type="text" name="date_of_expense" class="form-control datepicker" value="{{ Input::old('date_of_expense', date('Y-m-d')) }}" />
					  </div>
					</div>
					<button type="submit" name="action" value="review" class="btn btn-shadow btn-info">Add to Review</button>
					<button type="submit" name="action" value="save" class="btn btn-shadow btn-primary">Save</button>
					<a href="{{ route('admin_expenses.create') }}" class="btn btn-shadow btn-warning">Cancel</a>
			  </form>
			</div>
		</div>
	</section>
	<!-- page end-->
@stop