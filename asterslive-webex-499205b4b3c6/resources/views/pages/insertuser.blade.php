@extends('layouts.default')
@section('content')
<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					Enter Details
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- New Task Form -->
					<form action="{{ url() }}/join/{{$meetingid}}" method="POST" class="form-horizontal">
						{{ csrf_field() }}

						<!-- E-Mail Address -->
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">Name</label>

							<div class="col-sm-6">
								<input type="name" name="name" class="form-control" value="{{ old('name') }}">
							</div>
						</div>

						<!-- Password -->
						<div class="form-group">
							<label for="password" class="col-sm-3 control-label">Email</label>

							<div class="col-sm-6">
								<input type="email" name="email" class="form-control">
							</div>
						</div>
					
						<!-- Login Button -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-btn fa-sign-in"></i>Join
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop