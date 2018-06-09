import * as bootstrap from 'bootstrap'
import * as leaflet from 'leaflet'
import * as $ from 'jquery'
import {LatLngExpression, marker} from "leaflet";
import htmlString = JQuery.htmlString;


(function () {
    const select = $('#userSelect');
    const selectedUser = select.data('selected-user'); // Should be null if not used
    populateUserSelect(select, selectedUser);
})();


const intersectionimage = new leaflet.Icon({
    iconUrl: '/img/Intersection.png',
    // This marker is 16x16
    iconSize: [16, 16]
});


type IdMap<T> = { [id: number]: T }

export interface RouteInfo {
    root: string,
    route: string,
    region: string,
    systemName: string,
    banner: string,
    abbrev: string,
    city: string;
}

export interface Waypoint {
    pointId: number,
    label: string,
    lat: number,
    lon: number,
    elabel: string,
    edgeList: any[],
    intersecting: RouteInfo[]
}

export interface Segment {
    segmentId: number,
    fromID: number,
    toID: number,
    clinched: boolean
}

export interface Route {
    info: RouteInfo,
    waypoints: IdMap<Waypoint>,
    segments: Segment[],
    tier: number,
    color: string
}

export interface Traveler {
    traveler: string,
    overallActiveMileage: number,
    overallActivePreviewMileage: number
}

export interface MapState {
    map: leaflet.Map;
    markers: leaflet.Marker[];
    connections: leaflet.Polyline[];
    bounds: { ne: { lat: number, lon: number }, sw: { lat: number, lon: number } };
    labelClickCallback: (wpt: Waypoint) => any
}


export function loadmap(mapId: string): MapState {
    const map = new leaflet.Map(mapId, {renderer: leaflet.canvas()});
    map.setView([0, 0], 16);

    const osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    const osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    const osmLayer = new leaflet.TileLayer(osmUrl, {minZoom: 3, maxZoom: 18, attribution: osmAttrib});

    map.addLayer(osmLayer);
    // TODO: more layers

    return newMapState(map);
}

export function drawPoints(routes: Route[], oldState: MapState, mapClinched: boolean, genEdges: boolean): MapState {
    const newState: MapState = newMapState(oldState.map);
    oldState.connections.forEach((it) => it.remove());
    oldState.markers.forEach((it) => it.remove());

    let showHidden = false;
    let showMarkers = routes.length <= 1;

    const points: LatLngExpression[] = [];

    routes.forEach((route) => {
        for (const wid in route.waypoints) {
            const wpt: Waypoint = route.waypoints[wid];
            newState.bounds.sw.lat = Math.min(newState.bounds.sw.lat, wpt.lat);
            newState.bounds.sw.lon = Math.min(newState.bounds.sw.lon, wpt.lon);
            newState.bounds.ne.lat = Math.max(newState.bounds.ne.lat, wpt.lat);
            newState.bounds.ne.lon = Math.max(newState.bounds.ne.lon, wpt.lon);

            points.push([wpt.lat, wpt.lon]);

            const marker = new leaflet.Marker([wpt.lat, wpt.lon], {
                title: wpt.label, icon: intersectionimage
            });
            newState.markers.push(marker);

            if (showMarkers && (showHidden || waypointIsVisible(wpt))) {
                const markerInfo: string = getMarkerInfo(wpt);
                marker.addTo(newState.map);
                marker.bindPopup(markerInfo);
                //marker.on('click', () => newState.labelClickCallback(wpt))
            }
        }
    });

    newState.map.fitBounds([[newState.bounds.sw.lat, newState.bounds.sw.lon],
        [newState.bounds.ne.lat, newState.bounds.ne.lon]]);

    if (mapClinched) {
        if (routes.length == 1) {
            const route: Route = routes[0];
            route.segments.forEach((segment) => {
                const wptFrom = route.waypoints[segment.fromID];
                const wptTo = route.waypoints[segment.toID];
                const edgePoints = [new leaflet.LatLng(wptFrom.lat, wptFrom.lon), new leaflet.LatLng(wptTo.lat, wptTo.lon)];
                const color = (segment.clinched) ? "#ff8080" : "#cccccc";

                const connection = new leaflet.Polyline(edgePoints, {
                    color: color, weight: 10, opacity: 0.75
                });
                newState.connections.push(connection);
                connection.addTo(newState.map);
            });
        } else {
            routes.forEach((route) => {
                const color = route.color;

                route.segments.forEach((segment) => {
                    const wptFrom = route.waypoints[segment.fromID];
                    const wptTo = route.waypoints[segment.toID];
                    const edgePoints = [new leaflet.LatLng(wptFrom.lat, wptFrom.lon), new leaflet.LatLng(wptTo.lat, wptTo.lon)];
                    const opacity = (segment.clinched) ? 0.8 : 0.3;
                    const weight = (segment.clinched) ? 4 : 2;

                    const connection = new leaflet.Polyline(edgePoints, {
                        color: color, weight: weight, opacity: opacity
                    });
                    newState.connections.push(connection);
                    connection.addTo(newState.map);
                });
            });
        }
    } else if (genEdges) {
        newState.connections = [new leaflet.Polyline(points, {color: "#0000FF", weight: 10, opacity: 0.75})];
        newState.connections[0].addTo(newState.map);
        newState.connections[0].on('click', () => edgeClick(0));
    }

    return newState;
}

export function convertAllUnits(fromUnit, toUnit) {
    $('.unit').each(function () {
        let it = $(this);
        let measurement = parseFloat(it.text());
        let conversion = convertUnit(measurement, fromUnit, toUnit);
        it.text(conversion.toFixed(2));
    });

    $('.units-text').each(function () {
        let it = $(this);
        it.text(toUnit);
    });
}


function newMapState(map: leaflet.Map): MapState {
    const bounds = {ne: {lat: -999, lon: -999}, sw: {lat: 999, lon: 999}};
    return {map: map, connections: [], markers: [], bounds: bounds, labelClickCallback: () => null};
}

function waypointIsVisible(w: Waypoint) {
    return w.label[0] != '+';
}

function getMarkerInfo(wpt: Waypoint): htmlString {
    let intersections = "";
    if(wpt.intersecting.length > 0) {
        intersections += "<p>Intersecting/Concurrent Routes:<br />";
        wpt.intersecting.forEach((it) => {
            intersections += `<a href="/routes/${it.root}"> ${it.region} ${it.route} ${it.banner}`;
            if(it.city != "") {
                intersections += `(${it.city})`;
            }
            intersections += "</a><br>";
        });
        intersections += "</p>";
    }
    return `
<p style="line-height: 160%">
    <span style="font-size: 24pt; color: black">${wpt.label}</span><br>
    <b>Waypoint ID: ${wpt.pointId}</b><br>
    <b><a target="_blank" href="https://www.openstreetmap/?lat=${wpt.lat}&lon=${wpt.lon}">Coords.:</a></b> ${wpt.lat}&deg; ${wpt.lon}&deg;
</p>
${intersections}
`
}

function edgeClick(number: number) {
    // TODO
}

function convertUnit(measurement, fromUnit, toUnit) {
    if (fromUnit === toUnit) return measurement;

    if (fromUnit === 'mi' && toUnit === 'km') {
        return measurement * 1.60934
    }

    if (fromUnit === 'km' && toUnit === 'mi') {
        return measurement / 1.60934
    }
}

async function populateUserSelect(select: JQuery<HTMLElement>, selectedUser?: string) : Promise<Traveler[]> {
    console.log(selectedUser);

    const resp = await fetch('/api/travelers');
    const data: Traveler[] = await resp.json();

    select.html("<option value=\"\" selected>Select User</option>" + data.map((it) => {
        const selectedStr = (it.traveler == selectedUser) ? "selected" : "";
        return `<option value='${it.traveler}' ${selectedStr}>${it.traveler}</option>`
    }).join(""));

    return data;
}


