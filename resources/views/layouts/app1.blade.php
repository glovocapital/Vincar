<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   @include('layouts.partials.head')
</head>
<body class="hold-transition">
<!-- Site wrapper -->
<div class="wrapper">
        @include('layouts.partials.header')
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