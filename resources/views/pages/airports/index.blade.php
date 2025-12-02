@extends('layouts.master')

@section('title', 'Airports')
@section('page-title', 'Papua New Guinea Airports')

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
</style>
@endpush

@section('conten')

<div class="card">

    <div class="d-flex justify-content-end p-3" style="background-color: #dfeaf1;">

        <div class="d-flex gap-2 mt-2">

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
             <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                  <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
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
                    <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:30px; height:30px;">
                    <small>International</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:30px; height:30px;">
                    <small>Domestic</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:30px; height:30px;">
                    <small>Regional</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:30px; height:30px;">
                    <small>Military</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:30px; height:30px;">
                    <small>Combined (Civil - Military)</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:30px; height:30px;">
                    <small>Private</small>
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
       <p class="p-modal">Every attempt has been made to ensure the completeness and accuracy of the most updated information and data available. Clients are advised, however, that provided information, and data is subject to change.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level1Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Private Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level2Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Combined Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level3Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Military Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level4Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Regional Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level5Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <div class="modal-body">
        <p class="p-modal">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">International Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>


    <div id="map"></div>
</div>

@endsection

@push('service')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// === Inisialisasi Peta ===
const map = L.map('map')
    .setView([-8.6557505239603, 125.91557803754111], 6);

// === Layer ===
const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
}).addTo(map);

const satelliteLayer = L.tileLayer(
    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
    { attribution: 'Tiles © Esri', maxZoom: 19 }
);

L.control.layers(
    { "Street Map": osmLayer, "Satellite Map": satelliteLayer },
    null,
    { position: 'topleft' }
).addTo(map);

L.control.fullscreen({
    position: 'topleft' // tetap di kiri atas
}).addTo(map);

// === Styling posisi kontrol ===
const style = document.createElement('style');
style.textContent = `
.leaflet-top.leaflet-left .leaflet-control-layers { margin-top: 5px !important; }
.leaflet-top.leaflet-left .leaflet-control-zoom { margin-top: 10px !important; }
`;
document.head.appendChild(style);

// === Variabel Global ===
let airportMarkers = L.featureGroup().addTo(map);
let radiusCircle = null;
let radiusPinMarker = null;
let lastClickedLocation = null;
let drawnPolygonGeoJSON = null;

// === Leaflet Draw ===
const drawnItems = new L.FeatureGroup().addTo(map);
const drawControl = new L.Control.Draw({
    draw: {
        polygon: { allowIntersection: false, shapeOptions: { color: '#ff6600', fillColor: '#ff6600', fillOpacity: 0.2 } },
        polyline: false, rectangle: false, circle: false, marker: false, circlemarker: false
    },
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

// === Event Polygon ===
map.on(L.Draw.Event.CREATED, e => {
    drawnItems.clearLayers();
    drawnItems.addLayer(e.layer);
    drawnPolygonGeoJSON = e.layer.toGeoJSON();
    applyAirportFilters();
});

map.on(L.Draw.Event.EDITED, e => {
    e.layers.eachLayer(layer => drawnPolygonGeoJSON = layer.toGeoJSON());
    applyAirportFilters();
});

map.on(L.Draw.Event.DELETED, () => {
    drawnItems.clearLayers();
    drawnPolygonGeoJSON = null;
    applyAirportFilters();
});

// === Radius Circle ===
function updateRadiusCircleAndPin(radius = 0) {
    if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }
    if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }

    if (radius > 0 && lastClickedLocation) {
        radiusCircle = L.circle(lastClickedLocation, {
            color: 'red', fillColor: '#f03', fillOpacity: 0.3, radius: radius * 1000
        }).addTo(map);
        const redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });
        radiusPinMarker = L.marker(lastClickedLocation, { icon: redIcon }).addTo(map);
    }
}

map.on('click', e => {
    lastClickedLocation = { lat: e.latlng.lat, lng: e.latlng.lng };
    const radius = parseInt(document.querySelector('#radiusRangeMap').value || 0);
    document.querySelector('#radiusValueMap').textContent = radius;
    updateRadiusCircleAndPin(radius);
    applyAirportFilters();
});

// === Fetch Data Airport ===
async function fetchAirportData(filters = {}) {
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([k, v]) => {
        if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
        else if (v !== '' && v != null) params.append(k, v);
    });
    if (drawnPolygonGeoJSON) params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));

    try {
        const res = await fetch(`/api/airports?${params.toString()}`);
        return res.ok ? await res.json() : [];
    } catch (e) {
        console.error('Error fetching airport data:', e);
        return [];
    }
}

// === Tambah Marker Airport ===
function addAirportMarkers(data) {
    airportMarkers.clearLayers();
    data.forEach(airport => {
        if (!airport.latitude || !airport.longitude) return;

        const icon = L.icon({
            iconUrl: airport.icon || 'https://unpkg.com/leaflet/dist/images/marker-icon.png',
            iconSize: [24, 24], iconAnchor: [12, 24], popupAnchor: [0, -20]
        });

        const marker = L.marker([airport.latitude, airport.longitude], { icon }).addTo(airportMarkers);

        marker.bindPopup(`
            <h5 style="border-bottom:1px solid #ccc;">${airport.airport_name || 'N/A'}</h5>
            <strong>Classification:</strong> ${airport.category || 'N/A'}<br>
            <strong>Address:</strong> ${airport.address || 'N/A'}<br>
            <strong>Telephone:</strong> ${airport.telephone || 'N/A'}<br>
            ${airport.website ? `<strong>Website:</strong><a href='${airport.website}' target='__blank'> ${airport.website} </a><br>` : ''}
            ${airport.id ? `<a href="/airports/${airport.id}/detail" class="btn btn-primary btn-sm mt-2" style="color:white;">Read More</a>` : ''}
        `);
    });

    if (airportMarkers.getLayers().length > 0)
        map.fitBounds(airportMarkers.getBounds(), { padding: [50, 50] });
}

// === Apply Filter ===
async function applyAirportFilters() {
    // Ambil provinsi terpilih
    const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
    // Ambil kategori airport
    const aClasses = [...document.querySelectorAll('input[name="airportClass"]:checked')].map(e => e.value);
    // Ambil nama airport dari select2
    const airportSelect = $('#airport_name_map').val() || '';
    const airportName = Array.isArray(airportSelect) ? airportSelect[0] : airportSelect;
    // Ambil radius
    const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);

    // Siapkan filters
    let filters = {};
    if (airportName) filters.name = airportName;
    if (provs.length > 0) filters.provinces = provs;
    if (radius > 0 && lastClickedLocation) {
        filters.radius = radius;
        filters.center_lat = lastClickedLocation.lat;
        filters.center_lng = lastClickedLocation.lng;
    }

    const airports = await fetchAirportData(filters);

    const filteredAirports = airports.filter(a => {
        if (aClasses.length === 0) return true;
        if (!a.category) return false;
        const dbCategories = a.category.split(',').map(c => c.trim().toLowerCase());
        return aClasses.some(sel => dbCategories.includes(sel.toLowerCase()));
    });

    addAirportMarkers(filteredAirports);
    document.getElementById('totalCountDisplay').innerHTML = `<strong>Airports:</strong> ${filteredAirports.length}`;
}

// === Select2 Inisialisasi ===
$(document).ready(function() {
    $('#airport_name_map').select2({
        width: '100%',
        placeholder: 'Search Airport',
        allowClear: true
    });

    // Event select2 agar memicu filter otomatis
    $('#airport_name_map').on('change', function() {
        applyAirportFilters();
    });
});

// === Filter Panel ===
const FilterPanel = L.Control.extend({
    options: { position: 'topright' },
    onAdd: function () {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
        Object.assign(div.style, {
            background: 'white',
            borderRadius: '8px',
            boxShadow: '0 2px 6px rgba(0,0,0,0.2)',
            minWidth: '260px',
            maxHeight: '85vh',
            overflowY: 'auto'
        });

        div.innerHTML = `
            <button style="background:#007bff;color:white;border:none;width:100%;padding:8px;">Filter & Radius</button>
            <div id="filterPanel" style="padding:10px;">
                <strong>Radius: <span id="radiusValueMap">0</span> km</strong>
                <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin-bottom:6px;">
                <div style="display:flex;gap:5px;">
                    <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
                    <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
                </div>
                <hr>
                <label>Airport Name:</label>
                <select id="airport_name_map" class="form-select form-select-sm mb-2 select-search-airport">
                    <option value="">Select Airport</option>
                    @foreach($airportNames as $n)
                        <option value="{{ $n }}">{{ $n }}</option>
                    @endforeach
                </select>
                <label>Airport Category:</label>
                ${['International','Domestic','Military','Regional','Private'].map(c => `
                    <label style="display:block;font-size:13px;">
                        <input type="checkbox" name="airportClass" value="${c}"> ${c}
                    </label>`).join('')}
                <hr>
                <strong>Province</strong>
                <div style="max-height:120px;overflow-y:auto;border:1px solid #ccc;padding:5px;border-radius:5px;margin-top:6px;">
                    @foreach ($provinces as $p)
                        <div class="form-check">
                            <input class="form-check-input province-checkbox" type="checkbox" value="{{ $p->id }}">
                            <label class="form-check-label">{{ $p->provinces_region }}</label>
                        </div>
                    @endforeach
                </div>
                <hr>
                <button id="resetMapFilter" class="btn btn-sm btn-secondary w-100">Reset All</button>
                <div id="totalCountDisplay" style="margin-top:8px;text-align:center;font-size:13px;"></div>
            </div>`;
        L.DomEvent.disableClickPropagation(div);
        return div;
    }
});
map.addControl(new FilterPanel());

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
            alert('Klik lokasi di peta untuk menentukan titik radius.');
            return;
        }
        await applyAirportFilters();
    }

    if (e.target.id === 'resetRadiusMap') {
        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) map.removeLayer(radiusCircle);
        if (radiusPinMarker) map.removeLayer(radiusPinMarker);
        lastClickedLocation = null;
        await applyAirportFilters();
    }

    if (e.target.id === 'resetMapFilter') {
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.getElementById('airport_name_map').value = '';
        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) map.removeLayer(radiusCircle);
        if (radiusPinMarker) map.removeLayer(radiusPinMarker);
        lastClickedLocation = null;
        drawnItems.clearLayers();
        drawnPolygonGeoJSON = null;
        await applyAirportFilters();
    }
});

// === Checkbox & select change auto apply ===
document.addEventListener('change', e => {
    if (e.target.classList.contains('province-checkbox') || e.target.name === 'airportClass') {
        applyAirportFilters();
    }
});

// === Inisialisasi Awal ===
applyAirportFilters();
</script>

@endpush
