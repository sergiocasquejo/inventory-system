<div class="row">
	<div class="col-lg-12">

		@if (Session::has('error'))
		<div class="alert alert-block alert-danger fade in">
		  <button data-dismiss="alert" class="close close-sm" type="button">
		      <i class="icon-remove"></i>
		  </button>
		  <strong>Oh snap!</strong> {{ Session::get('error') }}.
		</div>
		@endif
		@if (Session::has('success'))
			<div class="alert alert-success fade in">
			  <button data-dismiss="alert" class="close close-sm" type="button">
			      <i class="icon-remove"></i>
			  </button>
			  <strong>Well done!</strong> {{ Session::get('success') }}.
			</div>	
		@endif

		@if (Session::has('warning'))
			<div class="alert alert-warning fade in">
			  <button data-dismiss="alert" class="close close-sm" type="button">
			      <i class="icon-remove"></i>
			  </button>
			  <strong>Warning!</strong> {{ Session::get('warning') }}.
			</div>
		@endif

		@if ($errors->has())
			<div class="alert alert-warning fade in">
			  <button data-dismiss="alert" class="close close-sm" type="button">
			      <i class="icon-remove"></i>
			  </button>
			  <strong>Warning!</strong> 
			  	<ul>
		        @foreach ($errors->all() as $error)
		            <li>{{ $error }}</li>
		        @endforeach
		        </ul>
			</div>
		@endif


		@if (Session::has('info'))
			<div class="alert alert-info fade in">
			  <button data-dismiss="alert" class="close close-sm" type="button">
			      <i class="icon-remove"></i>
			  </button>
			  <strong>Heads up!</strong> {{ Session::get('info') }}.
			</div>
		@endif
		
	</div>
</div>







