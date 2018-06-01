@extends('app')

@section('title', 'Highway Browser')

@section('content')
    <h1>Highway Browser</h1>
    <h4>Regions</h4>

    @component('components.beadcrumbs', ['links'=>[['Highway Browser', route('route.browser')]], 'item'=>'Regions'])
    @endcomponent

    <table class="table table-hover my-4">
        <thead><tr>
            <td>Code</td>
            <td>Name</td>
            <td>Country</td>
            <td>Continent</td>
        </tr></thead>
        <tbody>
            @foreach($regions as $region)
                <tr onclick="window.location.href = '{{route('route.index.region', $region->code)}}'">
                    <td>{{ $region[\App\REGION_CODE] }}</td>
                    <td>{{ $region[\App\REGION_NAME] }}</td>
                    <td>{{ $region[\App\REGION_COUNTRY] }}</td>
                    <td>{{ $region[\App\REGION_CONTINENT] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection