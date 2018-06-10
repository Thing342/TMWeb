@extends('app')

@section('title', 'User Leaderboard')

@section('content')
    <h1>User Leaderboard</h1>

    @if($traveler)
    <table class="table my-2" id="#userTable">
        <thead><tr>
            <td>Rank</td>
            <td>Traveler</td>
            <td>Total Active Mileage</td>
            <td>%</td>
            <td>Total Active + Preview Mileage</td>
            <td>%</td>
        </tr></thead>
        <tbody>
            <tr class="traveler current-traveler" data-traveler="{{ $traveler->traveler }}" >
                <td>{{ $trav_rank }}</td>
                <td>{{ $traveler->traveler }}</td>
                <td data-order="{{ $traveler->overallActiveMileage }}">
                    {{ number_format($traveler->overallActiveMileage, 0) }} mi
                </td>
                <td>{{ number_format($traveler->overallActiveMileage / $totals->activeMileage * 100, 2) }}</td>
                <td data-order="{{ $traveler->overallActivePreviewMileage }}">
                    {{ number_format($traveler->overallActivePreviewMileage, 0) }} mi
                </td>
                <td>{{ number_format($traveler->activePreviewMileage / $totals->activePreviewMileage * 100, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <table class="table table-hover my-4" id="leaderboardTable">
        <thead><tr>
            <td>Rank</td>
            <td>Traveler</td>
            <td>Total Active Mileage</td>
            <td>%</td>
            <td>Total Active + Preview Mileage</td>
            <td>%</td>
        </tr></thead>
        <tbody>
        @php($i=1)
        @foreach($travelers as $t)
            <tr class="traveler @if($traveler != null && $traveler->traveler == $t->traveler) current-traveler @endif" data-traveler="{{ $t->traveler }}" >
                <td>{{ $i }}</td>
                <td>{{ $t->traveler }}</td>
                <td data-order="{{ $t->overallActiveMileage }}">
                    {{ number_format($t->overallActiveMileage, 0) }} mi
                </td>
                <td>{{ number_format($t->overallActiveMileage / $totals->activeMileage * 100, 2) }}</td>
                <td data-order="{{ $t->overallActivePreviewMileage }}">
                    {{ number_format($t->overallActivePreviewMileage, 0) }} mi
                </td>
                <td>{{ number_format($t->activePreviewMileage / $totals->activePreviewMileage * 100, 2) }}</td>
            </tr>
            @php($i++)
        @endforeach
        </tbody>
    </table>
@endsection

@section('js')
<script type="application/javascript">
    $(function(){
        console.log("HHHHH");
        $('#leaderboardTable').DataTable({
            paging: false
        });
    });

    $("tr.traveler").on('click', function (e) {
       var traveler = $(this).data('traveler');
       window.location.href = '/users/' + traveler;
    })
</script>
@endsection