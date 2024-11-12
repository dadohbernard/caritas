<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        var siteUrl = "{{ url('/') }}";
        var csrf_token = "{{ csrf_token() }}";

    </script>
    <title>{{ isset($title) ? $title . ' - ' : '' }} {{ config('app.name') }}</title>
    {{-- <link rel="shortcut icon" href="https://excellenceindustries.com/wp-content/uploads/2018/10/favicon.ico" type="image/x-icon" /> --}}
    @include('layouts.header')
    @yield('style')
</head>
<body class="hold-transition sidebar-mini layout-fixed text-sm sidebar-collapse">
    <div class="wrapper">
        @include('layouts.navbar')
        @include('layouts.sidebar')
        <div class="content-wrapper" >
            @yield('content')
        </div>
        @include('layouts.footer')
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    @yield('script')
</body>

</html>
