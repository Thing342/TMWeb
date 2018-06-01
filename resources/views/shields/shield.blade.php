@php
$shieldTemplate = 'shields.templates.' . $route->systemName;

$system = strtoupper($route[\App\ROUTE_SYSTEM_NAME]);
$region = strtoupper($route[\App\ROUTE_REGION]);
$routeNum = str_replace($region, "", $route[\App\ROUTE_ROUTE])
@endphp

@if(View::exists($shieldTemplate))
    @include($shieldTemplate)
@else
    @include('shields.generic')
@endif