@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <section class="panel">
		<header class="panel-heading">
		 Create Product 
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_products.store') }}"  class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Supplier</label>
                        <div class="col-sm-10">
                            {{ Form::select('supplier', $suppliers, Input::old('supplier', ''), ['class' => 'form-control m-bot15']) }}
                        </div>
                    </div>
		  			<div class="form-group">
			          <label class="col-sm-2 control-label">Brand</label>
			          <div class="col-sm-10">
			              {{ Form::select('brand_id', $brands, Input::old('brand_id', 0), ['class' => 'form-control m-bot15']) }}
			          </div>
			      	</div>
			      	<div class="form-group">
			          <label class="col-sm-2 control-label">Category</label>
			          <div class="col-sm-10">
			              {{ Form::select('category_id', $categories, Input::old('category_id', 0), ['class' => 'form-control m-bot15', 'data-selected' => Input::old('category_id', 0)]) }}
			          </div>
			      	</div>
			       <div class="form-group">
			          <label class="col-sm-2 control-label">Name</label>
			          <div class="col-sm-10">
			              <input type="text" name="name" maxlength="255" class="form-control" value="{{ Input::old('name') }}" />
			          </div>
			      	</div>
					<div class="form-group">
					  <label class="col-sm-2 control-label">Description</label>
					  <div class="col-sm-10">
					      <textarea name="description" class="form-control">{{ Input::old('description') }}</textarea>
					  </div>
					</div>
					<div class="form-group">
					  <label class="col-sm-2 control-label">Unit of Measures</label>
					  <div class="col-sm-10">
					  		@if ($measures)
					  			@foreach ($measures as $key => $value)
					  				<input type="checkbox" name="uom[]" value="{{ $key }}" 
					  				{{ in_array( $key, Input::old('uom[]', []) ) ? 'checked="checked"' : '' }} /> {{ $value }}
					  			@endforeach
					  		@endif
					  </div>
					</div>
                    <div class="form-group sack-to-kilo-box {{ in_array( 'sack(s)', Input::old('uom[]', []) ) ? '' : 'hidden' }}">
                        <label class="col-sm-2 control-label">1 Sack to Kilos</label>
                        <div class="col-sm-10">
                            <input type="number" step="any"  name="sack_to_kg" min="0" placeholder="1 Sack to Kilos" value="{{ Input::old('sack_to_kg', \Config::get('agrivet.equivalent_measure.sacks.per')) }}" class="form-control">
                        </div>
                    </div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Comments</label>
					  <div class="col-sm-10">
					      <textarea name="comments" class="form-control">{{ Input::old('comments') }}</textarea>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-sm-2 control-label">Status</label>
					  <div class="col-sm-10">
					      {{ Form::select('status', \Config::get('agrivet.statuses'), Input::old('status'), ['class' => 'form-control m-bot15']) }}
					  </div>
					</div>
				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->

@stop