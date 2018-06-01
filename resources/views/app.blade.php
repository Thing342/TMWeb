<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('include.css')

    <title>Travel Mapping - @yield('title')</title>
</head>
<body>

 @component('components.navbar')
 @endcomponent

 @section('before-container') @endsection

 <div class="@section('container-classes') my-2 container @show">
     @yield('content')
 </div>

 @section('after-container') @endsection

@include('components.footer')

@include('include.js')
</body>
</html>