import {convertAllUnits, drawPoints, loadmap} from "./app";

let units = 'mi';

$(document).ready(function () {
    const url = $("#map").data('qs');

    let mapState = loadmap('map');
    $.get(url, function (data) {
        let routes = data.routes.sort((a, b) => b.tier - a.tier);

        console.log(routes);
        mapState = drawPoints(routes, mapState, data.mapClinched, data.genEdges);
    });
});

/*
$('#unitsSelect').on('change', function () {
    let newunits = this.value;
    convertAllUnits(units, newunits);
    units = newunits;
});


$('#showMarkers').on('click', function () {
    showMarkersClicked();
});
*/

$('tr.route-entry').on('click', function (event) {
    window.location.href = '/routes/' + $(this).data('route');
});