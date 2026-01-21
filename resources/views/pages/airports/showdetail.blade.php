@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'Papua New Guinea Airports')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />
<style>
    #map {
        height: 600px;
    }

    table {
        border: 1px solid black;
        border-collapse: collapse;
    }
    td {
        border: 1px solid black;
        padding: 4px;
    }

    p {
        margin-bottom: 8px;
        line-height: 18px;
    }

    .btn-danger {
        background-color:#395272;
        border-color: transparent;
    }

    .btn-danger:hover {
        background-color:#5686c3;
        border-color: transparent;
    }

    .btn.active {
        background-color: #5686c3 !important;
        border-color: transparent !important;
        color: #fff !important;
    }

    .p-3 {
        padding: 10px !important;
        margin: 0 3px;
    }

    .btn-outline-danger {
        color: #FFFFFF;
        background-color:#395272;
        border-color: transparent;
    }

    .btn-outline-danger:hover {
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

            <a href="{{ url('airports') }}/{{$airport->id}}/detail" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/detail') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <a href="{{ url('airports') }}/{{$airport->id}}/navigation" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/navigation') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-navaids-white.png') }}" style="width: 24px; height: 24px;">
                <small>Navigation</small>
            </a>

            <a href="{{ url('airports') }}/{{$airport->id}}/airlinesdestination" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/airlinesdestination') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-destination-white.png') }}" style="width: 24px; height: 24px;">
                <small>Destination</small>
            </a>

            <a href="{{ url('airports') }}/{{$airport->id}}/emergency" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/emergency') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-emergency-support-white.png') }}" style="width: 24px; height: 24px;">
                <small>Emergency</small>
            </a>

            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
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
        <div class="col-md-3">
                <div class="card">
                        <div class="card-header fw-bold"><img src="{{ asset('images/icon-general-info.png') }}" style="width: 24px; height: 24px;"> General Airport Info</div>
                        <div class="card-body overflow-auto">
                            @if(!empty($airport->military_branch))
                                <p><strong>Military Branch:</strong> {{ $airport->military_branch }} </p>
                            @endif
                            <p><strong>IATA Code:</strong> {{ $airport->iata_code }} </p>
                            <p><strong>ICAO Code:</strong> {{ $airport->icao_code }} </p>
                            <p><strong>Hrs of Operation:</strong> {!! $airport->hrs_of_operation !!} </p>
                            <p><strong>Distance To:</strong><br>
                                <?php echo $airport->distance_from; ?>
                            </p>
                            <p><strong>Elevation:</strong> {{ $airport->elevation }} </p>
                            <p><strong>Time Zone:</strong> {{ $airport->time_zone }} </p>
                            <p><strong>Magnetic Variation:</strong> {{ $airport->magnetic_variation }} </p>
                            <p><strong>Beacon:</strong> {{ $airport->beacon }}</p>
                            <p><strong>Max Aircraft Capability:</strong> {{ $airport->max_aircraft_capability }} </p>
                            <p><strong>Directorate General of Civil Aviation:</strong> {!! $airport->dgoca !!}  </p>
                            <p><strong>Operator:</strong> {!! $airport->operator !!}  </p>
                            <p><strong>Link:</strong> {!! $airport->soao !!}  </p>
                            <p><strong>Other Airport Info:</strong> {!! $airport->other_reference_website !!}  </p>
                            @if(!empty($airport->note))
                                <p><strong>Note:</strong> {!! $airport->note !!} </p>
                            @endif
                        </div>
                </div>
        </div>

        <div class="col-md-2">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/contact-icon.png') }}" style="width: 24px; height: 24px;"> Contact Details</div>
                <div class="card-body overflow-auto">
                    <p><strong>Telephone:</strong> <?php echo $airport->telephone; ?></p>
                    <p><strong>Fax:</strong> <?php echo $airport->fax; ?> </p>
                    <p><strong>Email:</strong> <?php echo $airport->email; ?> </p>
                    <p><strong>Website:</strong> <?php echo $airport->website; ?> </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-location.png') }}" style="width: 18px; height: 24px;"> Location</div>
                <div class="card-body overflow-auto">
                    <p><strong>Address:</strong>
                        {{ $city->city }}, {{ $province->provinces_region }}, Thailand
                    </p>
                    <p><strong>Latitude:</strong> {{ $airport->latitude }} </p>
                    <p><strong>Longitude:</strong> {{ $airport->longitude }} </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-nearest-accomodation.png') }}" style="width: 24px; height: 18px;"> Nearest Accomodation</div>
                <div class="card-body overflow-auto">
                    {!! $airport->nearest_accommodation !!} {{-- Menggunakan {!! !!} jika kontennya HTML --}}
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-support-service.png') }}" style="width: 24px; height: 24px;"> Support Services</div>
                <div class="card-body overflow-auto">
                    <p>
                        <strong>Air Traffic:</strong> {{ $airport->air_traffic }} <br>
                        <strong>Meteorological:</strong> {{ $airport->meteorology_services }} <br>
                        <strong>Aviation Fuel Depot:</strong> {{ $airport->aviation_fuel_depot }} <br>
                        <strong>Internet:</strong> {{ $airport->internet_services }}
                    </p>
                    <p>
                        <strong>Supplies / Equipment:</strong>
                    </p>
                    {!! $airport->supplies_eqipment !!} {{-- Menggunakan {!! !!} jika kontennya HTML --}}

                    <p><strong>Public Facilities:</strong></p>
                    {!! $airport->public_facilities !!} {{-- Menggunakan {!! !!} jika kontennya HTML --}}

                    <p><strong>Public Transportation:</strong></p>
                    {!! $airport->public_transportation !!} {{-- Menggunakan {!! !!} jika kontennya HTML --}}

                </div>
            </div>
        </div>

        <div class="col-md-5">
             <div class="card">
                <div class="card-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('service')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>
<script>
    const latitude = {{ $airport->latitude }};
    const longitude = {{ $airport->longitude }};
    const embassyName = '{{ $airport->airport_name }}';

    const map = L.map('map', {
        fullscreenControl: true
    }).setView([latitude, longitude], 17);

    // --- Define Tile Layers ---
    // 1. Street Map (OpenStreetMap)
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18 // OSM generally goes up to zoom level 22
    });

    // 2. Satellite Map (Esri World Imagery) - Recommended, no API key needed
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri',
        maxZoom: 18 // Esri World Imagery also typically goes up to zoom level 22
    });

    // Add the satellite layer to the map by default
    satelliteLayer.addTo(map);

    // --- Add Layer Control ---
    // Define the base layers that the user can switch between
   const baseLayers = {
        "Satelit Map": satelliteLayer,
        "Street Map": osmLayer
    };

    // Add the layer control to the map. This will appear in the top-right corner.
    L.control.layers(baseLayers).addTo(map);

    // Add a marker at the embassy's location
    L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup(embassyName) // Display the embassy's name when the marker is clicked
        .openPopup(); // Automatically open the popup when the map loads
</script>
@endpush
