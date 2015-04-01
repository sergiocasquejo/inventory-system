@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="form-group">
    	<div class="col-md-7" style="padding-left:0;">
    		<div class="panel">
	    		<header class="panel-heading">
				 	Preview
				</header>
			</div>
			<div class="panel-body" style="padding:0;">
				<form action="{{ route('admin_credits.saveReview') }}" method="POST" />
					<table class="table table-striped table-advance table-hover">
						<tr>
							<th>Customer</th>
							<th>Product</th>
                            @if (\Confide::user()->isAdmin())
							<th>Branch</th>
                            @endif
							<th>Qty</th>
							<th>Total</th>
							<th>Date</th>
							<th>Cmnts</th>
							<th></th>
						</tr>
						@if ($reviews)
							@foreach ($reviews as $key => $review)
							<tr>
								<td data-customer_name="{{ $review['customer_name'] }}" 
								data-address="{{ $review['address'] }}" 
								data-contact_number="{{ $review['contact_number'] }}"
                                        data-customer_id="{{ $review['customer_id']  }}">
									<a class="badge bg-primary" data-html="true" data-container="body" data-toggle="popover" data-placement="top" 
										data-content="{{ 'Name: '. $review['customer_name'] .'<br />'. 
										'Address: '. $review['address'] .'<br />'.
										'Contact #: '.$review['contact_number'] }}">?</a>
								</td>
								<td data-branch="{{{ $review['branch_id'] }}}" data-product="{{{ $review['product_id'] }}}"><strong>{{{ \Product::find($review['product_id'])->name }}}</strong></td>
                                @if (\Confide::user()->isAdmin())
								<td>{{ \Branch::find($review['branch_id'])->address }}</td>
                                @endif
								<td data-quantity="{{{ $review['quantity'] }}}" data-uom="{{{ $review['uom'] }}}">{{$review['quantity'] .' '.$review['uom'] }}</td>
								<td data-total_amount="{{{ $review['total_amount'] }}}">{{ \Helper::nf($review['total_amount']) }}</td>
								<td data-date_of_sale="{{{ $review['date_of_sale'] }}}">{{ $review['date_of_sale'] }}</td>
								<td data-comments="{{{ $review['comments'] }}}"><a class="badge bg-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="{{{ $review['comments'] }}}">?</a></td>
								<td>
									<a data-review-id="{{{ $key }}}" href="#" class="btn btn-primary btn-xs edit-credits-review" title="Edit"><i class="icon-pencil"></i></a>
									<a data-review-id="{{{ $key }}}" href="{{ route('admin_credits.deleteReview', $key) }}" title="Delete" class="btn btn-danger btn-xs delete-credits-review">
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
    	<div class="col-md-5 panel">
			<header class="panel-heading">
			 Create Credit
			</header>
			<div class="panel-body">
			  	<form action="{{ route('admin_credits.store') }}" id="creditForm"  class="form-horizontal tasi-form" method="POST">
			  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="customer_id" value="{{ Input::old('customer_id', 0)  }}">
			  		<div class="form-group">
			          <label class="col-sm-2 control-label">Branch</label>
			          <div class="col-sm-10">
			              {{ Form::select('branch_id', $branches, Input::old('branch_id', 0), ['class' => 'form-control m-bot15', (!\Confide::user()->isAdmin() ? 'disabled="disabled"' : '')]) }}
			              <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
			          </div>
			      	</div>
			       <div class="form-group">
			          <label class="col-sm-2 control-label">Customer Name</label>
			          <div class="col-sm-10">
			              <input type="text" name="customer_name" autocomplete="off" maxlength="255" class="form-control typeahead" value="{{ Input::old('customer_name') }}" />
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
                        <label class="col-sm-2 control-label">Unit of measure</label>
                        <div class="col-sm-10">
                            {{ Form::select('uom', $measures, Input::old('uom'), ['class' => 'form-control m-bot15', 'data-selected' => Input::old('uom')]) }}
                        </div>
                    </div>


			      	<div class="form-group">
					  <label class="col-sm-2 control-label">Quantity</label>
					  <div class="col-sm-10">
					      <input type="number"  step="any" name="quantity"  data-selected="{{ Input::old('uom') }}" value="{{ Input::old('quantity') }}" class="form-control">
					  </div>
					</div>



					<div class="form-group">
					  <label class="col-sm-2 control-label">Total Amount</label>
					  <div class="col-sm-10">
                            <span class="total_amount">Php 0.00</span>
					      <input type="hidden" name="total_amount" value="{{ Input::old('total_amount') }}" data-selected="{{ Input::old('total_amount') }}" class="form-control" readonly>
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
					      <input type="text" name="date_of_sale" value="{{ Input::old('date_of_sale', date('Y-m-d')) }}" class="form-control datepicker">
					  </div>
					</div>
					
					<!--<div class="form-group">
					  <label class="col-sm-2 control-label">Is Paid</label>
					  <div class="col-sm-10">
					      {{ Form::select('is_paid', \Config::get('agrivet.credit_statuses'), Input::old('is_paid', 0), ['class' => 'form-control m-bot15', 'disabled' => 'disabled']) }}
					  </div>
					</div>-->


					<button type="submit" name="action" value="review" class="btn btn-shadow btn-info">Add to Review</button>
					<button type="submit" name="action" value="save" class="btn btn-shadow btn-primary">Save</button>
					<a href="{{ route('admin_credits.create') }}" class="btn btn-shadow btn-warning">Cancel</a>
			  </form>
			</div>
		</div>
	</section>
	<!-- page end-->
@stop