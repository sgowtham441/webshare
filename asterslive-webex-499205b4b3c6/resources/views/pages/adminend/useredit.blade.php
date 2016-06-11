@extends('layouts.default')
@section('content')
   <div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					Group User Register
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- Group SUer Registration -->
					<form action="{{ url() }}/ALadmin/user/saveprofile/{{ $user->id }}" method="POST" class="form-horizontal">
						{{ csrf_field() }}

						<!-- Name -->
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label">Name</label>

							<div class="col-sm-6">
								<input type="text" name="name" class="form-control" value="{{ $user->name }}">
							</div>
						</div>
						<!-- Url allowed peruser -->
						<div class="form-group">
							<label for="URL allowed peruser" class="col-sm-3 control-label">URL allowed peruser</label>
                            <div class="col-sm-6">
								<input type="number" name="url_allowed" class="form-control"  value="{{ $user->url_allowed }}">
							</div>
						</div>
						
						<!-- Url allowed peruser -->
						<div class="form-group">
							<label for="User per URL" class="col-sm-3 control-label">User per URL</label>
                            <div class="col-sm-6">
								<input type="number" name="user_per_url" class="form-control" value="{{ $user->user_per_url }}">
							</div>
						</div>

						<!-- Register Button -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-btn fa-sign-in"></i>Register
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop