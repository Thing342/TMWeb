import {convertAllUnits, drawPoints, loadmap} from "./app";

import * as $ from 'jquery';
import 'datatables.net';

let units = 'mi';

(async function () {
    let root = $('#map').data('root');
    let req = fetch(`/api/waypoints?r=${root}`);
    let mapState = loadmap('map');

    let resp = await req;
    let data = await resp.json();

    console.log(data);
    mapState = drawPoints(data.routes, mapState, data.mapClinched, data.genEdges);
})();


$('#unitsSelect').on('change', function () {
    let newunits = (this as HTMLInputElement).value;
    convertAllUnits(units, newunits);
    units = newunits;
});