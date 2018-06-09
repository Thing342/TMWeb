<div class="card-column" style="min-width: 350px">
    <div class="card my-2">
        <div class="card-header">
            Route Info
            <button type="button" class="close" data-toggle="collapse" data-target="#routeInfo" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="collapse show" id="routeInfo">
            <div class="card-body">
                <div class="bigshield">@component('shields.shield', ['route' => $route]) @endcomponent</div>
                <h5 class="card-title">{{ $route->display_name() }}</h5>
                <small class="card-text text-muted">.list name: {{ $route->list_name() }}</small>
                <table id="routeInfo" class="table table-sm" style="font-size: small">
                    <tbody>
                    <tr>
                        <td class="important">Length</td>
                        <td>
                            <span class="unit">{{ number_format($route->mileage, 1) }}</span>
                            <span class="units-text">miles</span>
                        </td>
                    </tr>
                    <tr title="@foreach($travelers as $t) {{ $t[\App\TRAVELER_TRAVELER] }} @endforeach">
                        <td>Drivers</td>
                        <td>{{ sizeof($travelers) }} ({{ number_format($travelPct, 2) }} %)</td>
                    </tr>
                    <tr class="link" title="@foreach($clinchers as $t) {{ $t[\App\TRAVELER_TRAVELER] }} @endforeach">
                        <td rowspan="2">Clinched</td>
                        <td>{{ sizeof($clinchers) }} ({{ number_format($clinchedPct, 2) }} %)</td>
                    </tr>
                    <tr class="link" title="@foreach($clinchers as $t) {{ $t[\App\TRAVELER_TRAVELER] }} @endforeach">
                        <td>{{ number_format($clinchedPctOfDriven, 2) }} % of drivers</td>
                    </tr>
                    <tr>
                        <td>Average Distance</td>
                        <td>
                            <span class="unit">{{ number_format($meanDistanceDriven, 2) }}</span>
                            <span class="units-text">miles</span>
                            ({{ number_format($meanDistanceDrivenPct, 2) }} %)
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr>
                <a href="#" class="card-link">View Related Routes</a>
            </div>
        </div>
    </div>

    <div class="card my-2">
        <div class="card-header">
            Waypoints
            <button type="button" class="close" data-toggle="collapse" data-target="#waypoints" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="collapse show" id="waypoints">
            <table id="wptTable" class="table table-sm" style="font-size: small">
                <thead><tr><td>Name</td><td>%</td></tr></thead>
                <tbody>
                    @foreach($route->visibleWaypoints as $waypoint)
                        <tr>
                            <td>{{ $waypoint[\App\WAYPOINT_POINTNAME] }}</td>
                            <td>
                                @if(!$loop->last)
                                    {{ number_format($waypointStats[$waypoint->pointId]['clinchedPct'], 2) }}%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>