@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Create Stock
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_stocks.store') }}" id="stockForm"   class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-10">
                        {{ Form::select('branch_id', $branches, Input::old('branch_id'), ['class' => 'form-control m-bot15']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Supplier</label>
                    <div class="col-sm-10">
                        {{ Form::select('supplier', ['' => 'Select Supplier'], Input::old('supplier'), ['class' => 'form-control']) }}
                    </div>
                </div>

		       <div class="form-group">
		          <label class="col-sm-2 control-label">Product</label>
		          <div class="col-sm-10">
		              {{ Form::select('product_id', ['Select Product'], Input::old('product_id', ''), ['class' => 'form-control', 'data-selected' => Input::old('product_id')]) }}
		          </div>
		      	</div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Unit of Measure</label>
                    <div class="col-sm-10">
                        {{ Form::select('uom', $dd_measures, Input::old('uom'), ['class' => 'form-control', 'data-selected' => Input::old('uom')]) }}
                    </div>
                </div>


				<div class="form-group">
				  <label class="col-sm-2 control-label">Stocks</label>
				  <div class="col-sm-10">
				     <input type="number" step="any"  name="total_stocks" min="0" placeholder="Stocks" value="{{ Input::old('total_stocks') }}" class="form-control">
				  </div>
				</div>



                <h3>Payables</h3>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Brand</label>
                    <div class="col-sm-10">
                        {{ Form::select('brand', $brands, Input::old('brand'), ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Is Payable</label>
                    <div class="col-sm-10">
                        <input type="checkbox"  name="is_payable" value="1">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Payable</label>
                    <div class="col-sm-10">
                        <input type="number" step="any"  name="total_amount" min="0" placeholder="Payable" value="{{ Input::old('total_amount') }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Comments</label>
                    <div class="col-sm-10">
                        <textarea name="comments" class="form-control" placeholder="Enter your comments">{{ Input::old('comments') }}</textarea>
                    </div>
                </div>





				<button type="submit" class="btn btn-shadow btn-primary">Create</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop