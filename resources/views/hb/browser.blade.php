@extends('app')

@section('title', 'Highway Browser')

@section('content')
    <h1>Highway Browser</h1>

    <form action="{{ route('route.browser') }}">
        @csrf

        <h5>Filter:</h5>

        <div class="form-group">
            <label for="regionSelect">Filter by Region:</label>
            <select class="form-control" id="regionSelect" name="rg">
                <option value="_" selected>All Regions</option>
                @foreach($regions as $region)
                    <option value="{{ $region[\App\REGION_PK] }}">{{ $region[\App\REGION_NAME] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="systemSelect">Filter by System:</label>
            <select class="form-control" id="systemSelect" name="sys">
                <option value="_" selected>All Systems</option>
                @foreach($systems as $system)
                    <option value="{{ $system[\App\SYSTEM_PK] }}">{{ $system[\App\SYSTEM_FULLNAME] }}</option>
                @endforeach
            </select>
        </div>

        <input type="submit">
    </form>

@endsection