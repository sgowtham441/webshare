@extends('layouts.default')
@section('content')
 <div class="col-sm-offset-1 col-sm-10">
  <a class="btn btn-primary" id="urlgenerate" href="{{url() }}/generate" role="button">Url Generate</a><br><br>
 <table class="table table-hover table-bordered">
  <tr class="info">
  <td>Meeteing ID</td>
  <td>URL</td>
  <td>ACTION</td>
</tr>

@foreach ($groupurls as $groupurl)
<tr  class="active">
  <td>{{ $groupurl->metting_id }}</td>
  <td>{{ $groupurl->url }}</td>
  <td>
  <a class="btn btn-primary" href="{{ url() }}/ALadmin/user/edit/{{ $groupurl->id }}" role="button">URL Details</a>
  <a class="btn btn-primary" href="{{ url() }}/ALadmin/user/edit/{{ $groupurl->id }}" role="button">Send Url</a>
 
 </td>
</tr>
@endforeach
</table>
</div>

@stop