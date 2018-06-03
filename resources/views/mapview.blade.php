@extends('fluid')

@section('title', 'Mapview')

@section('container-classes', 'container-fluid myclass')

@section('before-container')
    <div class="my-2 px-2" id="map-main"
         style="overflow: auto; position: fixed; top: 60px; left: 0; right: 0; bottom: 0">
        <div id="map" class="w-100 h-100"></div>
    </div>
@endsection

@section('content')
    <div class="card-column pl-4 pr-1"
         style="overflow: auto; position: fixed; top: 70px; left: 40px; min-width: 400px; max-height: 100%">
        <div class="card my-2">
            <div class="card-header">
                Map Options
                <button type="button" class="close" data-toggle="collapse" data-target="#mapOpts"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="collapse show" id="mapOpts">
                <div class="card-body">
                    <form class="form-inline">
                        @csrf
                        <div class="form-check mb-2 mr-sm-2">
                            <input class="form-check-input" type="checkbox" id="showMarkers" name="showMarkers"
                                   value="false">
                            <label class="form-check-label h-100" for="showMarkersCheck">Show Markers</label>
                        </div>

                        <label for="unitsSelect" class="form-label mx-sm-2">Units:</label>
                        <select name="units" id="unitsSelect" class="form-control mr-sm-2">
                            <option value="mi" selected>Miles</option>
                            <option value="km">Kilometers</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card my-2">
            <div class="card-header">
                Routes
                <button type="button" class="close" data-toggle="collapse" data-target="#routeInfo"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="collapse show" id="routeInfo">
                <div class="card-body p-1" style="font-size: small">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <td>Route</td>
                            <td>System</td>
                            <td>Clinched</td>
                            <td>Overall</td>
                            <td>%</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($routes as $route)
                            @php
                                $isClinched = false;
                                $clinchedMi = 0.0;
                                $clinchedPct = 0.0;
                                if (sizeof($route->travelers) > 0) {
                                    $clinchInfo = $route->travelers[0]->pivot;
                                    $clinchedMi = $clinchInfo->mileage;
                                    $clinchedPct = $clinchInfo->mileage / $route->mileage * 100;
                                    $isClinched = $clinchInfo->clinched == 1;
                                }
                            @endphp
                            <tr class="@if($isClinched) route-clinched @endif route-entry" data-route="{{ $route->root }}">
                                <td>{{ $route->list_name() }}</td>
                                <td>{{ $route->systemName }}</td>
                                <td>
                                    <span class="unit">{{ number_format($clinchedMi, 1) }}</span>
                                    <span class="units-text">mi</span>
                                </td>
                                <td>
                                    <span class="unit">{{ number_format($route->mileage, 1) }}</span>
                                    <span class="units-text">mi</span>
                                </td>
                                <td>{{ number_format($clinchedPct, 1) }} %</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- TODO: eliminate hack -->
    <script type="application/javascript" src="http://tml.teresco.org/lib/waypoints.js.php{{ $apiQueryString }}"></script>
    <script type="application/javascript">
        let units = 'mi';

        $(document).ready(function () {
            loadmap();
            waypointsFromSQL();
            updateMap();
        });

        $('#unitsSelect').on('change', function () {
            let newunits = this.value;
            convertAllUnits(units, newunits);
            units = newunits;
        });

        $('#showMarkers').on('click', function () {
            showMarkersClicked();
        });

        $('tr.route-entry').on('click', function (event) {
            window.location.href = '/routes/' + $(this).data('route');
        })
    </script>
@endsection