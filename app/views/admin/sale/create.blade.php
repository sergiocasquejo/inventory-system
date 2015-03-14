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
				@if ($reviews)
					<table class="table table-striped table-advance table-hover">
						<tr>
							<th>Product</th>
							<th>Branch</th>
							<th>Qty</th>
							<th>Total</th>
							<th>Date</th>
							<th>Status</th>
							<th>Cmnts</th>
							<th></th>
						</tr>
						@foreach ($reviews as $key => $review)
						<tr>
							<td><strong>{{{ \Product::find($review['product_id'])->name }}}</strong></td>
							<td>{{ \Branch::find($review['branch_id'])->address }}</td>
							<td>{{ $review['quantity'] .' '.$review['uom'] }}</td>
							<td>{{ $review['total_amount'] }}</td>
							<td>{{ $review['date_of_sale'] }}</td>
							<td>{{ $review['status'] ? 'Active' : 'Inactive' }}</td>
							<td><a class="badge bg-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="{{{ $review['comments'] }}}">?</a></td>
							<td>
								<a data-review-id="{{{ $key }}}" href="#" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
								<a data-review-id="{{{ $key }}}" href="#" data-confirm="Are you sure?" data-method="DELETE" title="Delete" class="btn btn-danger btn-xs">
                                  <i class="icon-remove"></i>
                                </a>
							</td>

						</tr>
						@endforeach
					</table>
					@if (count($reviews) != 0)
					<button type="submit" name="action" value="save" class="btn btn-shadow btn-primary pull-right">Save</button>
					@endif
				@endif
			</div>
    	</div>
    	<div class="col-md-7 panel">
			<header class="panel-heading">
			 Create Sale
			</header>
			<div class="panel-body">
			  	<form id="saleForm" action="{{ route('admin_sales.store') }}"  class="form-horizontal tasi-form" method="POST">
			  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			       	<div class="form-group">
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-10">
							{{ Form::select('branch_id', $branches, Input::old('branch_id'), ['class' => 'form-control m-bot15', (!\Confide::user()->isAdmin() ? 'disabled="disabled"' : '' )]) }}
							<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
						</div>
			      	</div>

			      	<div class="form-group">
						<label class="col-sm-2 control-label">Product</label>
						<div class="col-sm-10">
							{{ Form::select('product_id', $products, Input::old('product_id'), ['class' => 'form-control m-bot15']) }}
							<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
						</div>
			      	</div>

			      	<div class="form-group">
					  <label class="col-sm-2 control-label">Unit of measure</label>
					  <div class="col-sm-10">
					  	
					      {{ Form::select('uom', $measures, Input::old('uom'), ['class' => 'form-control m-bot15', 'data-selected' =>  Input::old('uom')]) }}
					  </div>
					</div>
					

			      	<div class="form-group">
					  <label class="col-sm-2 control-label">Quantity</label>
					  <div class="col-sm-10">
					      <input type="number" step="any"  name="quantity" value="{{ Input::old('quantity') }}" class="form-control">
					  </div>
					</div>

					

					<div class="form-group">
					  <label class="col-sm-2 control-label">Total Amount</label>
					  <div class="col-sm-10">
					      <input type="number" name="total_amount" value="{{ Input::old('total_amount') }}" class="form-control" readonly>
					  </div>
					</div>


					<div class="form-group">
						<label class="col-sm-2 control-label">Comments</label>
						<div class="col-sm-10">
							<textarea name="comments" class="form-control">{{ Input::old('comments') }}</textarea>
						</div>
					</div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Date of sale</label>
					  <div class="col-sm-10">
					      <input type="text" name="date_of_sale" value="{{ Input::old('date_of_sale', date('Y-m-d')) }}" class="form-control datepicker">
					  </div>
					</div>
					
					<div class="form-group">
					  <label class="col-sm-2 control-label">Status</label>
					  <div class="col-sm-10">
					      {{ Form::select('status', \Config::get('agrivate.statuses'), Input::old('status'), ['class' => 'form-control m-bot15']) }}
					  </div>
					</div>
					<button type="submit" name="action" value="review" class="btn btn-shadow btn-info">Add to Review</button>
					<button type="submit" name="action" value="save" class="btn btn-shadow btn-primary pull-right">Save</button>
			  </form>
			</div>
		</div>
	</section>
	<!-- page end-->
@stop