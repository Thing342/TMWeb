@extends('app')

@section('title', $tmroute->display_name())

@section('content')
    <h1>{{ $tmroute->display_name() }}</h1>
    <a href="{{ route('system.read', $tmsystem[\App\SYSTEM_PK]) }}">
        Back to {{ $tmsystem[\App\SYSTEM_FULLNAME] }}
    </a>
    <br>
    <a href="{{ route('region.read', $tmregion[\App\REGION_PK]) }}">
        Back to {{ $tmregion[\App\REGION_NAME] }}
    </a>
    <br>
    <ul>
        @foreach($tmwaypoints as $tmwaypoint)
            <li>
                {{ $tmwaypoint[\App\WAYPOINT_POINTNAME] }}
            </li>
        @endforeach
    </ul>
@endsection