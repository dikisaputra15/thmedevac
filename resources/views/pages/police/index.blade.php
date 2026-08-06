@extends('layouts.master')

@section('title', 'Police')
@section('page-title', 'Papua New Guinea Police')

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
    .total-airports {
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

    .p-modal{
        text-align:justify;
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

        .gmap-camera-pad [data-dir="up"]    { top: 3px;  left: 33px; }
        .gmap-camera-pad [data-dir="down"]  { bottom: 3px; left: 33px; }
        .gmap-camera-pad [data-dir="left"]  { left: 3px;  top: 33px; }
        .gmap-camera-pad [data-dir="right"] { right: 3px; top: 33px; }
        .gmap-camera-pad [data-dir="close"] { top: 33px;  left: 33px; }

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

    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center gap-3 my-2">

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-link p-0 fw-bold text-decoration-underline text-dark" data-bs-toggle="modal" data-bs-target="#disclaimerModal">
                    <i class="bi bi-info-circle text-primary fs-5"></i>
                    Disclaimer
                </button>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="fw-bold me-2">Map Legend:</span>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                    <img src="{{ asset('images/Layer1.png') }}" style="width:15px; height:15px;">
                    <small>National Police (HQ)</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                    <img src="{{ asset('images/Layer2.png') }}" style="width:15px; height:15px;">
                    <small>Regional Police</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                    <img src="{{ asset('images/Layer3.png') }}" style="width:15px; height:15px;">
                    <small>Provincial Police</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                    <img src="{{ asset('images/Layer4.png') }}" style="width:15px; height:15px;">
                    <small>City Police Station</small>
                </button>

            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="disclaimerModal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="disclaimerLabel">Disclaimer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <p class="p-modal text-justify">Every attempt has been made to ensure the completeness and accuracy of the most updated information and data available. Clients are advised, however, that provided information, and data is subject to change.</p>
       <h5 class="modal-title" id="disclaimerLabel">Google Maps Link</h5>
       <p class="p-modal text-justify">Google Maps may automatically display or translate content based on the user’s current region, browser settings, or Google account preferences. This issue may occur when opening google maps link from TCMT platform using Microsoft Edge. For the best experience, we recommend opening the Google Chrome link while logged into your Google account. You can also use your browser’s translation feature to view Google Maps in your preferred language.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level3Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="{{ asset('images/Layer4.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">City Police Station</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level4Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer3.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Provincial Police</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level5Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer2.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Regional Police</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer1.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">National Police (HQ)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
    </div>
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
}).setView([15.561656906765931, 100.85374832882776], 5);

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

// === Global Variable ===
let policeMarkers = L.featureGroup().addTo(map);
let radiusCircle = null;
let radiusPinMarker = null;
let lastClickedLocation = null;
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

// === Nearby Category Bar (Google Maps style) — Hotels only ===
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

// === Polygon Draw (Custom Point-by-Point) ===
let isDrawingPolygon = false;
let polygonLatLngs = [];
let activePolygon = null;
let activePolyline = null;
let cursorPolyline = null;
let startMarker = null;

const drawButton = document.createElement('div');
drawButton.innerHTML = '⬟';
Object.assign(drawButton.style, {
    backgroundColor: 'white', border: '2px solid rgba(0,0,0,0.2)', borderRadius: '4px',
    width: '34px', height: '34px', textAlign: 'center', lineHeight: '30px',
    fontSize: '18px', cursor: 'pointer', margin: '10px'
});
drawButton.title = 'Draw Polygon (Click point by point, click starting point to finish)';

const clearButton = document.createElement('div');
clearButton.innerHTML = '🗑️';
Object.assign(clearButton.style, {
    backgroundColor: 'white', border: '2px solid rgba(0,0,0,0.2)', borderRadius: '4px',
    width: '34px', height: '34px', textAlign: 'center', lineHeight: '30px',
    fontSize: '16px', cursor: 'pointer', margin: '10px 0'
});
clearButton.title = 'Clear Polygon';

const PolygonDrawControl = L.Control.extend({
    options: { position: 'topleft' },
    onAdd: function () {
        const wrap = L.DomUtil.create('div', 'polygon-draw-control');
        wrap.style.border = 'none';
        wrap.style.margin = '0';
        wrap.style.display = 'flex';
        wrap.style.flexDirection = 'column';
        wrap.style.alignItems = 'flex-start';
        wrap.appendChild(drawButton);
        wrap.appendChild(clearButton);
        L.DomEvent.disableClickPropagation(wrap);
        return wrap;
    }
});
map.addControl(new PolygonDrawControl());

drawButton.addEventListener('click', () => {
    isDrawingPolygon = !isDrawingPolygon;
    if (isDrawingPolygon) {
        map.dragging.disable();
        map.doubleClickZoom.disable();
        drawButton.style.backgroundColor = '#ccc';
        map.getContainer().style.cursor = 'crosshair';
        polygonLatLngs = [];
        if (activePolygon) map.removeLayer(activePolygon);
        if (activePolyline) map.removeLayer(activePolyline);
        if (cursorPolyline) map.removeLayer(cursorPolyline);
        if (startMarker) map.removeLayer(startMarker);
        activePolygon = null;
        activePolyline = L.polyline([], {
            color: '#007bff', opacity: 0.8, weight: 3, interactive: false
        }).addTo(map);
        cursorPolyline = L.polyline([], {
            color: '#007bff', opacity: 0.5, weight: 3, interactive: false
        }).addTo(map);
        startMarker = null;
        drawnPolygonGeoJSON = null;
    } else {
        finishPolygon();
    }
});

map.on('mousemove', e => {
    if (!isDrawingPolygon || polygonLatLngs.length === 0) return;
    const lastPoint = polygonLatLngs[polygonLatLngs.length - 1];
    cursorPolyline.setLatLngs([lastPoint, e.latlng]);
});

map.on('contextmenu', () => {
    if (isDrawingPolygon) finishPolygon();
});

async function finishPolygon() {
    if (!isDrawingPolygon) return;
    isDrawingPolygon = false;
    map.dragging.enable();
    map.doubleClickZoom.enable();
    drawButton.style.backgroundColor = 'white';
    map.getContainer().style.cursor = '';
    if (cursorPolyline) { map.removeLayer(cursorPolyline); cursorPolyline = null; }
    if (startMarker) { map.removeLayer(startMarker); startMarker = null; }

    if (polygonLatLngs.length > 2) {
        if (activePolyline) { map.removeLayer(activePolyline); activePolyline = null; }
        activePolygon = L.polygon(polygonLatLngs, {
            color: '#007bff', opacity: 0.8, weight: 3,
            fillColor: '#007bff', fillOpacity: 0.2
        }).addTo(map);

        const coordinates = polygonLatLngs.map(p => [p.lng, p.lat]);
        coordinates.push([polygonLatLngs[0].lng, polygonLatLngs[0].lat]);

        drawnPolygonGeoJSON = {
            type: "Feature",
            geometry: { type: "Polygon", coordinates: [coordinates] },
            properties: {}
        };

        // Editable vertices (leaflet.draw edit handler)
        if (activePolygon.editing) activePolygon.editing.enable();

        const updatePolygonFilter = async () => {
            if (!activePolygon) return;
            const ring = activePolygon.getLatLngs()[0];
            if (ring.length > 2) {
                const newCoords = ring.map(p => [p.lng, p.lat]);
                newCoords.push([ring[0].lng, ring[0].lat]);
                drawnPolygonGeoJSON.geometry.coordinates = [newCoords];
                await applyPoliceFilters();
            }
        };

        activePolygon.on('edit', updatePolygonFilter);

        await applyPoliceFilters();
    } else {
        if (activePolyline) { map.removeLayer(activePolyline); activePolyline = null; }
        activePolygon = null;
        drawnPolygonGeoJSON = null;
    }
}

clearButton.addEventListener('click', async () => {
    if (activePolygon) map.removeLayer(activePolygon);
    if (activePolyline) map.removeLayer(activePolyline);
    if (cursorPolyline) map.removeLayer(cursorPolyline);
    if (startMarker) map.removeLayer(startMarker);
    activePolygon = null;
    activePolyline = null;
    cursorPolyline = null;
    startMarker = null;
    polygonLatLngs = [];
    drawnPolygonGeoJSON = null;
    isDrawingPolygon = false;
    map.dragging.enable();
    map.doubleClickZoom.enable();
    drawButton.style.backgroundColor = 'white';
    map.getContainer().style.cursor = '';
    await applyPoliceFilters();
});

// === Radius Circle & Location Pin ===
function updateRadiusCircleAndPin(radius = 0) {
    if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }

    if (radius > 0 && lastClickedLocation) {
        radiusCircle = L.circle(lastClickedLocation, {
            color: '#1565c0', opacity: 0.8, weight: 2,
            fillColor: '#1565c0', fillOpacity: 0.2,
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

map.on('click', e => {
    if (isDrawingPolygon) {
        polygonLatLngs.push(e.latlng);
        activePolyline.setLatLngs(polygonLatLngs);

        if (polygonLatLngs.length === 1) {
            startMarker = L.circleMarker(e.latlng, {
                radius: 6, fillColor: '#FFFFFF', fillOpacity: 1,
                color: '#007bff', weight: 2
            }).addTo(map);
            startMarker.on('click', () => {
                if (isDrawingPolygon) finishPolygon();
            });
        }
        return;
    }

    lastClickedLocation = { lat: e.latlng.lat, lng: e.latlng.lng };
    placeLocationPin(lastClickedLocation, 'Selected Location');
    const radius = parseInt(document.querySelector('#radiusRangeMap')?.value || 0);
    const radiusValEl = document.querySelector('#radiusValueMap');
    if (radiusValEl) radiusValEl.textContent = radius;
    updateRadiusCircleAndPin(radius);
    categoryBar.style.display = 'flex';
    applyPoliceFilters();
});

// === Fetch Data POLICE ===
async function fetchPoliceData(filters = {}) {
    const params = new URLSearchParams();

    Object.entries(filters).forEach(([k, v]) => {
        if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
        else if (v !== '' && v != null) params.append(k, v);
    });

    if (drawnPolygonGeoJSON) {
        params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));
    }

    try {
        const res = await fetch(`/api/polices?${params.toString()}`);
        return res.ok ? await res.json() : [];
    } catch (e) {
        console.error('Error fetching police:', e);
        return [];
    }
}

// === Marker POLICE ===
function addPoliceMarkers(data) {
    policeMarkers.clearLayers();

    data.forEach(police => {
        if (!police.latitude || !police.longitude) return;

        const icon = L.icon({
            iconUrl: police.icon ? police.icon : 'https://png.pngtree.com/png-vector/20221211/ourmid/pngtree-minimal-location-map-icon-logo-symbol-vector-design-transparent-background-png-image_6520892.png',
            iconSize: [12, 12],
            iconAnchor: [15, 30],
            popupAnchor: [0, -25]
        });

        const destLat = parseFloat(police.latitude);
        const destLng = parseFloat(police.longitude);

        const marker = L.marker([destLat, destLng], { icon: icon }).addTo(policeMarkers);

        const itemName  = police.name_police || 'N/A';
        const detailUrl = `/police/${police.id}/detail`;

        const popupContent = `
            <h5 style="border-bottom:1px solid #cccccc;"><a href="${detailUrl}" style="color:inherit;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#1a73e8'" onmouseout="this.style.color='inherit'">${itemName}</a></h5>
            <strong>Category:</strong> ${police.category || 'N/A'}<br>
            <strong>Address:</strong>
                ${police.location || 'N/A'},
                ${police.city_name || 'N/A'},
                ${police.province_name || 'N/A'}, Thailand<br>
            <strong>Phone:</strong> ${police.telephone || 'N/A'}<br>
            <strong>Website:</strong> ${police.website || 'N/A'}<br>
        `;

        // Tombol dibangun saat popup dibuka supaya status lastClickedLocation selalu terbaru
        marker.on('click', () => {
            let actionBtns = '';
            if (lastClickedLocation && !isNaN(destLat) && !isNaN(destLng)) {
                const oLat = lastClickedLocation.lat;
                const oLng = lastClickedLocation.lng;
                actionBtns = `
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;display:flex;gap:6px;flex-wrap:wrap;">
                        <button onclick="showRouteOnMap(${oLat},${oLng},${destLat},${destLng},'${(itemName||'').replace(/'/g,"\\'")}')"
                           style="display:inline-flex;align-items:center;gap:5px;
                                  background:#1a73e8;color:#fff;border:none;
                                  padding:5px 12px;border-radius:6px;font-size:12px;
                                  font-weight:500;cursor:pointer;">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                            </svg>
                            Get Directions
                        </button>
                        <a href="${detailUrl}"
                           style="display:inline-flex;align-items:center;gap:5px;
                                  background:#395272;color:#fff;text-decoration:none;
                                  padding:5px 12px;border-radius:6px;font-size:12px;
                                  font-weight:500;"
                           onmouseover="this.style.background='#5686c3'"
                           onmouseout="this.style.background='#395272'">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                            </svg>
                            Read More
                        </a>
                    </div>`;
            } else {
                actionBtns = `
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;">
                        <a href="${detailUrl}"
                           style="display:inline-flex;align-items:center;gap:5px;
                                  background:#395272;color:#fff;text-decoration:none;
                                  padding:5px 12px;border-radius:6px;font-size:12px;
                                  font-weight:500;"
                           onmouseover="this.style.background='#5686c3'"
                           onmouseout="this.style.background='#395272'">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                            </svg>
                            Read More
                        </a>
                    </div>`;
            }

            marker.setPopupContent(`<div style="font-size:13px; min-width: 200px;">${popupContent}${actionBtns}</div>`);
        });

        marker.bindPopup(`<div style="font-size:13px; min-width: 200px;">${popupContent}</div>`);
    });

    if (policeMarkers.getLayers().length > 0) {
        map.fitBounds(policeMarkers.getBounds(), { padding: [50, 50] });
    }
}

// === Apply Filter POLICE ===
async function applyPoliceFilters() {
    const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
    const categories = [...document.querySelectorAll('input[name="policeCategory"]:checked')].map(e => e.value);
    const policeName = $('#police_name_map').val() || '';
    const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);

    let filters = {};

    if (policeName) filters.name = policeName;
    if (provs.length > 0) filters.provinces = provs;
    if (categories.length > 0) filters.categories = categories;

    if (radius > 0 && lastClickedLocation) {
        filters.radius = radius;
        filters.center_lat = lastClickedLocation.lat;
        filters.center_lng = lastClickedLocation.lng;
    }

    const result = await fetchPoliceData(filters);

    const polices = result.polices;
    const categoryCounts = result.categoryCounts;

    addPoliceMarkers(polices);

    document.getElementById('totalCountDisplay').innerHTML =
        `<strong>Police:</strong> ${polices.length}`;

    Object.keys(categoryCounts).forEach(cat => {

        const id = cat.replace(/[^a-zA-Z0-9]/g,'-');

        const el = document.getElementById(`count-${id}`);

        if (el) {
            el.textContent = categoryCounts[cat];
        }
    });
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
                    <label>Police Name:</label>
                    <select id="police_name_map" class="form-select form-select-sm mb-2 select-search-police">
                        <option value="">Select Police</option>
                        @foreach($policeNames as $n)
                            <option value="{{ $n }}">{{ $n }}</option>
                        @endforeach
                    </select>
                    <label>Category:</label>
                    ${[
                        'National Police (HQ)',
                        'Regional Police',
                        'Provincial Police',
                        'City Police Station',
                    ].map(c => `
                    <label style="display:block;font-size:13px;margin-bottom:5px;">
                        <input type="checkbox" name="policeCategory" value="${c}">
                        ${c} (<span id="count-${c.replace(/[^a-zA-Z0-9]/g,'-')}">0</span>)
                    </label>
                    `).join('')}
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
                                @foreach ($provinces as $p)
                                <li>
                                    <label>
                                        <input
                                            type="checkbox"
                                            class="province-checkbox"
                                            value="{{ $p->id }}"
                                        >
                                        {{ $p->provinces_region }}
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
function initPoliceSelect2() {
    const el = document.getElementById('police_name_map');
    if (typeof $ === 'undefined' || !$.fn || !$.fn.select2 || !el) {
        setTimeout(initPoliceSelect2, 200);
        return;
    }
    if ($(el).hasClass('select2-hidden-accessible')) return;
    $(el).select2({
        width: '100%',
        placeholder: 'Search Police',
        allowClear: true
    });
}
initPoliceSelect2();

// Event select2 (delegated, jadi tidak tergantung timing DOM)
$(document).on('change', '#police_name_map', function() {
    applyPoliceFilters();
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

        const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
        updateRadiusCircleAndPin(radius);
        categoryBar.style.display = 'flex';
        applyPoliceFilters();
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

            categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
            closeRoutePanel();

            const rEl    = document.getElementById('radiusRangeMap');
            const rValEl = document.getElementById('radiusValueMap');
            if (rEl)    rEl.value          = 0;
            if (rValEl) rValEl.textContent = '0';

            applyPoliceFilters();
            input.focus();
        });
    }
}

// === Events ===
document.addEventListener('input', e => {
    if (e.target.id === 'radiusRangeMap') {
        const r = parseInt(e.target.value || 0);
        document.getElementById('radiusValueMap').textContent = r;
        updateRadiusCircleAndPin(r);
    }
});

document.addEventListener('click', async e => {
    if (e.target.id === 'applyRadiusMap') {
        const radius = parseInt(document.getElementById('radiusRangeMap').value || 0);
        if (radius > 0 && !lastClickedLocation) {
            alert('Cari lokasi terlebih dahulu menggunakan kolom "Search Location", atau klik langsung pada peta untuk menentukan titik radius.');
            return;
        }
        await applyPoliceFilters();
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

        await applyPoliceFilters();
    }

    if (e.target.id === 'resetMapFilter') {
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => cb.checked = false);
        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            $('.select-search-police').val(null).trigger('change');
        } else {
            document.getElementById('police_name_map').value = '';
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

        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
        closeRoutePanel();

        if (activePolygon) map.removeLayer(activePolygon);
        if (activePolyline) map.removeLayer(activePolyline);
        if (cursorPolyline) map.removeLayer(cursorPolyline);
        if (startMarker) map.removeLayer(startMarker);
        activePolygon = null;
        activePolyline = null;
        cursorPolyline = null;
        startMarker = null;
        polygonLatLngs = [];
        drawnPolygonGeoJSON = null;
        isDrawingPolygon = false;
        map.dragging.enable();
        map.doubleClickZoom.enable();
        drawButton.style.backgroundColor = 'white';
        map.getContainer().style.cursor = '';

        await applyPoliceFilters();
    }
}, true);

// === Checkbox & select change auto apply ===
document.addEventListener('change', e => {
    if (e.target.classList.contains('province-checkbox') || e.target.name === 'policeCategory') {
        applyPoliceFilters();
    }
});

// === Province: Select - Search Checkbox ===
document.addEventListener('click', (e) => {
    const provinceSelectInput = e.target.closest('#provinceSelect .select-input');
    const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');

    if (provinceSelectInput) {
        if (provinceDropdown) provinceDropdown.classList.toggle('show');
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

document.addEventListener('change', function(e) {
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

// === Init ===
setTimeout(() => {
    initLocationSearch();
}, 350);

// Retry sampai badge kategori (di dalam filter panel) benar-benar ada di DOM,
// supaya jumlah per kategori tidak "nyangkut" di 0 saat load pertama.
function initialApplyFilters() {
    if (!document.querySelector('#filterPanel [id^="count-"]')) {
        setTimeout(initialApplyFilters, 200);
        return;
    }
    applyPoliceFilters();
}
initialApplyFilters();
</script>

@endpush
