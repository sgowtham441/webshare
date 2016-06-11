@extends('layouts.default')
@section('content')
  <div class="col-sm-offset-1 col-sm-10">
 <div class="jumbotron">
  <p>Welcome {{ Auth::user()->name }}</p>
  
   <p>You are elagible for {{ Auth::user()->url_allowed }} URl and URL per user {{ Auth::user()->user_per_url }}</p>
</div>
 
</div>

@stop