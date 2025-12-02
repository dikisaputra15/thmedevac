@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'Papua New Guinea Airports')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

<style>
    #map {
        height: 600px;
    }

    table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
    }
    td {
        border: 1px solid black;
        text-align: justify;
        border-color: #78d9e9;
        padding: 0 5px;
    }

    table tr:nth-child(even) {
        background-color: #ffffff; /* light gray */
    }

    table tr:nth-child(odd) {
        background-color: #c1e4f5; /* white */
    }

     p{
        margin-bottom: 8px;
        line-height: 18px;
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

    <div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">
        <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $airport->airport_name }}</h2>
            <span class="fw-bold"><b>Airfield Category:</b> {{ $airport->category }}</span>
        </div>

        <div class="d-flex gap-2 ms-auto">

              <!-- Button 2 -->
            <a href="{{ url('airports') }}/{{$airport->id}}/detail" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/detail') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <!-- Button 3 -->
            <a href="{{ url('airports') }}/{{$airport->id}}/navigation" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/navigation') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-navaids-white.png') }}" style="width: 24px; height: 24px;">
                <small>Navigation</small>
            </a>

             <!-- Button 4 -->
             <a href="{{ url('airports') }}/{{$airport->id}}/airlinesdestination" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/airlinesdestination') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-destination-white.png') }}" style="width: 24px; height: 24px;">
                <small>Destination</small>
            </a>

            <!-- Button 5 -->
            <a href="{{ url('airports') }}/{{$airport->id}}/emergency" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/emergency') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-emergency-support-white.png') }}" style="width: 24px; height: 24px;">
                <small>Emergency</small>
            </a>

             <!-- Button 5 -->
            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <!-- Button 6 -->
            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <!-- Button 7 -->
            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                <small>Embassies</small>
            </a>

        </div>
