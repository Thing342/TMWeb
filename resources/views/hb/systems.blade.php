@extends('app')

@section('title', 'Highway Browser')

@section('content')
    <h1>Highway Browser</h1>
    <h4>Systems</h4>

    @component('components.beadcrumbs', ['links'=>[['Highway Browser', route('route.browser')]], 'item'=>'Systems'])
    @endcomponent

    <table class="table table-hover my-4">
        <thead><tr>
            <td>Country</td>
            <td>System</td>
            <td>Code</td>
            <td>Status</td>
            <td>Level</td>
        </tr></thead>
        <tbody>
            @foreach($systems as $system)
                <tr onclick="window.location.href = '{{route('route.index.system', $system->systemName)}}'">
                    <td>{{ $system[\App\SYSTEM_COUNTRYCODE] }}</td>
                    <td>{{ $system[\App\SYSTEM_FULLNAME] }}</td>
                    <td>{{ $system[\App\SYSTEM_SYSTEMNAME] }}</td>
                    <td>{{ $system[\App\SYSTEM_LEVEL] }}</td>
                    <td>{{ $system[\App\SYSTEM_TIER] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection