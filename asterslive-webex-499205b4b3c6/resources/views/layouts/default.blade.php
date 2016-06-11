<!doctype html>
<html>
<head>
    @include('includes.head')
</head>
<body>
<div class="container-fluid">

    <div class="row">
        @include('includes.header')
    </div>

    <div id="main" class="row">

            @yield('content')

    </div>

    <div class="row">
        @include('includes.footer')
    </div>

</div>
</body>
</html>