<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   @include('layouts.partials.head')
   <link rel="stylesheet" href="{{URL::asset('css/custom.css')}}">
</head>
<body class="login hold-transition">
<!-- Site wrapper -->
<div class="wrapper">

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </section>
    <!-- /.content-wrapper -->
</div>
</body>
</html>