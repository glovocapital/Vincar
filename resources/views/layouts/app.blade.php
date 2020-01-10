<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>

<body>
<div class="wrapper">

    @include('layouts.partials.nav')

    <div class="main">
        @include('layouts.partials.sidebar')

        <main class="content">
            @yield('content')
        </main>

        @include('layouts.partials.footer')

    </div>
</div>



@include('layouts.partials.footer-scripts')

@yield('local-scripts')

</body>


</html>