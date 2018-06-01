@extends('app')

@section('title', $region[\App\REGION_NAME])

@section('content')
    <h1>{{ $region[\App\REGION_NAME] }}</h1>
    <a href="{{ route("region.index") }}">Back to Regions</a>
    <ul>
        @foreach($tmroutes as $tmroute)
            <li>
                <a href="{{ route('route.read', $tmroute[\App\ROUTE_PK]) }}">{{ $tmroute->display_name() }}</a>
            </li>
        @endforeach
    </ul>
@endsection