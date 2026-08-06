@extends('layouts.master')

@section('title','Embassiees')
@section('page-title', 'Indonesia Embassiees')

@push('styles')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />
<style>
    #map {
        height: 700px;
    }
    .filter-container {
        margin-bottom: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }
    .form-check-scrollable {
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
    }
    .total-embassy {
        background: white;
        padding: 8px 12px;
        border-radius: 8px;
        box-shadow: 0 0 6px rgba(0,0,0,0.2);
        font-weight: bold;
    }

     .select2-container .select2-selection--single {
        height: 45px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px;
        right: 10px;
    }

       .btn-danger{
            background-color:#395272;
            border-color: transparent;
        }

        .btn-danger:hover{
            background-color:#5686c3;
            border-color: transparent;
        }

        .btn.active {
            background-color: #5686c3 !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .p-3{
            padding: 10px !important;
            margin: 0 3px;
        }

        .btn-outline-danger{
            color: #FFFFFF;
            background-color:#395272;
            border-color: transparent;
        }

        .btn-outline-danger:hover{
            background-color:#5686c3;
            border-color: transparent;
        }

        .fa,
        .fab,
        .fad,
        .fal,
        .far,
        .fas {
            color: #346abb;
        }

        .card-header{
            padding: 0.25rem 1.25rem;
            color: #3c66b5;
            font-weight: bold;
        }

        .mb-4{
            margin-bottom: 0.5rem !important;
        }

        .filter-box {
            position: relative;
            width: 100%;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .select-input {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 8px 10px;
            background: #fff;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .select-input input {
            border: none;
            width: 100%;
            cursor: pointer;
            background: transparent;
            outline: none;
        }

        .select-dropdown {
            display: none;
            position: absolute;
            width: 100%;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 3px;
            z-index: 9999;
            max-height: 250px;
            overflow: hidden;
        }

        .select-dropdown.show {
            display: block;
        }

        .dropdown-search {
            width: 100%;
            border: none;
            border-bottom: 1px solid #ddd;
            padding: 8px;
            outline: none;
        }

        #provinceList {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 180px;
            overflow-y: auto;
        }

        #provinceList li {
            padding: 5px 10px;
        }

        #provinceList li:hover {
            background: #f5f5f5;
        }

        #provinceList label {
            width: 100%;
            margin: 0;
            cursor: pointer;
        }

        /* ===== Google Places Autocomplete Fix ===== */
        .pac-container {
            z-index: 99999 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2) !important;
            font-family: inherit !important;
            margin-top: 2px !important;
            border: 1px solid #ddd !important;
        }

        .pac-item {
            padding: 6px 12px !important;
            cursor: pointer !important;
            font-size: 13px !important;
            border-top: 1px solid #f0f0f0 !important;
        }

        .pac-item:hover {
            background: #f0f6ff !important;
        }

        .pac-item-query {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #333 !important;
        }

        .pac-matched {
            color: #1a73e8 !important;
            font-weight: 700 !important;
        }

        #locationSearchMap:focus {
            outline: none !important;
            border-color: #1a73e8 !important;
            box-shadow: 0 0 0 2px rgba(26,115,232,0.2) !important;
        }

        /* ===== Map Type Control (Google style: Map | Satellite) ===== */
        .gmap-type-wrap {
            border: none !important;
            background: transparent;
            font-family: Roboto, Arial, sans-serif;
            position: relative;
        }

        .gmap-type-bar {
            display: flex;
            background: #fff;
            border-radius: 2px;
            box-shadow: 0 1px 4px -1px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .gmap-type-btn {
            border: none;
            background: #fff;
            color: #565656;
            font-size: 18px;
            font-weight: 400;
            height: 40px;
            padding: 0 17px;
            cursor: pointer;
            white-space: nowrap;
        }

        .gmap-type-btn:hover {
            background: #ebebeb;
        }

        .gmap-type-btn.active {
            color: #000;
            font-weight: 500;
        }

        .gmap-type-sub {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 2px;
            background: #fff;
            border-radius: 2px;
            box-shadow: 0 1px 4px -1px rgba(0,0,0,0.3);
            padding: 8px 14px;
            font-size: 15px;
            color: #000;
            white-space: nowrap;
        }

        .gmap-type-wrap:hover .gmap-type-sub {
            display: block;
        }

        .gmap-type-opt {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            cursor: pointer;
            font-weight: 400;
        }

        .gmap-type-opt input {
            cursor: pointer;
            margin: 0;
        }

        /* ===== Fullscreen control (Google style, kanan atas) ===== */
        .leaflet-control-zoom-fullscreen {
            width: 40px !important;
            height: 40px !important;
            background-color: #fff !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 18 18'%3E%3Cpath fill='%23666' d='M0 0h7v2H2v5H0V0zm11 0h7v7h-2V2h-5V0zM2 11v5h5v2H0v-7h2zm14 0h2v7h-7v-2h5v-5z'/%3E%3C/svg%3E") !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-size: 18px 18px !important;
            border-radius: 2px !important;
            box-shadow: 0 1px 4px -1px rgba(0,0,0,0.3) !important;
            border: none !important;
        }

        .leaflet-fullscreen-on .leaflet-control-zoom-fullscreen {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 18 18'%3E%3Cpath fill='%23666' d='M5 0h2v7H0V5h5V0zm6 0h2v5h5v2h-7V0zM0 11h7v7H5v-5H0v-2zm11 0h7v2h-5v5h-2v-7z'/%3E%3C/svg%3E") !important;
        }

        .gmap-fullscreen {
            border: none !important;
            background: transparent !important;
            margin-top: 10px !important;
            margin-right: 10px !important;
        }

        /* ===== Camera / Pan Control (Google style, kanan bawah) ===== */
        .gmap-camera-wrap {
            border: none !important;
            background: transparent !important;
            margin: 0 10px 10px 0 !important;
        }

        .gmap-camera-toggle,
        .gmap-camera-pad {
            background: #fff;
            border: none;
            border-radius: 50%;
            box-shadow: 0 1px 4px -1px rgba(0,0,0,0.3);
            padding: 0;
        }

        .gmap-camera-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #666;
        }

        .gmap-camera-toggle:hover {
            background: #f1f1f1;
            color: #000;
        }

        .gmap-camera-pad {
            display: none;
            position: relative;
            width: 96px;
            height: 96px;
        }

        .gmap-camera-pad.show {
            display: block;
        }

        .gmap-camera-pad button {
            position: absolute;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            border-radius: 50%;
            color: #666;
            cursor: pointer;
            padding: 0;
        }

        .gmap-camera-pad button:hover {
            background: #f1f1f1;
            color: #000;
        }

        /* ===== Custom top-center control corner (Nearby bar & Clear Route) ===== */
        .leaflet-top.leaflet-center {
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }

        .leaflet-top.leaflet-center .leaflet-control {
            pointer-events: auto;
            margin-left: 0;
            margin-right: 0;
        }

        .gmap-camera-pad [data-dir="up"]    { top: 3px;  left: 33px; }
        .gmap-camera-pad [data-dir="down"]  { bottom: 3px; left: 33px; }
        .gmap-camera-pad [data-dir="left"]  { left: 3px;  top: 33px; }
        .gmap-camera-pad [data-dir="right"] { right: 3px; top: 33px; }
        .gmap-camera-pad [data-dir="close"] { top: 33px;  left: 33px; }
</style>

@endpush

@section('conten')

<div class="card">

    <div class="d-flex justify-content-end p-3" style="background-color: #dfeaf1;">

        <div class="d-flex gap-2 mt-2">

            <a href="{{ url('home') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill fs-3"></i>
                <small>Home</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
             <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

             <a href="{{ url('police') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('police') ? 'active' : '' }}">
                <i class="bi bi-person-badge" style="width: 24px; height: 24px;"></i>
                <small>Police</small>
            </a>

            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                <small>Embassies</small>
            </a>

        </div>
    </div>

    <div style="position:relative;">

    <div id="map"></div>

    <!-- Route Detail Panel -->
    <div id="routePanel" style="
        display:none;
        position:absolute;
        top:10px;
        left:10px;
        width:300px;
        max-height:calc(100% - 20px);
        background:#fff;
        border-radius:10px;
        box-shadow:0 4px 20px rgba(0,0,0,0.18);
        z-index:999;
        flex-direction:column;
        overflow:hidden;
        font-family:inherit;
    ">
        <!-- Header -->
        <div style="background:#1a73e8;padding:12px 14px;color:#fff;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;">
            <div>
                <div style="font-size:11px;opacity:0.85;letter-spacing:0.5px;">DRIVING DIRECTIONS</div>
                <div id="routePanelTitle" style="font-size:13px;font-weight:600;margin-top:2px;">—</div>
            </div>
            <button onclick="closeRoutePanel()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:26px;height:26px;border-radius:50%;cursor:pointer;font-size:15px;line-height:1;display:flex;align-items:center;justify-content:center;">&times;</button>
        </div>
        <!-- Summary -->
        <div id="routeSummary" style="padding:10px 14px;background:#f0f4ff;border-bottom:1px solid #dde8ff;display:flex;gap:16px;flex-shrink:0;">
            <div style="text-align:center;">
                <div style="font-size:18px;font-weight:700;color:#1a73e8;" id="routeDistance">—</div>
                <div style="font-size:10px;color:#666;text-transform:uppercase;letter-spacing:0.4px;">Distance</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:18px;font-weight:700;color:#395272;" id="routeDuration">—</div>
                <div style="font-size:10px;color:#666;text-transform:uppercase;letter-spacing:0.4px;">Est. Time</div>
            </div>
        </div>
        <!-- Steps -->
        <div id="routeSteps" style="overflow-y:auto;flex:1;padding:8px 0;"></div>
    </div>

    </div>
</div>


@endsection

@push('service')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry,drawing"></script>
<script src="https://unpkg.com/leaflet.gridlayer.googlemutant@0.14.1/dist/Leaflet.GoogleMutant.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// === Inisialisasi Peta ===
// zoomControl & attributionControl dimatikan: kontrol + atribusi disediakan basemap Google
const map = L.map('map', {
    zoomControl: false,
    attributionControl: false
}).setView([15.561656906765931, 100.85374832882776], 6);

// === Basemap: Google (roadmap / terrain / satellite / hybrid) ===
const baseLayers = {
    roadmap:   L.gridLayer.googleMutant({ type: 'roadmap',   maxZoom: 21 }),
    terrain:   L.gridLayer.googleMutant({ type: 'terrain',   maxZoom: 21 }),
    satellite: L.gridLayer.googleMutant({ type: 'satellite', maxZoom: 21 }),
    hybrid:    L.gridLayer.googleMutant({ type: 'hybrid',    maxZoom: 21 })
};

let currentBaseLayer = baseLayers.roadmap.addTo(map);

function setBasemap(type) {
    if (currentBaseLayer === baseLayers[type]) return;
    if (currentBaseLayer) map.removeLayer(currentBaseLayer);
    currentBaseLayer = baseLayers[type].addTo(map);
    if (currentBaseLayer.bringToBack) currentBaseLayer.bringToBack();
}

// === Map Type Control (Google style: Map | Satellite) ===
const MapTypeControl = L.Control.extend({
    options: { position: 'topleft' },
    onAdd: function () {
        const wrap = L.DomUtil.create('div', 'gmap-type-wrap');

        wrap.innerHTML = `
            <div class="gmap-type-bar">
                <button type="button" class="gmap-type-btn active" data-mode="map">Map</button>
                <button type="button" class="gmap-type-btn" data-mode="satellite">Satellite</button>
            </div>
            <div class="gmap-type-sub">
                <label class="gmap-type-opt" data-opt="terrain">
                    <input type="checkbox" id="mapTypeTerrain"> Terrain
                </label>
                <label class="gmap-type-opt" data-opt="labels" style="display:none;">
                    <input type="checkbox" id="mapTypeLabels" checked> Labels
                </label>
            </div>`;

        const btns       = wrap.querySelectorAll('.gmap-type-btn');
        const optTerrain = wrap.querySelector('[data-opt="terrain"]');
        const optLabels  = wrap.querySelector('[data-opt="labels"]');
        const cbTerrain  = wrap.querySelector('#mapTypeTerrain');
        const cbLabels   = wrap.querySelector('#mapTypeLabels');

        let mode = 'map'; // 'map' | 'satellite'

        function apply() {
            if (mode === 'map') {
                optTerrain.style.display = '';
                optLabels.style.display  = 'none';
                setBasemap(cbTerrain.checked ? 'terrain' : 'roadmap');
            } else {
                optTerrain.style.display = 'none';
                optLabels.style.display  = '';
                setBasemap(cbLabels.checked ? 'hybrid' : 'satellite');
            }
            btns.forEach(b => b.classList.toggle('active', b.dataset.mode === mode));
        }

        btns.forEach(b => b.addEventListener('click', () => {
            mode = b.dataset.mode;
            apply();
        }));
        cbTerrain.addEventListener('change', apply);
        cbLabels.addEventListener('change', apply);

        L.DomEvent.disableClickPropagation(wrap);
        L.DomEvent.disableScrollPropagation(wrap);
        return wrap;
    }
});
map.addControl(new MapTypeControl());

// Fullscreen di kanan atas (default Google), di atas panel Filter & Radius
const fullscreenControl = L.control.fullscreen({ position: 'topright' });
map.addControl(fullscreenControl);
L.DomUtil.addClass(fullscreenControl.getContainer(), 'gmap-fullscreen');

// === Camera / Pan Control (Google style, kanan bawah) ===
const PAN_ICON = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 2 15 6H9l3-4zm0 20-3-4h6l-3 4zM2 12l4-3v6l-4-3zm20 0-4 3V9l4 3z"/>
    <circle cx="12" cy="12" r="1.8"/>
</svg>`;

const ARROWS = {
    up:    `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>`,
    down:  `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>`,
    left:  `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6 12l8-6v12z"/></svg>`,
    right: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 12l-8 6V6z"/></svg>`,
    close: `<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>`
};

const CameraControl = L.Control.extend({
    options: { position: 'bottomright' },
    onAdd: function (map) {
        const wrap = L.DomUtil.create('div', 'gmap-camera-wrap');

        wrap.innerHTML = `
            <button type="button" class="gmap-camera-toggle" title="Map camera controls">${PAN_ICON}</button>
            <div class="gmap-camera-pad">
                <button type="button" data-dir="up"    title="Pan up">${ARROWS.up}</button>
                <button type="button" data-dir="left"  title="Pan left">${ARROWS.left}</button>
                <button type="button" data-dir="right" title="Pan right">${ARROWS.right}</button>
                <button type="button" data-dir="down"  title="Pan down">${ARROWS.down}</button>
                <button type="button" data-dir="close" title="Close">${ARROWS.close}</button>
            </div>`;

        const toggle = wrap.querySelector('.gmap-camera-toggle');
        const pad    = wrap.querySelector('.gmap-camera-pad');
        const PAN_STEP = 120; // px

        toggle.addEventListener('click', () => {
            pad.classList.add('show');
            toggle.style.display = 'none';
        });

        pad.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                switch (btn.dataset.dir) {
                    case 'up':    map.panBy([0, -PAN_STEP]); break;
                    case 'down':  map.panBy([0,  PAN_STEP]); break;
                    case 'left':  map.panBy([-PAN_STEP, 0]); break;
                    case 'right': map.panBy([ PAN_STEP, 0]); break;
                    case 'close':
                        pad.classList.remove('show');
                        toggle.style.display = '';
                        break;
                }
            });
        });

        L.DomEvent.disableClickPropagation(wrap);
        L.DomEvent.disableScrollPropagation(wrap);
        return wrap;
    }
});
map.addControl(new CameraControl());

// === Custom "topcenter" control corner ===
map._controlCorners['topcenter'] = L.DomUtil.create(
    'div', 'leaflet-top leaflet-center', map._controlContainer
);

// === Variabel Global ===
let embassyMarkers = L.featureGroup().addTo(map);
let radiusCircle = null;
let radiusPinMarker = null;
let lastClickedLocation = null;
let destinationMarker = null;
let destinationCoordinates = null;
let drawnPolygonGeoJSON = null;

// === Directions (in-map routing) ===
const directionsService = new google.maps.DirectionsService();
const routeLayer = L.featureGroup().addTo(map);

function clearRouteLayer() {
    routeLayer.clearLayers();
}

// "Clear Route" button
const clearRouteBtn = document.createElement('div');
clearRouteBtn.id = 'clearRouteBtn';
clearRouteBtn.innerHTML = '✕ Clear Route';
Object.assign(clearRouteBtn.style, {
    display: 'none',
    background: '#fff',
    border: '2px solid rgba(0,0,0,0.2)',
    borderRadius: '6px',
    padding: '6px 12px',
    fontSize: '13px',
    fontWeight: '600',
    cursor: 'pointer',
    margin: '10px',
    color: '#d32f2f',
    boxShadow: '0 2px 6px rgba(0,0,0,0.15)'
});
clearRouteBtn.title = 'Clear the current route';
clearRouteBtn.addEventListener('click', () => {
    closeRoutePanel();
});

const ClearRouteControl = L.Control.extend({
    options: { position: 'topcenter' },
    onAdd: function () {
        L.DomEvent.disableClickPropagation(clearRouteBtn);
        return clearRouteBtn;
    }
});
map.addControl(new ClearRouteControl());

// Helper: close route panel
function closeRoutePanel() {
    const panel = document.getElementById('routePanel');
    if (panel) panel.style.display = 'none';
    clearRouteLayer();
    clearRouteBtn.style.display = 'none';
}

// Helper: draw route on map + show panel
function showRouteOnMap(originLat, originLng, destLat, destLng, destName) {
    directionsService.route({
        origin: new google.maps.LatLng(originLat, originLng),
        destination: new google.maps.LatLng(destLat, destLng),
        travelMode: google.maps.TravelMode.DRIVING
    }, (result, status) => {
        if (status === 'OK') {
            clearRouteLayer();
            map.closePopup();

            const route = result.routes[0];
            const leg   = route.legs[0];

            const path = (route.overview_path || []).map(p => [p.lat(), p.lng()]);
            if (path.length) {
                L.polyline(path, { color: '#1a73e8', weight: 5, opacity: 0.85 }).addTo(routeLayer);
            }
            L.marker([leg.start_location.lat(), leg.start_location.lng()])
                .bindPopup('Start').addTo(routeLayer);
            L.marker([leg.end_location.lat(), leg.end_location.lng()])
                .bindPopup(destName || 'Destination').addTo(routeLayer);

            if (routeLayer.getLayers().length) {
                map.fitBounds(routeLayer.getBounds(), { padding: [40, 40] });
            }

            clearRouteBtn.style.display = 'inline-block';

            const panel = document.getElementById('routePanel');
            document.getElementById('routePanelTitle').textContent = destName || 'Destination';
            document.getElementById('routeDistance').textContent  = leg.distance.text;
            document.getElementById('routeDuration').textContent  = leg.duration.text;

            const stepsEl = document.getElementById('routeSteps');
            stepsEl.innerHTML = leg.steps.map((step, i) => {
                const raw = (step.html_instructions || step.instructions || '');
                const instruction = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                if (!instruction) return '';
                const icons = {
                    'Turn left':        '↰',
                    'Turn right':       '↱',
                    'Keep left':        '↖',
                    'Keep right':       '↗',
                    'Continue':         '↑',
                    'Head':             '↑',
                    'Roundabout':       '↻',
                    'U-turn':           '⟳',
                    'Merge':            '↑',
                    'Ramp':             '↗',
                    'Destination':      '📍',
                };
                let icon = '•';
                for (const [key, val] of Object.entries(icons)) {
                    if (instruction.startsWith(key)) { icon = val; break; }
                }
                const isLast = i === leg.steps.length - 1;
                return `
                    <div style="display:flex;gap:10px;padding:8px 14px;
                                border-bottom:${isLast ? 'none' : '1px solid #f0f0f0'};
                                align-items:flex-start;">
                        <div style="min-width:22px;height:22px;background:${isLast ? '#395272' : '#e8f0fe'};
                                    border-radius:50%;display:flex;align-items:center;
                                    justify-content:center;font-size:12px;
                                    color:${isLast ? '#fff' : '#1a73e8'};flex-shrink:0;margin-top:1px;">
                            ${icon}
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:12px;color:#222;line-height:1.4;">${instruction}</div>
                            <div style="font-size:11px;color:#888;margin-top:2px;">${step.distance.text}</div>
                        </div>
                    </div>`;
            }).join('');

            panel.style.display = 'flex';
        } else {
            if (status === 'ZERO_RESULTS') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Route Not Found',
                    text: 'No driving route could be found between your location and the destination. The two locations may not be connected by road.',
                    confirmButtonColor: '#1a73e8',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Directions Error',
                    text: 'Could not get directions: ' + status,
                    confirmButtonColor: '#1a73e8',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}

// --- Nearby Category Bar (Google Maps style) — Hotels only ---
let categoryMarkers   = [];
let activeCategoryBtn = null;

const categoryBar = document.createElement('div');
categoryBar.id = 'nearbyCategBar';
Object.assign(categoryBar.style, {
    display:       'none',
    background:    'transparent',
    padding:       '8px 10px 0',
    gap:           '8px',
    flexWrap:      'nowrap',
    overflowX:     'auto',
    maxWidth:      '90vw',
    scrollbarWidth:'none'
});

const nearbyCategories = [
    { label: 'Hotels', icon: '🏨', type: 'lodging' }
];

nearbyCategories.forEach(cat => {
    const btn = document.createElement('button');
    btn.textContent = cat.icon + ' ' + cat.label;
    Object.assign(btn.style, {
        display:      'inline-flex',
        alignItems:   'center',
        gap:          '4px',
        padding:      '6px 14px',
        borderRadius: '20px',
        border:       '1px solid rgba(0,0,0,0.12)',
        background:   '#fff',
        color:        '#222',
        fontSize:     '13px',
        fontWeight:   '500',
        cursor:       'pointer',
        whiteSpace:   'nowrap',
        boxShadow:    '0 1px 4px rgba(0,0,0,0.15)',
        transition:   'all 0.15s'
    });

    btn.addEventListener('click', () => {
        if (activeCategoryBtn === btn) {
            clearCategoryMarkers();
            resetCategoryBtn(btn);
            activeCategoryBtn = null;
            return;
        }
        if (activeCategoryBtn) resetCategoryBtn(activeCategoryBtn);
        activeCategoryBtn = btn;
        btn.style.background = '#1a73e8';
        btn.style.color      = '#fff';
        btn.style.borderColor= '#1a73e8';
        showNearbyCategory(cat.type, cat.label);
    });

    categoryBar.appendChild(btn);
});

const CategoryBarControl = L.Control.extend({
    options: { position: 'topcenter' },
    onAdd: function () {
        L.DomEvent.disableClickPropagation(categoryBar);
        L.DomEvent.disableScrollPropagation(categoryBar);
        return categoryBar;
    }
});
map.addControl(new CategoryBarControl());

function resetCategoryBtn(btn) {
    btn.style.background  = '#fff';
    btn.style.color       = '#222';
    btn.style.borderColor = 'rgba(0,0,0,0.12)';
}

function clearCategoryMarkers() {
    categoryMarkers.forEach(m => map.removeLayer(m));
    categoryMarkers = [];
}

function showNearbyCategory(type, label) {
    if (!lastClickedLocation) return;
    clearCategoryMarkers();

    const center  = new google.maps.LatLng(lastClickedLocation.lat, lastClickedLocation.lng);
    const service = new google.maps.places.PlacesService(document.createElement('div'));

    const iconColors = { lodging: '#1a73e8' };
    const color = iconColors[type] || '#555';

    function makeSvgIcon(col) {
        const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='32' height='40' viewBox='0 0 32 40'>`
                  + `<path d='M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24S32 28 32 16C32 7.16 24.84 0 16 0z' fill='${col}'/>`
                  + `<circle cx='16' cy='16' r='7' fill='#fff'/>`
                  + `</svg>`;
        return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
    }

    const placeIcon = L.icon({
        iconUrl: makeSvgIcon(color),
        iconSize: [32, 40],
        iconAnchor: [16, 40],
        popupAnchor: [0, -36]
    });

    service.nearbySearch({ location: center, radius: 5000, type }, (results, status) => {
        if (status !== google.maps.places.PlacesServiceStatus.OK) {
            if (status === 'ZERO_RESULTS') {
                alert(`No ${label.toLowerCase()} found within 5 km.`);
            } else {
                alert(`Failed to load ${label.toLowerCase()}. Error status: ${status}. Please ensure "Places API" is enabled and billing is active.`);
                console.error('PlacesService nearbySearch failed with status:', status);
            }
            return;
        }
        if (!results.length) return;

        results.forEach(place => {
            if (!place.geometry?.location) return;

            const destLat = place.geometry.location.lat();
            const destLng = place.geometry.location.lng();

            const marker = L.marker([destLat, destLng], {
                icon: placeIcon,
                title: place.name
            }).addTo(map);

            const dist     = google.maps.geometry.spherical.computeDistanceBetween(center, place.geometry.location);
            const distText = dist >= 1000 ? (dist / 1000).toFixed(1) + ' km' : Math.round(dist) + ' m';
            const rating   = place.rating ? `⭐ ${place.rating.toFixed(1)}` : '';
            const safeName = (place.name || '').replace(/'/g, "\\'");

            marker.bindPopup(`
                <div style="font-size:13px;min-width:190px;">
                    <h5 style="border-bottom:1px solid #ccc;margin:0 0 6px;font-size:14px;">${place.name}</h5>
                    <div style="color:#666;font-size:12px;margin-bottom:3px;">${label}</div>
                    ${rating  ? `<div style="font-size:12px;">${rating}</div>` : ''}
                    <div style="margin-top:4px;font-size:12px;color:#555;"> ${distText} from search location</div>
                    <div style="margin-top:8px;">
                        <button onclick="showRouteOnMap(${center.lat()},${center.lng()},${destLat},${destLng},'${safeName}')"
                                style="display:inline-flex;align-items:center;gap:5px;
                                       background:#1a73e8;color:#fff;border:none;
                                       padding:5px 12px;border-radius:6px;font-size:12px;
                                       font-weight:500;cursor:pointer;">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                            </svg>
                            Get Directions
                        </button>
                    </div>
                </div>`);

            categoryMarkers.push(marker);
        });
    });
}

// === Leaflet Draw (polygon) ===
const drawnItems = new L.FeatureGroup().addTo(map);

const drawControl = new L.Control.Draw({
    draw: {
        polygon: true,
        polyline: false,
        rectangle: false,
        circle: false,
        marker: false,
        circlemarker: false
    },
    edit: {
        featureGroup: drawnItems,
        remove: true
    }
});
map.addControl(drawControl);

map.on(L.Draw.Event.CREATED, function (event) {
    const layer = event.layer;
    drawnItems.clearLayers();
    drawnItems.addLayer(layer);
    drawnPolygonGeoJSON = layer.toGeoJSON();
    applyFilters();
});

map.on(L.Draw.Event.EDITED, function (event) {
    event.layers.eachLayer(function (layer) {
        drawnPolygonGeoJSON = layer.toGeoJSON();
    });
    applyFilters();
});

map.on(L.Draw.Event.DELETED, function () {
    drawnItems.clearLayers();
    drawnPolygonGeoJSON = null;
    applyFilters();
});

// Fungsi untuk membuat ikon penanda tujuan
const destinationIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
});

// Fungsi untuk menetapkan dan menampilkan penanda tujuan
function setDestination(lat, lng) {
    if (destinationMarker) {
        map.removeLayer(destinationMarker);
    }
    destinationCoordinates = [lat, lng];
    destinationMarker = L.marker(destinationCoordinates, { icon: destinationIcon }).addTo(map);
    destinationMarker.bindPopup("<b>Destination</b>").openPopup();

    if (embassyMarkers.getLayers().length > 0) {
        const bounds = embassyMarkers.getBounds().extend(destinationCoordinates);
        map.fitBounds(bounds, { padding: [50, 50] });
    } else {
        map.setView(destinationCoordinates, 10);
    }
}

// === Radius Circle & Location Pin ===
function updateRadiusCircle() {
    const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
    const center = lastClickedLocation ?? map.getCenter();

    if (radiusCircle) {
        map.removeLayer(radiusCircle);
        radiusCircle = null;
    }

    if (radius > 0) {
        radiusCircle = L.circle(center, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.3,
            radius: radius * 1000
        }).addTo(map);
    }
}

function placeLocationPin(location, label) {
    if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }
    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });
    radiusPinMarker = L.marker(location, {
        icon: redIcon,
        title: label || 'Selected Location',
        zIndexOffset: 9999
    }).addTo(map);
}

// Klik peta untuk menentukan titik pusat radius
map.on('click', function (e) {
    lastClickedLocation = { lat: e.latlng.lat, lng: e.latlng.lng };
    placeLocationPin(lastClickedLocation, 'Selected Location');
    updateRadiusCircle();
    categoryBar.style.display = 'flex';
});

// === Fetch & Tampilkan Embassy ===
async function fetchAndDisplayembassy(filters = {}) {
    embassyMarkers.clearLayers();

    const params = new URLSearchParams();
    Object.keys(filters).forEach(key => {
        if (Array.isArray(filters[key])) {
            filters[key].forEach(value => params.append(`${key}[]`, value));
        } else {
            params.append(key, filters[key]);
        }
    });

    if (drawnPolygonGeoJSON) {
        params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));
    }

    // --- Simpan parameter filter ke localStorage untuk persistensi ---
    localStorage.setItem('embessyFilterParams', params.toString());
    if (drawnPolygonGeoJSON) {
        localStorage.setItem('embessyDrawnPolygon', JSON.stringify(drawnPolygonGeoJSON));
    } else {
        localStorage.removeItem('embessyDrawnPolygon');
    }
    if (lastClickedLocation) {
        localStorage.setItem('embessyLastClickedCenter', JSON.stringify(lastClickedLocation));
    } else {
        localStorage.removeItem('embessyLastClickedCenter');
    }

    const totalEl = document.getElementById('totalCountDisplay');

    try {
        const response = await fetch(`/api/embassy?${params.toString()}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const embassy = await response.json();

        if (totalEl) totalEl.innerHTML = `<strong>Embassy:</strong> ${embassy.length}`;

        if (embassy.length === 0) {
            embassyMarkers.clearLayers();
            return;
        }

        embassy.forEach(embassy => {
            const embassyIcon = L.icon({
                iconUrl: '/images/embassy-icon-new.png',
                iconSize: [24, 24],
                iconAnchor: [12, 24],
                popupAnchor: [0, -20]
            });

            const marker = L.marker([embassy.latitude, embassy.longitude], { icon: embassyIcon }).addTo(embassyMarkers);

            // Simpan kedutaan terakhir yang diklik sebagai pusat radius
            marker.on('click', () => {
                lastClickedLocation = {
                    lat: embassy.latitude,
                    lng: embassy.longitude
                };
                updateRadiusCircle();
            });

            marker.bindPopup(`
                <h5 style="border-bottom:1px solid #cccccc;">${embassy.name_embassiees || 'N/A'}</h5>
                <strong>Address:</strong>
                    ${embassy.location || 'N/A'}
                    ${embassy.city ? ', ' + embassy.city : ''}
                    ${embassy.provinces_region ? ', ' + embassy.provinces_region : ''}, Thailand <br>
                <strong>Telephone:</strong> ${embassy.telephone || 'N/A'}<br>
                ${embassy.website ? `<strong>Website:</strong><a href='${embassy.website}' target='__blank'> ${embassy.website} </a><br>` : ''}
                ${embassy.id ? `<a href="/embassiees/${embassy.id}/detail" class="btn btn-primary btn-sm mt-2" style="color:white;">Read More</a>` : ''}
            `);
        });

        if (embassyMarkers.getLayers().length > 0) {
            let bounds = embassyMarkers.getBounds();
            if (destinationCoordinates) bounds.extend(destinationCoordinates);
            map.fitBounds(bounds, { padding: [50, 50] });
        } else if (destinationCoordinates) {
            map.setView(destinationCoordinates, 10);
        }
    } catch (error) {
        console.error('Error fetching embessy data:', error);
        if (totalEl) totalEl.innerText = 'Error loading embassy.';
    }
}

// === Apply Filter ===
function applyFilters() {
    const nameEl = document.getElementById('embassy_name_map');
    const name = nameEl ? nameEl.value : '';
    const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);

    const selectedProvinces = Array.from(document.querySelectorAll('.province-checkbox:checked'))
        .map(checkbox => checkbox.value);

    let filters = {
        name: name,
        provinces: selectedProvinces
    };

    if (radius > 0) {
        const center = lastClickedLocation ?? map.getCenter();
        filters.radius = radius;
        filters.center_lat = center.lat;
        filters.center_lng = center.lng;
    }

    fetchAndDisplayembassy(filters);
}

// === Filter Panel ===
let filterPanelDiv = null;

const FilterPanel = L.Control.extend({
    options: { position: 'topright' },
    onAdd: function () {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

        Object.assign(div.style, {
            background: 'white',
            borderRadius: '8px',
            boxShadow: '0 2px 6px rgba(0,0,0,0.2)',
            minWidth: '260px',
            maxWidth: '290px',
            overflow: 'visible',
            position: 'relative',
            display: 'flex',
            flexDirection: 'column'
        });

        div.innerHTML = `
            <button style="flex:0 0 auto;background:#007bff;color:white;border:none;width:100%;padding:8px;border-radius:8px 8px 0 0;font-weight:600;letter-spacing:0.3px;">Filter &amp; Radius</button>

            <!-- Search Location - NOT inside scrollable div so dropdown is never clipped -->
            <div id="searchSection" style="flex:0 0 auto;padding:10px 10px 6px 10px;background:white;position:relative;">
                <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Search Location</strong>
                <div style="position:relative;margin-top:5px;">
                    <input
                        type="text"
                        id="locationSearchMap"
                        placeholder="Search Location..."
                        autocomplete="off"
                        style="width:100%;padding:7px 30px 7px 9px;border:1.5px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;"
                    >
                    <span id="locationSearchClear" title="Clear"
                        style="position:absolute;right:8px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:15px;color:#aaa;display:none;">&times;</span>
                </div>
                <div id="locationFoundBadge" style="display:none;margin-top:6px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:5px;padding:4px 8px;font-size:12px;color:#2e7d32;">
                    &#128204; <span id="locationFoundName"></span>
                </div>
            </div>

            <!-- Radius -->
            <div id="radiusSection" style="flex:0 0 auto;padding:0 10px 0 10px;">
                <hr style="margin:8px 0;">
                <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Radius: <span id="radiusValueMap">0</span> km</strong>
                <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin:4px 0;">
                <div style="display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:5px;">
                    <span>0</span><span>250 km</span><span>500 km</span>
                </div>
                <div style="display:flex;gap:5px;margin-bottom:6px;">
                    <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
                    <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
                </div>
            </div>

            <!-- Scrollable filters -->
            <div id="filterPanel" style="flex:1 1 auto;min-height:0;padding:0 10px 10px 10px;overflow-y:auto;border-top:1px solid #eee;">
                <div style="padding-top:8px;">
                    <label>Diplomatic Missions:</label>
                    <select id="embassy_name_map" class="form-select form-select-sm mb-2 select-search-embassy-name">
                        <option value="">Select Embassy</option>
                        @foreach($embassyNames as $n)
                            <option value="{{ $n }}">{{ $n }}</option>
                        @endforeach
                    </select>
                    <hr>
                    <div class="filter-box" id="provinceSelect">
                        <label class="filter-label">Region</label>

                        <div class="select-input">
                            <input
                                type="text"
                                id="provinceSearch"
                                placeholder="Select Region"
                                readonly
                            >
                            <i class="bi bi-chevron-down"></i>
                        </div>

                        <div class="select-dropdown">
                            <input
                                type="text"
                                class="dropdown-search"
                                id="provinceSearchInput"
                                placeholder="Search Region..."
                            >

                            <ul id="provinceList">
                                @foreach ($provinces as $province)
                                <li>
                                    <label>
                                        <input
                                            type="checkbox"
                                            class="province-checkbox"
                                            value="{{ $province->id }}"
                                        >
                                        {{ $province->provinces_region }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <button id="resetMapFilter" class="btn btn-sm btn-secondary w-100">Reset All</button>
                    <div id="totalCountDisplay" style="margin-top:8px;text-align:center;font-size:13px;"></div>
                </div>
            </div>`;

        filterPanelDiv = div;
        L.DomEvent.disableClickPropagation(div);
        L.DomEvent.disableScrollPropagation(div);
        return div;
    }
});
map.addControl(new FilterPanel());

// Tinggi panel mengikuti tinggi peta supaya tombol "Reset All" tidak terpotong
function syncFilterPanelHeight() {
    if (!filterPanelDiv) return;
    filterPanelDiv.style.maxHeight = Math.max(220, map.getSize().y - 20) + 'px';
}
syncFilterPanelHeight();
map.on('resize', syncFilterPanelHeight);
window.addEventListener('resize', syncFilterPanelHeight);

// === Init Select2 (retry sampai panel benar-benar ada di DOM) ===
function initEmbassySelect2() {
    const nameEl = document.getElementById('embassy_name_map');
    if (typeof $ === 'undefined' || !$.fn || !$.fn.select2 || !nameEl) {
        setTimeout(initEmbassySelect2, 200);
        return;
    }
    if (!$(nameEl).hasClass('select2-hidden-accessible')) {
        $(nameEl).select2({ width: '100%', placeholder: 'Search Embassy', allowClear: true });
    }
}
initEmbassySelect2();

// Event select2 (delegated, jadi tidak tergantung timing DOM)
$(document).on('change', '#embassy_name_map', function () {
    applyFilters();
});

// === Init Location Search — Google Places Autocomplete ===
// .pac-container is repositioned to position:fixed via MutationObserver
// to bypass the map/panel container overflow:hidden clipping.
function initLocationSearch() {
    const input = document.getElementById('locationSearchMap');
    if (!input) {
        setTimeout(initLocationSearch, 300);
        return;
    }

    const clearBtn = document.getElementById('locationSearchClear');

    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['geocode', 'establishment'],
        fields: ['geometry', 'name', 'formatted_address']
    });

    let pacContainer = null;

    function fixPacPosition() {
        if (!pacContainer) return;
        const rect = input.getBoundingClientRect();
        pacContainer.style.position   = 'fixed';
        pacContainer.style.zIndex     = '2147483647';
        pacContainer.style.top        = (rect.bottom + 2) + 'px';
        pacContainer.style.left       = rect.left + 'px';
        pacContainer.style.width      = rect.width + 'px';
        pacContainer.style.borderRadius = '0 0 8px 8px';
        pacContainer.style.boxShadow  = '0 8px 24px rgba(0,0,0,0.2)';
        pacContainer.style.fontFamily = 'inherit';
    }

    const observer = new MutationObserver(() => {
        if (!pacContainer) {
            pacContainer = document.querySelector('.pac-container');
            if (pacContainer) {
                fixPacPosition();
                new MutationObserver(fixPacPosition).observe(
                    pacContainer, { attributes: true, attributeFilter: ['style'] }
                );
            }
        }
    });
    observer.observe(document.body, { childList: true, subtree: false });

    window.addEventListener('scroll', fixPacPosition, true);
    window.addEventListener('resize', fixPacPosition);
    input.addEventListener('focus',  fixPacPosition);
    input.addEventListener('input',  fixPacPosition);

    // Cegah peta menangkap input keyboard / pointer
    L.DomEvent.on(input, 'keydown keypress keyup mousedown dblclick wheel', L.DomEvent.stopPropagation);

    input.addEventListener('focus', () => {
        input.style.borderColor = '#1a73e8';
        input.style.boxShadow   = '0 0 0 3px rgba(26,115,232,0.15)';
    });
    input.addEventListener('blur', () => {
        input.style.borderColor = '#ddd';
        input.style.boxShadow   = 'none';
    });

    input.addEventListener('input', () => {
        if (clearBtn) clearBtn.style.display = input.value.length ? 'inline' : 'none';
    });

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.location) return;

        const loc = {
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng()
        };
        lastClickedLocation = loc;

        map.setView([loc.lat, loc.lng], 10);

        const label = place.name || place.formatted_address || 'Location';
        placeLocationPin(loc, label);

        if (clearBtn) clearBtn.style.display = 'inline';

        const badge     = document.getElementById('locationFoundBadge');
        const badgeName = document.getElementById('locationFoundName');
        if (badge)     badge.style.display = 'block';
        if (badgeName) badgeName.textContent = label;

        updateRadiusCircle();
        applyFilters();

        // Tampilkan bar nearby (Hotels) setelah lokasi ditemukan
        categoryBar.style.display = 'flex';
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            input.value = '';
            clearBtn.style.display = 'none';
            if (pacContainer) pacContainer.style.display = 'none';

            const badge = document.getElementById('locationFoundBadge');
            if (badge) badge.style.display = 'none';

            if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }
            if (radiusCircle)    { map.removeLayer(radiusCircle);    radiusCircle    = null; }
            lastClickedLocation = null;

            // Sembunyikan bar nearby & hapus marker hotel + rute
            categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
            closeRoutePanel();

            const rEl    = document.getElementById('radiusRangeMap');
            const rValEl = document.getElementById('radiusValueMap');
            if (rEl)    rEl.value          = 0;
            if (rValEl) rValEl.textContent = '0';

            applyFilters();
            input.focus();
        });
    }
}

// === Events ===
document.addEventListener('input', e => {
    if (e.target.id === 'radiusRangeMap') {
        const r = parseInt(e.target.value || 0);
        document.getElementById('radiusValueMap').textContent = r;
        updateRadiusCircle();
    }
});

document.addEventListener('click', async e => {
    if (e.target.id === 'applyRadiusMap') {
        applyFilters();
    }

    if (e.target.id === 'resetRadiusMap') {
        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }
        if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
        closeRoutePanel();

        applyFilters();
    }

    if (e.target.id === 'resetMapFilter') {
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => cb.checked = false);

        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            $('#embassy_name_map').val(null).trigger('change.select2');
        } else {
            const nameEl = document.getElementById('embassy_name_map');
            if (nameEl) nameEl.value = '';
        }

        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            provinceSearch.value = '';
            provinceSearch.placeholder = 'Select Region';
        }
        const provinceSearchInput = document.getElementById('provinceSearchInput');
        if (provinceSearchInput) provinceSearchInput.value = '';
        document.querySelectorAll('#provinceList li').forEach(li => { li.style.display = ''; });
        const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');
        if (provinceDropdown) provinceDropdown.classList.remove('show');

        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }
        if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        // Sembunyikan bar nearby & hapus marker hotel + rute
        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
        closeRoutePanel();

        if (destinationMarker) {
            map.removeLayer(destinationMarker);
            destinationMarker = null;
            destinationCoordinates = null;
        }

        // Bersihkan poligon
        drawnItems.clearLayers();
        drawnPolygonGeoJSON = null;

        // Hapus filter yang disimpan dari localStorage
        localStorage.removeItem('embessyFilterParams');
        localStorage.removeItem('embessyDrawnPolygon');
        localStorage.removeItem('embessyLastClickedCenter');

        map.setView([15.561656906765931, 100.85374832882776], 6);
        fetchAndDisplayembassy();
    }
}, true);

// === Checkbox change auto apply ===
document.addEventListener('change', e => {
    if (e.target.classList.contains('province-checkbox')) {
        applyFilters();
    }
});

// === Region: Select - Search Checkbox ===
document.addEventListener('click', (e) => {
    const provinceSelectInput = e.target.closest('#provinceSelect .select-input');
    const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');

    if (provinceSelectInput) {
        if (provinceDropdown) {
            provinceDropdown.classList.toggle('show');
            // pastikan dropdown tidak tertutup batas scroll panel
            if (provinceDropdown.classList.contains('show')) {
                setTimeout(() => provinceDropdown.scrollIntoView({ block: 'nearest' }), 0);
            }
        }
    } else {
        const provinceSelect = document.getElementById('provinceSelect');
        if (provinceSelect && !provinceSelect.contains(e.target) && provinceDropdown) {
            provinceDropdown.classList.remove('show');
        }
    }
}, true);

document.addEventListener('keyup', (e) => {
    if (e.target.id === 'provinceSearchInput') {
        const keyword = e.target.value.toLowerCase();
        document.querySelectorAll('#provinceList li').forEach(li => {
            const text = li.textContent.toLowerCase();
            li.style.display = text.includes(keyword) ? '' : 'none';
        });
    }
});

document.addEventListener('change', function (e) {
    if (e.target.classList.contains('province-checkbox')) {
        const selected = [...document.querySelectorAll('.province-checkbox:checked')]
            .map(cb => cb.parentElement.textContent.trim());
        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            if (selected.length === 0) {
                provinceSearch.value = '';
                provinceSearch.placeholder = 'Select Region';
            } else if (selected.length <= 2) {
                provinceSearch.value = selected.join(', ');
            } else {
                provinceSearch.value = selected.length + ' Region Selected';
            }
        }
    }
});

// === Muat filter tersimpan lalu terapkan ===
function loadFiltersAndApply() {
    const savedParamsString = localStorage.getItem('embessyFilterParams');
    const savedPolygonString = localStorage.getItem('embessyDrawnPolygon');
    const savedCenterString = localStorage.getItem('embessyLastClickedCenter');

    if (savedParamsString) {
        const params = new URLSearchParams(savedParamsString);

        // Radius
        const savedRadius = parseInt(params.get('radius')) || 0;
        const rEl    = document.getElementById('radiusRangeMap');
        const rValEl = document.getElementById('radiusValueMap');
        if (rEl)    rEl.value = savedRadius;
        if (rValEl) rValEl.textContent = savedRadius;

        // Checkbox provinsi
        const savedProvinces = params.getAll('provinces[]');
        document.querySelectorAll('.province-checkbox').forEach(checkbox => {
            checkbox.checked = savedProvinces.includes(checkbox.value);
        });
        const selected = [...document.querySelectorAll('.province-checkbox:checked')]
            .map(cb => cb.parentElement.textContent.trim());
        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch && selected.length) {
            provinceSearch.value = selected.length <= 2
                ? selected.join(', ')
                : selected.length + ' Region Selected';
        }

        // Select nama embassy
        const nameEl = document.getElementById('embassy_name_map');
        if (nameEl) nameEl.value = params.get('name') || '';
        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            $('#embassy_name_map').trigger('change.select2');
        }

        // Pusat radius terakhir
        if (savedCenterString) {
            lastClickedLocation = JSON.parse(savedCenterString);
            placeLocationPin(lastClickedLocation, 'Selected Location');
        }

        // Poligon tersimpan
        if (savedPolygonString) {
            drawnPolygonGeoJSON = JSON.parse(savedPolygonString);
            if (drawnPolygonGeoJSON && drawnPolygonGeoJSON.geometry && drawnPolygonGeoJSON.geometry.coordinates) {
                const layer = L.geoJSON(drawnPolygonGeoJSON);
                drawnItems.clearLayers();
                drawnItems.addLayer(layer);
                map.fitBounds(layer.getBounds(), { padding: [50, 50] });
            }
        }

        applyFilters();
        updateRadiusCircle();
    } else {
        fetchAndDisplayembassy();
    }
}

// === Init ===
setTimeout(() => {
    initLocationSearch();
}, 350);

// Retry sampai panel benar-benar ada di DOM sebelum memuat filter tersimpan
function initialApplyFilters() {
    if (!document.getElementById('radiusRangeMap')) {
        setTimeout(initialApplyFilters, 200);
        return;
    }
    loadFiltersAndApply();
}
initialApplyFilters();
</script>
@endpush
