<!doctype html>
<html lang="en" style="height: 100vh">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('include.css')

    <title>Travel Mapping - @yield('title')</title>
</head>
<body style="height: 100vh">

 @component('components.navbar')
 @endcomponent

 @section('before-container') <!--> @show

 <div class="@section('container-classes') my-2 container-fluid @show" style="height: 75vh">
     @yield('content')
 </div>

 @section('after-container') <!--> @show

@include('include.js')

</body>
</html>