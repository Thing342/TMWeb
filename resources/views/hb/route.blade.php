@extends('fluid')

@section('title', 'Highway Browser')

@section('container-classes', 'container-fluid')

@section('content')
    <div class="row">
        <aside id="map-sidebar" class="px-2" style="overflow: auto; width: 375px; position: fixed; top: 60px; left: 0; bottom: 0">
            @include('hb.sidebar')
        </aside>
        <main class="my-2 px-2" id="map-main"
              style="overflow: auto; position: fixed; top: 60px; left: 390px; right: 0; bottom: 0">

            @component('components.beadcrumbs', ['links'=>$backstack, 'item'=>$route->route]) @endcomponent

            <div class="row mx-2">
                <form class="form-inline">
                    @csrf
                    <div class="form-check mb-2 mr-sm-2">
                        <input class="form-check-input" type="checkbox" id="showMarkers" name="showMarkers"
                               checked="false">
                        <label class="form-check-label h-100" for="showMarkersCheck">Show Markers</label>
                    </div>

                    <label for="unitsSelect" class="form-label mr-sm-2">Units:</label>
                    <select name="units" id="unitsSelect" class="form-control mr-sm-2">
                        <option value="mi" selected>Miles</option>
                        <option value="km">Kilometers</option>
                    </select>
                </form>
            </div>

            <div class="row">
                <div id="map"
                     style="overflow: auto; position: fixed; top: 180px; left: 390px; right: 0; bottom: 0"></div>
            </div>
        </main>
    </div>
@endsection

@section('js')
    <!-- TODO: eliminate hack -->
    <script type="application/javascript" src="http://tml.teresco.org/lib/waypoints.js.php?r={{ $route->root }}"></script>
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