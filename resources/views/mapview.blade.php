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
    <div class="card-column px-4"
         style="overflow: auto; position: fixed; top: 70px; left: 40px; width: 300px; max-height: 100%">
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
                    <span>{{ $traveler->traveler }}</span>
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
                <div class="card-body">
dfsfdsffsd
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- TODO: eliminate hack -->
    <script type="application/javascript" src="http://tml.teresco.org/lib/waypoints.js.php?rg=DE&u={{ $traveler->traveler }}"></script>
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
    </script>
@endsection