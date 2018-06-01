<html>
<head>
    <title>Routes</title>
</head>
<body>
    <h1>{{ $system[\App\SYSTEM_FULLNAME] }}</h1>
    <a href="{{ route("system.index") }}">Systems</a>
    <ul>
        @foreach($tmroutes as $tmroute)
            <li>
                <a href="{{ route('route.read', $tmroute[\App\ROUTE_PK]) }}">{{ $tmroute->display_name() }}</a>
            </li>
        @endforeach
    </ul>
</body>
</html>