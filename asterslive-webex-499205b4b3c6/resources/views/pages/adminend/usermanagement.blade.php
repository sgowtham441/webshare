@extends('layouts.default')
@section('content')
 <div class="col-sm-offset-1 col-sm-10">
  <a class="btn btn-primary" href="{{ url() }}/ALadmin/user/add" role="button">Add</a><br><br>
 <table class="table table-hover table-bordered">
  <tr class="info">
  <td>ID</td>
  <td>Name</td>
  <td>Email ID</td>
  <td>ACTION</td>
</tr>

@foreach ($users as $user)
<tr  class="active">
  <td>{{ $user->id }}</td>
  <td>{{ $user->name }}</td>
  <td>{{ $user->email }}</td>
  <td>
  
  <a class="btn btn-primary" href="{{ url() }}/ALadmin/user/edit/{{ $user->id }}" role="button">Edit</a>
  <a class="btn btn-primary" href="{{ url() }}/ALadmin/user/changepassword/{{ $user->id }}" role="button">Password Changed</a>
  <a class="btn btn-primary" href="{{ url() }}/ALadmin/user/delete/{{ $user->id }}" role="button">Delete</a>
 </td>
</tr>
@endforeach
</table>
</div>
@stop