</div>

   <div class="card mb-4 position-relative">
        <div class="card-body" style="padding:0 7px;">
            <small><i>Last Updated {{ $airport->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('airportdata.edit', $airport->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">

        <!-- Kolom 1 -->
        <div class="col-md-4">
            <!-- Card 1 -->
            <div class="card-body" style="padding: 0 10px;">
                <div class="card">
                    <div class="card-header fw-bold"><img src="{{ asset('images/icon-navaids.png') }}" style="width: 24px; height: 24px;"> Navigation Aids (NAVAIDs)</div>
                    <div class="card-body overflow-auto" style="max-height: 200px;">
                        <?php echo $airport->navigation_aids; ?>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="card-body" style="padding: 0 10px;">
                <div class="card">
                    <div class="card-header fw-bold"><img src="{{ asset('images/icon-comms.png') }}" style="width: 24px; height: 20px;"> Communication Data</div>
                    <div class="card-body overflow-auto" style="max-height: 200px;">
                        <?php echo $airport->communication; ?>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card-body" style="padding: 0 10px;">
                <div class="card">
                    <div class="card-header fw-bold"><img src="{{ asset('images/icon-runway.jpg') }}" style="width: 24px; height: 24px;"> Runway Data</div>
                    <div class="card-body overflow-auto" style="max-height: 300px;">
                          <p><strong>Runway Edge Lights:</strong> {{ $airport->runway_edge_lights }} </p>
                          <p><strong>Runways End Identifier Lights (REIL):</strong> {{ $airport->reil }} </p>
                        <?php echo $airport->runways; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom 2 -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold"><i class="fas fa-map"></i>Nearby Airfield (Up To 500 KM)</div>
                <div class="card-body">

                 <div class="col-md-12">
                    <div class="d-flex justify-content-end align-items-center">

                        <div class="d-flex align-items-center">

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:18px; height:18px;">
                                <small>International</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:18px; height:18px;">
                                <small>Domestic</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:18px; height:18px;">
                                <small>Regional Domestic</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                <small>Combined (Civil - Military)</small>
                            </button>

                             <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:18px; height:18px;">
                                <small>Military</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:18px; height:18px;">
                                <small>Private</small>
                            </button>
                        </div>

                    </div>
                </div>

                    <div id="map"></div>
                </div>
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


@endsection

@push('service')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const airportData = {!! json_encode([
            'id'        => $airport->id,
            'name'      => $airport->airport_name,
            'latitude'  => $airport->latitude,
            'longitude' => $airport->longitude,
            'icon'      => $airport->icon ?? '',
            'image'     => $airport->image ?? '',
            'address'   => $airport->address ?? '',
            'telephone' => $airport->telephone ?? '',
            'website'   => $airport->website ?? '',
        ]) !!};

        // Data bandara terdekat (dari controller)
        const nearbyAirports = @json($nearbyAirports);
        const radiusKm = {{ $radius_km }};

        let map;
        let mainAirportMarker;
        let nearbyMarkersGroup = L.featureGroup();
        let radiusCircle;
        let routingControl;

        // Default icon
        const DEFAULT_MAIN_AIRPORT_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
        const DEFAULT_AIRPORT_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';

        // Custom icon untuk bandara utama
        const mainAirportIcon = new L.Icon({
            iconUrl: DEFAULT_MAIN_AIRPORT_ICON_URL,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        function initializeMap() {
            map = L.map('map', {
                fullscreenControl: true
            }).setView([airportData.latitude, airportData.longitude], 12);

            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            });

            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri',
                maxZoom: 19
            });

            satelliteLayer.addTo(map);

            const baseLayers = {
                "Satelit Map": satelliteLayer,
                "Street Map": osmLayer
            };

            L.control.layers(baseLayers).addTo(map);
            nearbyMarkersGroup.addTo(map);
        }

        function addMainAirportAndCircle() {
            mainAirportMarker = L.marker([airportData.latitude, airportData.longitude], { icon: mainAirportIcon })
                .addTo(map)
                .bindPopup(`<b>${airportData.name}</b><br>This is the main airport.`)
                .openPopup();

            radiusCircle = L.circle([airportData.latitude, airportData.longitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.1,
                radius: radiusKm * 1000
            }).addTo(map);
        }

        function addNearbyAirports(data) {
            data.forEach(item => {
                const itemIcon = L.icon({
                    iconUrl: item.icon || DEFAULT_AIRPORT_ICON_URL,
                    iconSize: [24, 24],
                    iconAnchor: [12, 24],
                    popupAnchor: [0, -20]
                });

                const name = item.airport_name || 'N/A';
                const detailUrl = `/airports/${item.id}/detail`;
                const distance = item.distance ? `<br><strong>Distance:</strong> ${item.distance.toFixed(2)} km` : '';

                const marker = L.marker([item.latitude, item.longitude], { icon: itemIcon })
                    .bindPopup(`
                        <b><a href="${detailUrl}">${name}</a></b> (Airport)<br>
                        ${distance}<br>
                        <button class="btn btn-sm btn-primary mt-2" onclick="getDirection(${item.latitude}, ${item.longitude}, '${name}')">
                            Get Direction
                        </button>
                    `);

                nearbyMarkersGroup.addLayer(marker);
            });
        }

        function fitMapToBounds() {
            const bounds = L.featureGroup([mainAirportMarker, nearbyMarkersGroup, radiusCircle]).getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        }

        // === Get Direction Function ===
        window.getDirection = function(lat, lng, name) {
            if (routingControl) {
                map.removeControl(routingControl);
            }

            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(airportData.latitude, airportData.longitude), // asal
                    L.latLng(lat, lng) // tujuan
                ],
                routeWhileDragging: true,
                show: true,
                createMarker: function(i, wp, nWps) {
                    if (i === 0) {
                        return L.marker(wp.latLng, { icon: mainAirportIcon }) // pakai icon bandara
                            .bindPopup(`<b>${airportData.name}</b><br>Start Point`);
                    } else if (i === nWps - 1) {
                        return L.marker(wp.latLng)
                            .bindPopup(`<b>${name}</b><br>Destination`);
                    }
                }
            }).addTo(map);

            // sembunyikan panel bawaan saat pertama kali
            const panel = document.querySelector('.leaflet-routing-container');
            if (panel) {
                panel.style.display = 'none';
            }

            // buat tombol toggle kalau belum ada
            if (!document.getElementById('toggle-route-panel')) {
                const toggleBtn = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                toggleBtn.id = 'toggle-route-panel';
                toggleBtn.innerHTML = '<a href="#" title="Show/Hide Route Panel"><i class="fa fa-route"></i></a>';
                toggleBtn.style.alignItems = 'center';
                toggleBtn.style.justifyContent = 'center';

                toggleBtn.onclick = function(e) {
                    e.preventDefault();
                    if (panel.style.display === 'none') {
                        panel.style.display = 'block';
                    } else {
                        panel.style.display = 'none';
                    }
                };

                // pasang tombol di pojok kanan atas map
                map.getContainer().appendChild(toggleBtn);
                toggleBtn.style.position = 'absolute';
                toggleBtn.style.top = '60px';
                toggleBtn.style.right = '10px';
                toggleBtn.style.zIndex = 1000;
            }
        };


        // Eksekusi utama
        initializeMap();
        addMainAirportAndCircle();
        addNearbyAirports(nearbyAirports);
        fitMapToBounds();


    });
</script>

@endpush
