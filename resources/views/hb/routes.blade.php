@extends('app')

@section('title', 'Highway Browser')

@section('content')
    <h1>Highway Browser</h1>
    <h4>{{ $title }}</h4>
    @component('components.beadcrumbs', ['links'=>$backstack, 'item'=>$title]) @endcomponent

    <table class="table table-hover my-4">
        <thead><tr>
            <td>Tier</td>
            <td>System</td>
            <td>Region</td>
            <td>Route Name</td>
            <td>LIST Name</td>
            <td>Level</td>
            <td>Root</td>
        </tr></thead>
        <tbody>
            @foreach($routes as $route)
                <tr onclick="window.location.href='{{ route('route.read', $route->root) }}'">
                    <td>{{ $route->system[\App\SYSTEM_TIER] }}</td>
                    <td>{{ $route[\App\ROUTE_SYSTEM_NAME] }}</td>
                    <td>{{ $route[\App\ROUTE_REGION] }}</td>
                    <td>{{ $route->display_name() }}</td>
                    <td>{{ $route->list_name() }}</td>
                    <td>{{ $route->system[\App\SYSTEM_LEVEL] }}</td>
                    <td>{{ $route[\App\ROUTE_ROOT] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection