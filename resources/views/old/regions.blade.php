@extends('app')

@section('title', 'Regions')

@section('content')
    <h1>Regions</h1>
    <ul>
        @foreach($regions as $region)
            <li>
                <a href="{{ route('region.read', $region[\App\REGION_PK]) }}">
                    {{ $region[\App\REGION_NAME] }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection