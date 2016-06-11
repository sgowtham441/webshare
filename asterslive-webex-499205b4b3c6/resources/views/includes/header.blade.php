
<div class="col-md-12">
 <nav class="navbar navbar-default">
 @if(Auth::check())
   
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Webex</a>
    </div>
	 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	 @if(Auth::user()->role==1)
      <ul class="nav navbar-nav">
        <li class="active"><a href="{{ url() }}/ALadmin/user">User Management</a></li>
        <li><a href="#">Profile</a></li>
      </ul>
      @else
      <ul class="nav navbar-nav">
        <li class="active"><a href="{{ url() }}/manage">URL Management</a></li>
        <li><a href="#">Profile</a></li>
      </ul>
      @endif
     <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><?php echo Auth::user()->name; ?>e</a></li>
        <li><a href="{{ url() }}/auth/logout">Logout</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
  @endif
 </nav>
</div>
