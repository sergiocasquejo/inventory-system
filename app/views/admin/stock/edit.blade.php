@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
	  <section class="panel">
		<header class="panel-heading">
		 Create Stock
		</header>
		<div class="panel-body">
		  	<form action="{{ route('admin_stocks.update', $stock->stock_on_hand_id) }}" id="stockForm"   class="form-horizontal tasi-form" method="POST">
		  		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		  		<input type="hidden" name="_method" value="PUT" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-10">
                        {{ Form::select('branch_id', $branches, Input::old('branch_id', $stock->branch_id), ['class' => 'form-control m-bot15']) }}
                    </div>
                </div>

		       <div class="form-group">
		          <label class="col-sm-2 control-label">Product</label>
		          <div class="col-sm-10">
		              {{ Form::select('product_id', $products, Input::old('product_id', $stock->product_id), ['class' => 'form-control']) }}
		          </div>
		      	</div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Unit of Measure</label>
                    <div class="col-sm-10">
                        {{ Form::select('uom', $dd_measures, Input::old('uom', $stock->uom), ['class' => 'form-control', 'data-selected' =>Input::old('uom', $stock->uom)]) }}
                    </div>
                </div>


				<div class="form-group">
				  <label class="col-sm-2 control-label">Stocks</label>
				  <div class="col-sm-10">
				     <input type="number" step="any"  name="total_stocks" min="0" placeholder="Stocks" value="{{ Input::old('total_stocks', $stock->total_stocks) }}" class="form-control"> 
				  </div>
				</div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Payable</label>
                    <div class="col-sm-10">
                        <input type="number" step="any"  name="payable" min="0" placeholder="Payable" value="{{ Input::old('payable') }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Is Payable</label>
                    <div class="col-sm-10">
                        <input type="checkbox"  name="is_payable" value="1">
                    </div>
                </div>



				<button type="submit" class="btn btn-shadow btn-primary">Update</button>
		  </form>
		</div>
	</section>
	<!-- page end-->
@stop