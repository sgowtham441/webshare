@if (count($errors) > 0)
	<!-- Form Error List -->
	<div class="alert alert-danger">
		<strong>Whoops! Something went wrong!</strong>

		<br><br>

		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

   <div> 
        @if (session('status'))
	        <div class="alert alert-success">
	        {{ session('status') }}
	       </div>
       	@endif
      </div>
      <div> 
        @if (session('error'))
	        <div class="alert alert-error-message">
	        {{ session('error') }}
	       </div>
       	@endif
      </div>