@extends('layouts.master')

@section('title','More Details')

@push('styles')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />
<style>
    #map {
        height: 600px;
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
        <div class="d-flex gap-2 align-items-center">
            <h2 class="fw-bold">{{ $embassy->name_embassiees }}</h2>
        </div>

        <div class="d-flex gap-2 ms-auto">
            <!-- Button 2 -->
            <a href="{{ url('embassiees') }}/{{$embassy->id}}/detail" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees/'.$embassy->id.'/detail') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <!-- Button 5 -->
            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                  <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <!-- Button 7 -->
            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
             <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                <small>Embassies</small>
            </a>
        </div>
    </div>

    <div class="card mb-4 position-relative">
        <div class="card-body" style="padding:0 7px;">
            <small><i>Last Updated {{ $embassy->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('embessydata.edit', $embassy->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header fw-bold"><i class="fas fa-phone"></i> Contact Information</div>
                <div class="card-body">
                <p>
                    <strong>Telephone:</strong> {!! $embassy->telephone ?? '-' !!}
                </p>
                <p>
                    <strong>Fax:</strong> {!! $embassy->fax ?? '-' !!}
                </p>
                    <strong>Email:</strong> {!! $embassy->email ?? '-' !!}
                </p>
                <p>
                    <strong>Website:</strong> {!! $embassy->website !!}
                </p>
                <p>
                    <strong>Latitude:</strong> {{ $embassy->latitude ?? '-' }}
                </p>
                <p>
                    <strong>Longitude:</strong> {{ $embassy->longitude ?? '-' }}
                </p>
                 <p>
                    <strong>Location:</strong>
                    {{ $embassy->location }},
                    {{ $city->city }},
                    {{ $province->provinces_region }}, Thailand
                </p>
            </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                 <div class="card-header fw-bold"><i class="fas fa-map"></i> Map</div>
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
    const latitude = @json($embassy->latitude);
    const longitude = @json($embassy->longitude);
    const embassyName = @json($embassy->name_embassiees);

    const map = L.map('map', {
        fullscreenControl: true
    }).setView([latitude, longitude], 16);

    // --- Define Tile Layers ---
    // 1. Street Map (OpenStreetMap)
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19 // OSM generally goes up to zoom level 19
    });

    // 2. Satellite Map (Esri World Imagery) - Recommended, no API key needed
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
        maxZoom: 19 // Esri World Imagery also typically goes up to zoom level 19
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
