<html>
<head>
    <title>Systems</title>
</head>
<body>
    <h1>Systems</h1>
    <ul>
        @foreach($systems as $system)
            <li>
                <a href="{{ route('system.read', $system[\App\SYSTEM_PK]) }}">
                    {{ $system[\App\SYSTEM_FULLNAME] }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>