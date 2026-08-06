@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-title', 'Papua New Guinea Crisis Management Tools')

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
        .total-info {
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0,0,0,0.2);
            font-weight: bold;
            margin-left: 10px;
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
        .hospital-legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 5px;
        }
        .hospital-legend-item img {
            width: 30px;
            height: 30px;
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

    /* Classification section */
    .classification {
      display: flex;
      width: 100%;
    }

    .class-column {
      flex: 1;
      text-align: center;

    }
    .class-column:last-child {
      border-right: none;
    }

    .class-header {
      font-weight: 600;
      padding: 0.1rem 0;
    }

    /* Color bars */
    .class-medical-classification {border: none; text-align: center;}
    .class-airport-category {border: none;}
    .class-advanced { border-bottom: 3px solid #0070c0; }
    .class-intermediate { border-bottom: 3px solid #00b050; }
    .class-basic { border-bottom: 3px solid #ffc000; }

    /* Hospital layout */
    .hospital-list {
      display: flex;
      flex-direction: column;
      align-items: center;

    }

    /* For side-by-side classes */
    .hospital-row {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0;
    }

    .hospital-item {
      display: flex;
      align-items: center;
      gap: 0;
      font-size: 0.9rem;
      white-space: nowrap;
    }

    .hospital-icon {
      width: 18px;
      height: 18px;
      border-radius: 3px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    /* Image inside icon box */
    .hospital-icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    /* Airfield icons */
    .category-item img {
      width: 16px;
      height: 16px;
      object-fit: contain;
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

    /* Dropdown opsi (Terrain / Labels) — muncul saat hover, seperti Google */
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

    /* ikon "keluar fullscreen" */
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
    <div class="row" style="background-color: #dfeaf1;">
       <div class="col-md-9">
            <div class="d-flex p-3" style="justify-content: center;">
                <div class="d-flex gap-2">

                <!-- Airport -->
                      <div class="class-column" style="margin-right: 100px;">
                        <div class="class-header class-airport-category">Airfield Classification</div>
                        <div class="airport-list">
                          <div class="hospital-row" style="flex-direction: column;">
                            <!-- Airport row 1 -->
                            <div class="hospital-item">
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
                                  <small>Regional</small>
                              </button>
                            </div>
                            <!-- Airport row 2 -->
                            <div class="hospital-item">
                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                  <small>Civil-Military</small>
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
                      </div>

                      <!-- Medical Facility Legend -->
                      <div style="flex-direction: column;">
                        <!-- Title -->
                        <div>
                            <div class="class-header class-medical-classification">Medical Facility Classification</div>
                        </div>
                        <div style="display: flex; flex-direction: row;">
                            <!-- Advanced -->
                            <div class="class-column">
                              <div class="class-header class-advanced">&nbsp</div>
                              <div class="hospital-list">
                                <div class="hospital-item">
                                  <button class="btn p-1">
                                    Goverment
                                  </button>
                                </div>
                                <div class="hospital-item">
                                    <button class="btn p-1">
                                      Private
                                    </button>
                                  </div>
                              </div>
                            </div>

                             <!-- Advanced -->
                            <div class="class-column">
                              <div class="class-header class-advanced">Advanced</div>
                              <div class="hospital-list">
                                <div class="hospital-item">
                                  <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level66Modal">
                                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:24px; height:24px;">
                                    <small>Regional Hospital (A)</small>
                                  </button>
                                </div>
                                <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level55Modal">
                                      <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:24px; height:24px;">
                                      <small>Large Private Hospital</small>
                                    </button>
                                  </div>
                              </div>
                            </div>

                            <!-- Intermediate -->
                            <div class="class-column">
                              <div class="class-header class-intermediate">Intermediate</div>
                              <div class="hospital-list">
                                  <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level44Modal">
                                      <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:24px; height:24px;">
                                      <small>General Hospital (S,M1)</small>
                                    </button>
                                  </div>
                                  <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level33Modal">
                                      <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:24px; height:24px;">
                                      <small>Medium Private Hospital</small>
                                    </button>
                                  </div>
                              </div>
                            </div>

                            <!-- Basic -->
                            <div class="class-column">
                              <div class="class-header class-basic">Basic</div>
                              <div class="hospital-list">
                                  <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level111Modal">
                                        <img src="https://id.concordreview.com/wp-content/uploads/2026/02/hospital_pin-orange.png" style="width:24px; height:24px;">
                                        <small>Community Hospital (M2, F1, F2, F3) & SHPH</small>
                                    </button>
                                  </div>
                                   <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level11Modal">
                                        <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:24px; height:24px;">
                                        <small>Small Private Hospital & Private Clinic / Polyclinic</small>
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>

                       <div class="class-column" style="margin-left: 50px;">
                        <div class="class-header class-airport-category">POLICE CLASSIFICATION</div>

                        <div class="airport-list">
                            <div class="hospital-row" style="flex-direction: column;">

                                <!-- Baris Atas (3) -->
                                <div class="hospital-item">
                                    <button class="btn p-1">
                                        <img src="{{ asset('images/Layer1.png') }}" style="width:12px; height:12px;">
                                        <small>National Police (HQ)</small>
                                    </button>

                                    <button class="btn p-1">
                                        <img src="{{ asset('images/Layer2.png') }}" style="width:12px; height:12px;">
                                        <small>Regional Police</small>
                                    </button>
                                </div>

                                <!-- Baris Bawah (2) -->
                                <div class="hospital-item">
                                    <button class="btn p-1">
                                         <img src="{{ asset('images/Layer3.png') }}" style="width:12px; height:12px;">
                                        <small>Provincial Police</small>
                                    </button>

                                    <button class="btn p-1">
                                        <img src="{{ asset('images/Layer4.png') }}" style="width:12px; height:12px;">
                                        <small>City Police Station</small>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="d-flex justify-content-end p-3">
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
        </div>
    </div>

    <div class="col-md-12">
        <button class="btn btn-link p-0 fw-bold text-decoration-underline text-dark" data-bs-toggle="modal" data-bs-target="#disclaimerModal">
            <i class="bi bi-info-circle text-primary fs-5"></i>
            <small>Disclaimer</small>
        </button>
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

<div class="modal fade" id="level7Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
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

<div class="modal fade" id="level111Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://id.concordreview.com/wp-content/uploads/2026/02/hospital_pin-orange.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Public Community District Hospital</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                Community Hospitals are district-level public hospitals under Thailand’s Ministry of Public Health (MOPH). They provide primary and secondary care and are classified into F1, F2, and F3 based on capacity and service scope.
                <ul>
                    <li>M2, F1: Large Community Hospital located at the District Level, with a bed capacity  between 60 -150 beds</li>
                    <li>F2: Medium Community Hospital located at the District Level, with a bed capacity between 30 - 60 beds</li>
                    <li>F3: Small Community Hospital located at the District Level, with a bed capacity between 10 – 30 beds</li>
                </ul>
            </p>
            <p class="p-modal text-justify">
                These medical facilities are primary-level district hospitals that provide essential inpatient and emergency services. Their primary function is to deliver accessible basic medical care, manage common conditions, and stabilize urgent cases. They serve as the first inpatient referral point from subdistrict health centers and refer complicated cases to higher-level hospitals. The difference between F1, F2, and F3 reflects size and service scope rather than fundamental roles. Staffed by general physicians and some specialists. Act as referral centers from Subdistrict Health Promoting Hospitals (SHPH)
            </p>
            <p class="p-modal text-justify">
                <a href="{{ route('exurl') }}" target="_blank">Click here to see Thailand Government Health System</a>
            </p>
            <p class="p-modal text-justify">
                <a href="https://anamai.moph.go.th/en/home" target="_blank">MOPH (Ministry of Public Health) website</a>
            </p>

<p class="text-justify">
         <u><b>Note:</b></u> The MOPH website can be difficult to access and may require a VPN.
        </p>
            <h5 class="fw-bold" style="color:#3c8dbc;">
                M2 - Large Community/Upper Secondary Hospital
            </h5>
            <h6 class="fw-bold">
                <b>Overview</b>
            </h6>
            <p class="p-modal text-justify">
               A Large Community / Upper Secondary Hospital (M2) is a district-level hospital providing upper primary and limited secondary care services. It serves as the principal inpatient facility within a district and is responsible for stabilizing emergency and moderately complex cases prior to referral. Its role focuses on improving accessibility to inpatient and emergency services for rural and semi-urban populations while maintaining integration with higher referral centers.
            </p>
            <h6 class="fw-bold">
                <b>Role</b>
            </h6>
            <ul>
                <li>Support district disease surveillance</li>
                <li>Coordinate referral networks</li>
                <li>Provide outreach services to smaller health facilities</li>
                <li>Participate in national health schemes (UCS, SSS, CSMBS)</li>
            </ul>
            <h6 class="fw-bold">
                <b>Clinical Services</b>
            </h6>
        <p class="text-justify">
            <ul>
                <li>Bed Capacity, approximately 60–150 beds</li>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>Internal Medicine</li>
                        <li>General Surgery</li>
                        <li>Orthopedics (basic trauma & fracture care)</li>
                        <li>Obstetrics & Gynecology</li>
                        <li>Pediatrics</li>
                        <li>Anesthesiology (basic service)</li>
                        <li>Emergency Medicine</li>
                        <li>Basic dental services</li>
                    </ul>
                    <p class="text-justify">
                        Some M2 hospitals may have part-time or rotating specialists depending on province.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24-hour Emergency Department</li>
                        <li>Trauma stabilization</li>
                        <li>Basic ICU or high-dependency unit (limited beds)</li>
                        <li>Ambulance referral coordination</li>
                    </ul>
                    <p class="text-justify">
                        Advanced trauma, neurosurgical, or cardiac emergencies are stabilized and referred.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Surgical Services</strong>
                    <ul>
                        <li>General surgical procedures</li>
                        <li>Cesarean sections</li>
                        <li>Basic orthopedic surgery</li>
                        <li>Minor laparoscopic procedures (in some facilities)</li>
                        <li>Minor ENT and urologic procedures</li>
                    </ul>
                    <p class="text-justify">
                        Complex surgeries refer to M1 or regional hospitals.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Facilities</strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>X-ray</li>
                            <li>Ultrasound</li>
                            <li>CT scan (available in some M2, but not universal)</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Laboratory</li>
                        <ul>
                            <li>Standard clinical laboratory (hematology, chemistry)</li>
                            <li>Basic microbiology</li>
                            <li>Blood storage / transfusion capability (limited capacity)</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Pharmacy</li>
                        <ul>
                            <li>Inpatient and outpatient dispensing</li>
                            <li>Essential medicines per National List of Essential Medicines</li>
                        </ul>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Maternal & Child Health</strong>
                    <ul>
                        <li>Antenatal care clinic</li>
                        <li>Labor and delivery unit</li>
                        <li>Cesarean section capability</li>
                        <li>Postnatal ward</li>
                        <li>Pediatric inpatient ward</li>
                        <li>Neonatal stabilization (basic resuscitation)</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> High-risk pregnancies and premature neonates are referred to M1 or regional hospitals.
                    </p>
                </li>
            </ul>
        </p>
        <h5 class="fw-bold" style="color:#3c8dbc;">
            F1 – Large Community Public Hospital
        </h5>
        <h6 class="fw-bold">
            <b>Overview</b>
        </h6>
        <p class="text-justify">
            Under the Ministry of Public Health (MOPH) classification, F1 designates advanced district secondary care, providing upper-tier secondary care within the community hospital category. It sits above F2/F3 hospitals and below M1 provincial hospitals in capability.
        </p>
        <h6 class="fw-bold">
            <b>Role</b>
        </h6>
        <ul>
            <li>Provide Upper-Tier District Secondary Care: Deliver multi-specialty inpatient and outpatient services, managing moderate medical and surgical conditions within district capacity.</li>
            <li>24-Hour Emergency & Stabilization Services: Provide continuous emergency care, trauma stabilization, and limited critical care prior to referral.</li>
            <li>Deliver Essential Surgical & Obstetric Services: Perform general surgery, emergency procedures, and cesarean sections at district level.</li>
            <li>District Referral Hub: Receive patients from Subdistrict Health Promoting Hospitals (SHPH) and refer complex cases to M2 or M1 provincial hospitals.</li>
            <li>Support Public Health Implementation: Conduct disease surveillance, supervise primary care units, and implement national health programs under the Universal Coverage Scheme (UCS).</li>
        </ul>
        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>Bed Capacity, approximately 60 -150 beds</li>
                <li>
                    <strong>Outpatient & Inpatient Care</strong>
                    <ul>
                        <li>General outpatient clinics</li>
                        <li>Medical inpatient ward</li>
                        <li>Surgical inpatient ward</li>
                        <li>Pediatric ward</li>
                        <li>Maternity/postnatal ward</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Core Specialties</strong>
                    <ul>
                        <li>Internal Medicine (general physicians)</li>
                        <li>General Surgery</li>
                        <li>Obstetrics & Gynecology</li>
                        <li>Pediatrics</li>
                        <li>Anesthesiology (basic service)</li>
                        <li>Emergency Medicine</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> Specialist availability may vary by district.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24-hour Emergency Department</li>
                        <li>Trauma stabilization capability</li>
                        <li>Basic ICU or high-dependency unit (limited beds)</li>
                        <li>Ambulance and referral coordination</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> Severe trauma, neurosurgical, cardiac, or highly complex cases are stabilized and referred upward.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Obstetric Services</strong>
                    <ul>
                        <li>General surgical procedures</li>
                        <li>Emergency surgery</li>
                        <li>Cesarean section capability</li>
                        <li>Basic orthopedic procedures</li>
                        <li>Minor laparoscopic procedures (where available)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Facilities</strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>Digital X-ray</li>
                            <li>Ultrasound</li>
                            <li>CT scan (not universal; depends on district capacity)</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Laboratory</li>
                        <ul>
                            <li>Routine hematology and clinical chemistry</li>
                            <li>Basic microbiology</li>
                            <li>Blood storage and transfusion capability (limited scale)</li>
                        </ul>
                    </ul>
                     <ul>
                        <li>Pharmacy</li>
                        <ul>
                            <li>Inpatient and outpatient dispensing</li>
                            <li>Essential medicines list coverage</li>
                        </ul>
                    </ul>
                </li>
            </ul>
        </p>
        <h5 class="fw-bold" style="color:#3c8dbc;">
          F2 - Medium Community Hospital
        </h5>
        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>
        <p class="text-justify">
          Under the Ministry of Public Health (MOPH) classification, an F2 hospital is a medium-sized district community hospital providing core secondary care services. It sits above F3 (small community hospital) and below F1 (large community hospital) in capacity and scope. Receives referrals from Subdistrict Health Promoting Hospitals (SHPH) and refers moderate-to-complex cases to F1, M2, or M1 hospitals.
        </p>
        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <ul>
            <li>District Referral Hub: First inpatient referral point from SHPHs and primary care units.
            <li>Primary & Basic Secondary Care: Provides OPD, IPD, maternal-child health, chronic disease care, and general medical services.
            <li>24-Hour Emergency Stabilization: Manages acute cases and stabilizes patients prior to referral.
            <li>Basic Surgery & Obstetrics: Conducts minor–moderate procedures and routine deliveries; refers complicated cases.
            <li>Essential Diagnostics: Equipped with laboratory, X-ray, and typically ultrasound services.
            <li>Referral Gatekeeper: Screens and transfers complex cases to F1, M1, or Regional (A) hospitals.
        </ul>
        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>Bed Capacity, approximately 30 – 60 beds</li>
                <li>
                    <strong>Outpatient & Inpatient Care</strong>
                    <ul>
                        <li>General outpatient clinic</li>
                        <li>Medical inpatient ward</li>
                        <li>Limited surgical inpatient beds</li>
                        <li>Basic pediatric care</li>
                        <li>Maternity ward</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Core Clinical Coverage</strong>
                    <ul>
                        <li>General physicians (internal medicine at general level)</li>
                        <li>General surgery (basic procedures)</li>
                        <li>Obstetrics & Gynecology</li>
                        <li>Pediatrics</li>
                        <li>Basic anesthesia services</li>
                        <li>Specialists may rotate from provincial hospitals in some districts.</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Acute Care</strong>
                    <ul>
                        <li>24-hour Emergency Department</li>
                        <li>Trauma and acute illness stabilization</li>
                        <li>Basic resuscitation capability</li>
                        <li>Limited or no formal ICU (may have high-dependency beds)</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> Severe trauma, neurosurgical, cardiac, and highly complex cases are stabilized and referred upward.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Obstetric Services</strong>
                    <ul>
                        <li>Basic general surgical procedures</li>
                        <li>Emergency surgery (within district capacity)</li>
                        <li>Cesarean section capability</li>
                        <li>Minor orthopedic procedures</li>
                        <li>Basic laparoscopic procedures (in some facilities)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Facilities</strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>X-ray</li>
                            <li>Ultrasound</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Laboratory</li>
                        <ul>
                            <li>Basic hematology and chemistry</li>
                            <li>Basic microbiology</li>
                            <li>Blood storage for transfusion (limited scale)</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Pharmacy</li>
                        <ul>
                            <li>Inpatient and outpatient dispensing</li>
                            <li>National Essential Medicines List coverage</li>
                        </ul>
                    </ul>
                </li>
            </ul>
        </p>

        <h5 class="fw-bold" style="color:#3c8dbc;">
          F3 – Small Community Public Hospital
        </h5>
        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>
        <p class="text-justify">
          Small district-level hospital under Thailand’s Ministry of Public Health (MOPH), typically serving rural or low-population districts. It delivers structured primary care with limited inpatient and emergency capability, and refers complex cases to F2, F1, M-level, or Regional (A) hospitals.
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <ul>
            <li>Frontline District Hospital: Provides the first level of inpatient hospital care in small or rural districts.</li>
            <li>Primary Care Provider: Delivers comprehensive outpatient care, chronic disease management, maternal-child health, and preventive services.</li>
            <li>Basic Inpatient Care: Manages uncomplicated medical cases requiring short hospital stays.</li>
            <li>Emergency Stabilization Point: Offers 24-hour initial assessment and resuscitation, stabilizing patients prior to referral.</li>
            <li>Minor Procedure Center: Performs basic surgical procedures and wound management; no major surgery capability.</li>
            <li>Referral Gatekeeper: Screens and refers complex medical, surgical, obstetric, and trauma cases to F2, F1, M-level, or Regional (A) hospitals.</li>
            <li>Public Health Node: Supports immunization programs, disease control, and coordination with Subdistrict Health Promoting Hospitals (SHPH) within the district network.</li>
        </ul>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <ul>
            <li>Bed Capacity, approximately 10 – 30 beds</li>
            <li>Outpatient Department (OPD): General medical consultations, chronic disease management (e.g., hypertension, diabetes), minor illness and injury care.</li>
            <li>Inpatient Ward (Limited Beds): Small-capacity ward (typically ~10–30 beds) for uncomplicated medical cases and short stays.</li>
            <li>24-Hour Basic Emergency Unit: Initial assessment, resuscitation, and stabilization before referral (no advanced trauma or ICU capability).</li>
            <li>Maternal & Child Health Services: Antenatal care, post-natal care, immunization, well-baby clinic (deliveries are limited or referred depends on staffing and capacity).</li>
            <li>Minor Procedures: Basic wound care, suture, abscess drainage, and other minor surgical procedures (no major surgery).</li>
            <li>Basic Diagnostics: Essential laboratory testing and plain X-ray (in many facilities); ultrasound availability varies (no CT/MRI).</li>
            <li>Pharmacy Services: Essential medicines under the National List of Essential Medicines.</li>
            <li>Public Health & Preventive Services: Health promotion, vaccination programs, disease surveillance, and coordination with Subdistrict Health Promoting Hospitals (SHPH).</li>
        </ul>

        <h5 class="fw-bold" style="color:#3c8dbc;">
          Subdistrict Health Promoting Hospitals/Center – SHPH (Primary Care Units – No Inpatient Service)
        </h5>
        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>
        <p class="text-justify">
          A Public Subdistrict Health Center are the primary care frontline facilities at tambon (subdistrict) level under the Ministry of Public Health (MOPH) within Thailand’s public health system. It functions as the first contact point for community healthcare, focusing on preventive medicine, chronic disease management, maternal and child health, vaccination programs, and health promotion activities. These facilities do not provide inpatient services and refer patients requiring hospitalization to Community Hospitals (F1, F2, F3).
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <ul>
            <li>Frontline Primary Care Provider: First point of contact for community-based healthcare at subdistrict (tambon) level.</li>
            <li>Health Promotion & Prevention Hub: Leads immunization, NCD screening, maternal-child health, nutrition, and lifestyle programs.</li>
            <li>Basic Curative Care Provider: Manages minor illnesses, common conditions, and routine chronic disease follow-up (e.g., hypertension, diabetes).</li>
            <li>Community Outreach & Home Care Unit: Manage home visits, elderly care, palliative support, and rehabilitation follow-up.</li>
            <li>Public Health Surveillance Node: Monitors communicable diseases, reports outbreaks, and supports national health campaigns.</li>
            <li>Referral Gatekeeper: Screens and refers patients requiring inpatient or specialist care to F3/F2/F1 district hospitals.</li>
            <li>District Health Network Support: Coordinates with community hospitals and local authorities to implement Ministry of Public Health programs.</li>
        </ul>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <ul>
            <li>No inpatient services</li>
            <li>Primary Care Clinic / OPD Room – General consultation and treatment area</li>
            <li>Basic Treatment & Procedure Room – Wound care, injections, simple procedures</li>
            <li>Maternal & Child Health Room – ANC, postnatal care, child development services</li>
            <li>Immunization Area – Vaccine storage (cold chain) and administration</li>
            <li>Pharmacy Corner – Essential medicines (limited formulary)</li>
            <li>Basic Laboratory Capability – Simple tests (e.g., blood sugar, urine dipstick, malaria smear in endemic areas)</li>
        </ul>
        <p class="text-justify">
          <u>Note:</u> No surgery, no advanced imaging.
        </p>
      </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level11Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Small Private Hospital</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>
        <p class="text-justify">
          A Small Private Hospital provides basic inpatient and emergency care services in smaller cities or districts. It functions primarily as a primary-to-basic-secondary care provider and focuses on general medical treatment, minor surgery, and short-term admissions. Complex cases are typically referred to larger hospitals.
        </p>
        <p class="text-justify">
          <a href="https://anamai.moph.go.th/en/home" target="_blank">MOPH (Ministry of Public Health) website</a>
        </p>

        <p class="text-justify">
         <u><b>Note:</b></u> The MOPH website can be difficult to access and may require a VPN.
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <ul>
            <li>Provides local inpatient and outpatient medical care</li>
            <li>Manages common conditions and short-stay admissions</li>
            <li>Offers basic 24-hour emergency stabilization</li>
            <li>Performs minor surgery and elective procedures</li>
            <li>Supplies essential diagnostics (lab, X-ray, often ultrasound)</li>
            <li>Serves insured, corporate, and self-pay patients</li>
            <li>Refers complex or high-acuity cases to larger hospitals</li>
        </ul>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <ul>
            <li>Bed Capacity, approximately 10–50 beds</li>
            <li>Basic inpatient wards</li>
            <li>Minor surgical procedures</li>
            <li>Emergency stabilization services</li>
            <li>Basic imaging and laboratory</li>
        </ul>

        <h5 class="fw-bold" style="color:#3c8dbc;">
          Private Clinic / Polyclinic (No inpatient Service)
        </h5>
        <p class="text-justify">
          A Private Clinic or Polyclinic is an outpatient-only medical facility delivering general practice or specialist consultations. It serves as a direct-access private primary care provider and does not offer inpatient admission.
        </p>
        <p class="text-justify">
          <a href="https://anamai.moph.go.th/en/home" target="_blank">MOPH (Ministry of Public Health) website</a>
        </p>

 <p class="text-justify">
         <u><b>Note:</b></u> The MOPH website can be difficult to access and may require a VPN.
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <ul>
            <li>Provides outpatient-only primary and specialty consultations</li>
            <li>Manages minor illnesses, chronic disease follow-up, and preventive care</li>
            <li>Performs basic procedures (wound care, injections, minor dermatologic or GP procedures)</li>
            <li>Offers limited diagnostics (basic lab tests; imaging typically outsourced)</li>
            <li>Delivers occupational health and medical check-up services</li>
            <li>Serves walk-in, insured, and self-pay patients</li>
            <li>Refers patients requiring inpatient or advanced care to hospitals</li>
        </ul>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <ul>
            <li>No inpatient services</li>
            <li>Consultation rooms</li>
            <li>Basic diagnostics</li>
            <li>Minor procedures</li>
            <li>Pharmacy services (if licensed)</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level22Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-orange.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 2</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>Community Health Post - Health Sub Center (CHP)</b></p>
        <p class="p-modal">Primary health, ambulatory care, and short stay inpatient and maternity care at the local rural / remote community level, with a minimum of six (6) health workers to ensure safe 24-hour care and treatment.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level33Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Medium Private Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>
        <p class="text-justify">
          A Medium Private Hospital provides structured secondary care services within urban or semi-urban settings. It manages common inpatient and surgical cases and offers specialist consultations. Its role is to provide accessible private-sector hospital care for routine and moderately complex conditions, referring highly specialized cases to larger tertiary hospitals when necessary.
        </p>

        <p class="text-justify">
          <a href="https://anamai.moph.go.th/en/home" target="_blank">MOPH (Ministry of Public Health) website</a>
        </p>

 <p class="text-justify">
         <u><b>Note:</b></u> The MOPH website can be difficult to access and may require a VPN.
        </p>

        <h6 class="fw-bold">
          <b>Roles</b>
        </h6>
        <p class="text-justify">
          <ul>
                <li>Deliver Secondary Care: Provide multi-specialty outpatient and inpatient medical and surgical services.</li>
                <li>Provide 24-Hour Emergency & Limited Critical Care: Operate emergency services with advanced life support and limited ICU capacity; stabilize and refer complex cases.</li>
                <li>Conduct Surgical & Obstetric Services: Perform general, orthopedic, minimally invasive, and cesarean procedures within facility capability.</li>
                <li>Maintain Diagnostic & Support Services: Offer imaging (X-ray, ultrasound, often CT), laboratory testing, pharmacy, and limited blood services.</li>
                <li>Operate Within a Market-Based System: Serve insured and self-paying patients while complementing and reducing demand on the public hospital network.</li>
          </ul>
        </p>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>Bed Capacity, approximately 50–250 beds</li>
                <li>
                    <strong>Outpatient Services</strong>
                    <ul>
                        <li>General practice clinics</li>
                        <li>Specialist clinics (internal medicine, surgery, orthopedics, OB-GYN, pediatrics)</li>
                        <li>Preventive health check programs</li>
                        <li>Chronic disease management</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Inpatient Services</strong>
                    <ul>
                        <li>Medical and surgical wards</li>
                        <li>Private and semi-private rooms</li>
                        <li>Short-stay admission units</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24-hour Emergency Department</li>
                        <li>Advanced life support capability</li>
                        <li>Intensive Care Unit (limited bed numbers compared to large tertiary hospitals)</li>
                        <li>Ambulance services</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> Complex trauma or highly specialized cases are referred to large tertiary centers.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Procedural Capacity</strong>
                    <ul>
                        <li>General surgery</li>
                        <li>Orthopedic procedures</li>
                        <li>Obstetric surgery (C-section)</li>
                        <li>Laparoscopic surgery</li>
                        <li>Endoscopy (GI procedures)</li>
                        <li>Minor cosmetic procedures (in some hospitals)</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> Highly complex cardiothoracic or neurosurgical operations are usually not performed unless the hospital is part of a larger network.
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Facilities</strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>Digital X-ray</li>
                            <li>Ultrasound</li>
                            <li>CT scan (common)</li>
                            <li>MRI (available in some medium private hospitals)</li>
                        </ul>
                        <li>Laboratory</li>
                        <ul>
                            <li>Full routine clinical laboratory</li>
                            <li>Blood storage and transfusion capability (limited scale)</li>
                        </ul>
                        <li>Pharmacy</li>
                        <ul>
                            <li>On-site pharmacy</li>
                            <li>Broad branded and generic drug availability</li>
                        </ul>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Maternal & Child Services</strong>
                    <ul>
                        <li>Antenatal clinic</li>
                        <li>Delivery suite</li>
                        <li>Cesarean section capability</li>
                        <li>Nursery and basic neonatal stabilization</li>
                        <li>Pediatric inpatient care</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> High-risk neonatal ICU services may be limited depending on facility size.
                    </p>
                </li>
            </ul>
        </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level44Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">General Hospital</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       <div class="modal-body">

        <p class="text-justify">
         In Thailand’s public health system, a General Hospital is a provincial-level secondary care hospital under the Ministry of Public Health (MOPH), typically classified as S, M1 or M2. It provides comprehensive secondary medical services, broader than community hospitals (F-level), and functions as the main referral hospital within a province (or large district cluster).
        </p>

        <p class="text-justify">
          <a href="{{ route('exurl') }}" target="_blank">Click here to see Thailand Government Health System</a>
        </p>

        <p class="text-justify">
          <a href="https://anamai.moph.go.th/en/home" target="_blank">MOPH (Ministry of Public Health) website</a>
        </p>

 <p class="text-justify">
         <u><b>Note:</b></u> The MOPH website can be difficult to access and may require a VPN.
        </p>

        <h5 class="fw-bold" style="color:#3c8dbc;">
          S - Standard Level Hospital
        </h5>

        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>

        <p class="text-justify">
         A Standard Level Public Hospital (Category S) is a provincial secondary-care hospital providing a broad range of specialist services. It functions as the main intermediate referral hospital within a province for cases originating from community hospitals. While capable of managing most secondary-level conditions, it refers to highly complex tertiary cases to Advanced Level hospitals. It plays a central role in emergency care, inpatient management, and surgical services at the provincial level.
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>Serve as training centers for medical interns and residents</li>
                <li>Provide specialist outreach to M1 and M2 hospitals</li>
                <li>Lead regional disease control and outbreak response</li>
                <li>Support advanced emergency medical referral systems</li>
            </ul>
        </p>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>Bed Capacity, approximately 200–500 beds</li>
                <li>
                    <strong>Core and Advanced Specialties </strong>
                    <ul>
                        <li>Internal Medicine (with subspecialties: cardiology, pulmonology, nephrology, endocrinology, etc.)</li>
                        <li>General Surgery</li>
                        <li>Orthopedic Surgery</li>
                        <li>Neurosurgery</li>
                        <li>Obstetrics & Gynecology</li>
                        <li>Pediatrics (with subspecialties)</li>
                        <li>Anesthesiology</li>
                        <li>Emergency Medicine</li>
                        <li>Psychiatry</li>
                        <li>ENT (Otolaryngology)</li>
                        <li>Ophthalmology</li>
                        <li>Urology</li>
                        <li>Dermatology</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Advanced Tertiary Services </strong>
                    <ul>
                        <li>Cardiac catheterization laboratory</li>
                        <li>Advanced trauma services</li>
                        <li>Comprehensive ICU services (medical, surgical, pediatric, neonatal)</li>
                        <li>Neonatal ICU (higher-level capability)</li>
                        <li>Oncology services (chemotherapy; some provide radiotherapy)</li>
                        <li>Dialysis services</li>
                        <li>Endoscopy units</li>
                        <li>Advanced laparoscopic surgery</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Procedural Capacity </strong>
                    <ul>
                        <li>Major elective and emergency surgeries</li>
                        <li>Neurosurgical procedures</li>
                        <li>Complex orthopedic surgery</li>
                        <li>High-risk obstetric surgery</li>
                        <li>Thoracic and some cardiovascular surgery (varies by region)</li>
                    </ul>
                    <p class="text-justify">
                        <u>Note:</u> Highly specialized services (e.g., organ transplantation, highly complex cardiac surgery) are usually referred to university/teaching hospitals
                    </p>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Infrastructure </strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>X-ray</li>
                            <li>Ultrasound</li>
                            <li>CT Scan</li>
                            <li>MRI</li>
                            <li>Mammography</li>
                            <li>Interventional radiology (in many centers)</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Laboratory</li>
                        <ul>
                            <li>Full clinical pathology</li>
                            <li>Microbiology</li>
                            <li>Blood bank with component therapy</li>
                            <li>Specialized testing (e.g., immunology, advanced chemistry)</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Pharmacy</li>
                        <ul>
                            <li>Comprehensive formulary</li>
                            <li>Specialized oncology and critical care medications</li>
                        </ul>
                    </ul>
                </li>
            </ul>
        </p>

        <h5 class="fw-bold" style="color:#3c8dbc;">
          M1 - Mid-Level General Hospital
        </h5>
        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>
        <p class="text-justify">
         A Mid-Level General Public Hospital (Category M1) provides structured secondary care in medium-sized provinces or districts. It serves as an intermediate referral center between community hospitals and higher-level standard/regional hospitals under the Ministry of Public Health system.
        </p>
        <p class="text-justify">
         M1 hospitals deliver comprehensive multi-specialty services, including inpatient and outpatient care, 24-hour emergency services, ICU capability, operative services, diagnostic imaging (including CT), and full maternal and child health care. They function as the primary provincial referral hospital, managing routine medical and surgical admissions while stabilizing more complex or critical cases prior to onward referral to higher-tier facilities.
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>Support district hospitals technically</li>
                <li>Conduct disease surveillance reporting</li>
                <li>Participate in outbreak response</li>
                <li>Provide specialist outreach to community hospitals</li>
                <li>Serve as training sites for medical interns and nurses (in some provinces)</li>
            </ul>
        </p>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>Bed Capacity, approximately 120–300 beds</li>
                <li>
                    <strong>Core and Advanced Specialties </strong>
                    <ul>
                        <li>Internal Medicine</li>
                        <li>General Surgery</li>
                        <li>Orthopedics</li>
                        <li>Obstetrics & Gynecology</li>
                        <li>Pediatrics</li>
                        <li>Anesthesiology</li>
                        <li>Emergency Medicine</li>
                        <li>Psychiatry</li>
                        <li>ENT (Otolaryngology)</li>
                        <li>Ophthalmology</li>
                        <li>Some M1 hospitals may also have</li>
                        <li>Cardiology (basic non-invasive)</li>
                        <li>Neurology (consultative)</li>
                        <li>Urology</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care </strong>
                    <ul>
                        <li>24-hour Emergency Department</li>
                        <li>Trauma stabilization capability</li>
                        <li>Intensive Care Unit (ICU)</li>
                        <li>Neonatal ICU (Level II in most cases)</li>
                        <li>Ambulance & referral coordination</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical Services </strong>
                    <ul>
                        <li>General surgery (elective & emergency)</li>
                        <li>Orthopedic surgery</li>
                        <li>Cesarean section</li>
                        <li>Basic laparoscopic procedures</li>
                        <li>Minor urological & ENT procedures</li>
                        <li>Highly complex surgeries (e.g., open heart, advanced neurosurgery) are referred upward.</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Facilities </strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>X-ray</li>
                            <li>Ultrasound</li>
                            <li>CT Scan (standard in most M1)</li>
                            <li>Mammography (in many provinces)</li>
                            <li>MRI may exist in some M1 hospitals but is not universal.</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Laboratory Services</li>
                        <ul>
                            <li>Full clinical pathology lab</li>
                            <li>Hematology</li>
                            <li>Clinical chemistry</li>
                            <li>Microbiology</li>
                            <li>Blood bank (standard transfusion service)</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Pharmacy</li>
                        <ul>
                            <li>Inpatient & outpatient dispensing</li>
                            <li>Essential medicines list (National Drug List of Thailand)</li>
                        </ul>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Maternal & Child Health Services </strong>
                    <ul>
                        <li>Antenatal clinic</li>
                        <li>Delivery room & operating theatre for C-section</li>
                        <li>Postnatal ward</li>
                        <li>Pediatric inpatient ward</li>
                        <li>Immunization services</li>
                        <li>Neonatal resuscitation</li>
                    </ul>
                </li>
            </ul>
        </p>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level55Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Large Private Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>

        <p class="text-justify">
         A Large Private Hospital in Thailand is a high-capacity, corporate or network-affiliated facility delivering advanced tertiary-level services within the private sector and operates under license from the Ministry of Public Health. It provides comprehensive specialist and subspecialist services comparable to Advanced (category A) Level public hospitals. These hospitals typically serve urban populations and international patients, offering direct access without referral restrictions. They function as major private-sector healthcare hubs and often maintain international accreditation standards.
        </p>

        <p class="text-justify">
            <a href="https://anamai.moph.go.th/en/home" target="_blank">MOPH (Ministry of Public Health) website</a>
        </p>

<p class="text-justify">
         <u><b>Note:</b></u> The MOPH website can be difficult to access and may require a VPN.
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <p class="text-justify">
             <ul>
                <li>Deliver Advanced Tertiary Care: Provide comprehensive multi-specialty and subspecialty medical and surgical services for complex conditions.</li>
                <li>Operate Full Emergency & Critical Care Services: Maintain 24-hour emergency departments and fully equipped ICUs, managing high-acuity cases within institutional capability.</li>
                <li>Provide Advanced Surgical & Interventional Procedures: Conduct major surgeries, minimally invasive and cardiac interventions, and other complex procedures.</li>
                <li>Serve Insured and International Markets: Deliver care to privately insured, corporate, expatriate, and medical tourism patients within a corporate healthcare framework.</li>
                <li>Complement the Public Health System: Absorb tertiary care demand and refer ultra-specialized cases when required, supporting overall national healthcare capacity.</li>
            </ul>
        </p>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>
                    <strong>Bed Capacity </strong>
                    <ul>
                        <li>> 250 beds (often 300–600+)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Comprehensive Clinical Specialties </strong>
                    <ul>
                        <li>Internal Medicine with subspecialties (cardiology, endocrinology, neurology, etc.)</li>
                        <li>General and specialized surgery</li>
                        <li>Orthopedics and spine surgery</li>
                        <li>Obstetrics & Gynecology</li>
                        <li>Pediatrics (often with NICU capability)</li>
                        <li>Neurosurgery (in many facilities)</li>
                        <li>Cardiothoracic surgery (in major centers)</li>
                        <li>Urology, ENT, Ophthalmology, Dermatology</li>
                        <li>Oncology services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24-hour Emergency Department</li>
                        <li>Advanced trauma and resuscitation capability</li>
                        <li>Medical, surgical, and cardiac ICUs</li>
                        <li>Neonatal ICU (in major facilities)</li>
                        <li>Inter-hospital transfer coordination</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Interventional Services</strong>
                    <ul>
                        <li>Major elective and emergency surgeries</li>
                        <li>Minimally invasive and robotic-assisted surgery (in leading hospitals)</li>
                        <li>Cardiac catheterization and interventional cardiology</li>
                        <li>Advanced endoscopy services</li>
                        <li>Complex orthopedic and spine procedures</li>
                    </ul>
                </li>

                <p class="text-justify">
                    <u>Note:</u> Some large private hospitals perform highly specialized procedures such as organ transplantation, depending on licensing and specialist availability.
                </p>

                <li class="mt-2">
                    <strong>Diagnostic & Therapeutic Infrastructure</strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>Digital X-ray</li>
                            <li>Ultrasound</li>
                            <li>Multi-slice CT</li>
                            <li>MRI</li>
                            <li>Interventional radiology</li>
                            <li>Mammography</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Laboratory</li>
                        <ul>
                            <li>Comprehensive pathology services</li>
                            <li>Blood bank with component therapy</li>
                            <li>Oncology</li>
                            <li>Chemotherapy</li>
                            <li>Radiotherapy (in selected hospitals)</li>
                            <li>Multidisciplinary cancer centers</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Renal Services</li>
                        <ul>
                            <li>Hemodialysis and related renal therapy</li>
                        </ul>
                    </ul>
                </li>
            </ul>
        </p>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level66Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">A - Regional Hospital / Advanced Level Referral Hospital</h5>
        </div>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <h6 class="fw-bold">
          <b>Overview</b>
        </h6>

        <p class="text-justify">
          A Regional Hospital is a tertiary-level regional referral hospital under the Ministry of Public Health (MOPH) and the highest-tier public hospital within Thailand’s provincial health system. Categorized as an “A” level medical provider, it delivers advanced specialty and subspecialty care, complex surgery, high-acuity critical care, and advanced diagnostic services for multiple provinces within the MOPH network. These hospitals manage complex medical and surgical cases referred from lower-level facilities, serve as the principal clinical authority within their geographic region, commonly function as teaching and training institutions for medical professionals, and support public health policy implementation at the regional level.
        </p>

        <p class="text-justify">
          <a href="{{ route('exurl') }}" target="_blank">Click here to see Thailand Government Health System</a>
        </p>

        <p class="text-justify">
          <a href="https://anamai.moph.go.th/en/home" target="_blank">MOPH (Ministry of Public Health) website</a>
        </p>

        <p class="text-justify">
         <u><b>Note:</b></u> The MOPH website can be difficult to access and may require a VPN.
        </p>

        <h6 class="fw-bold">
          <b>Role</b>
        </h6>
        <p class="text-justify">
             <ul>
                <li>Serves as the highest regional referral authority within the MOPH network</li>
                <li>Provides specialist outreach and technical supervision to S and M1 hospitals</li>
                <li>Functions as a major training center for specialists, residents, and allied health professionals (often affiliated with medical schools)</li>
                <li>Leads regional outbreak response and advanced public health coordination</li>
            </ul>
        </p>

        <h6 class="fw-bold">
          <b>Clinical Services</b>
        </h6>
        <p class="text-justify">
            <ul>
                <li>
                    <strong>Bed Capacity </strong>
                    <ul>
                        <li>≥ 500 beds (often 700–1,200+)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Comprehensive Clinical Specialties </strong>
                    <ul>
                        <li>Internal Medicine with subspecialties (cardiology, pulmonology, nephrology, endocrinology, infectious diseases, etc.)</li>
                        <li>General and specialized surgery</li>
                        <li>Orthopedics and trauma surgery</li>
                        <li>Neurosurgery</li>
                        <li>Cardiothoracic surgery (in most category A hospitals)</li>
                        <li>Obstetrics & Gynecology (high-risk maternal care)</li>
                        <li>Pediatrics with subspecialties</li>
                        <li>Anesthesiology and critical care medicine</li>
                        <li>Emergency medicine and trauma services</li>
                        <li>Psychiatry</li>
                        <li>ENT, Ophthalmology, Urology, Dermatology</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Advanced Emergency & Critical Care</strong>
                    <ul>
                        <li>24-hour advanced emergency department</li>
                        <li>Designated trauma center capability</li>
                        <li>Multiple specialized ICUs (medical, surgical, cardiac, pediatric, neonatal)</li>
                        <li>Advanced life support and inter-hospital transport coordination</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Interventional Capacity</strong>
                    <ul>
                        <li>Major elective and emergency surgeries</li>
                        <li>Complex neurosurgical and spinal procedures</li>
                        <li>Advanced cardiovascular interventions (cardiac catheterization, interventional cardiology)</li>
                        <li>Thoracic and complex abdominal surgery</li>
                        <li>Advanced minimally invasive and laparoscopic platforms</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Therapeutic Infrastructure</strong>
                    <ul>
                        <li>Imaging</li>
                        <ul>
                            <li>Digital X-ray</li>
                            <li>Ultrasound</li>
                            <li>CT (multi-slice)</li>
                            <li>MRI</li>
                            <li>Interventional radiology</li>
                            <li>Mammography</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Laboratory & Blood Services</li>
                        <ul>
                            <li>Full clinical pathology</li>
                            <li>Advanced microbiology and immunology</li>
                            <li>Comprehensive blood bank with component therapy</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Oncology Services</li>
                        <ul>
                            <li>Chemotherapy</li>
                            <li>Radiotherapy (in most category A hospitals)</li>
                            <li>Oncology surgery and multidisciplinary cancer care</li>
                        </ul>
                    </ul>
                    <ul>
                        <li>Renal Services</li>
                        <ul>
                            <li>Hemodialysis and renal replacement therapy</li>
                        </ul>
                    </ul>
                </li>
            </ul>
        </p>

      </div>
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
document.addEventListener('DOMContentLoaded', function () {

    const provinceSelect = document.querySelector('#provinceSelect .select-input');
    const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');
    const provinceSearch = document.getElementById('provinceSearch');
    const provinceSearchInput = document.getElementById('provinceSearchInput');

    // buka/tutup dropdown
    provinceSelect.addEventListener('click', () => {
        provinceDropdown.classList.toggle('show');
    });

    // tutup saat klik luar
    document.addEventListener('click', (e) => {
        if (!document.getElementById('provinceSelect').contains(e.target)) {
            provinceDropdown.classList.remove('show');
        }
    });

    // filter pencarian
    provinceSearchInput.addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();

        document.querySelectorAll('#provinceList li').forEach(li => {
            const text = li.textContent.toLowerCase();

            li.style.display = text.includes(keyword)
                ? ''
                : 'none';
        });
    });

    // update text input ketika checkbox dipilih
    document.addEventListener('change', function(e) {

        if (e.target.classList.contains('province-checkbox')) {

            const selected = [...document.querySelectorAll('.province-checkbox:checked')]
                .map(cb => cb.parentElement.textContent.trim());

            if (selected.length === 0) {
                provinceSearch.value = '';
                provinceSearch.placeholder = '🔍 Select Region / State';
            } else if (selected.length <= 2) {
                provinceSearch.value = selected.join(', ');
            } else {
                provinceSearch.value = selected.length + ' Region / State Selected';
            }
        }
    });

});
</script>

<script>
    // --- Map Initialization ---
    // zoomControl & attributionControl dimatikan: atribusi + kontrol sudah
    // disediakan basemap Google, sama seperti dashboard-ref
    const map = L.map('map', {
        zoomControl: false,
        attributionControl: false
    }).setView([15.561656906765931, 100.85374832882776], 6);

    // --- Basemap: Google (roadmap / terrain / satellite / hybrid) ---
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

    // --- Map Type Control (Google style: Map | Satellite) ---
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

            const btns      = wrap.querySelectorAll('.gmap-type-btn');
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

    // --- Camera / Pan Control (Google style, kanan bawah) ---
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

    // Fullscreen di kanan atas (default Google), di atas panel Filter & Radius
    const fullscreenControl = L.control.fullscreen({ position: 'topright' });
    map.addControl(fullscreenControl);
    L.DomUtil.addClass(fullscreenControl.getContainer(), 'gmap-fullscreen');

    const style = document.createElement('style');
    style.textContent = `
        .leaflet-top.leaflet-left .gmap-type-wrap {
            margin: 10px 0 0 10px !important;
        }
        `;
    document.head.appendChild(style);

    // --- Global States ---
    let airportMarkers = L.featureGroup().addTo(map);
    let hospitalMarkers = L.featureGroup().addTo(map);
    let policeMarkers = L.featureGroup().addTo(map);
    let embassyMarkers = L.featureGroup().addTo(map);
    let radiusCircle = null;
    let radiusPinMarker = null;
    let lastClickedLocation = null;
    let drawnPolygonGeoJSON = null;
    let totalHospitals = 0;
    let totalAirports = 0;
    let totalPolice = 0;
    let totalEmbassies = 0;

    // --- Custom "topcenter" control corner ---
    map._controlCorners['topcenter'] = L.DomUtil.create(
        'div', 'leaflet-top leaflet-center', map._controlContainer
    );

    // --- Directions (in-map routing) ---
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
        clearRouteLayer();
        clearRouteBtn.style.display = 'none';
    });

    const ClearRouteControl = L.Control.extend({
        options: { position: 'topcenter' },
        onAdd: function () {
            L.DomEvent.disableClickPropagation(clearRouteBtn);
            return clearRouteBtn;
        }
    });
    map.addControl(new ClearRouteControl());

    // --- Nearby Category Bar (Google Maps style) ---
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
                // toggle off
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

        // Color map per category
        const iconColors = {
            lodging:    '#1a73e8',
            restaurant: '#e53935',
            pharmacy:   '#2e7d32',
            atm:        '#f57c00',
            parking:    '#1565c0',
            cafe:       '#6d4c41',
            hospital:   '#c62828',
        };
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

        const searchRadiusM  = 20000; // 20 km
        const searchRadiusKm = searchRadiusM / 1000;

        service.nearbySearch({ location: center, radius: searchRadiusM, type }, (results, status) => {
            if (status !== google.maps.places.PlacesServiceStatus.OK) {
                if (status === 'ZERO_RESULTS') {
                    alert(`No ${label.toLowerCase()} found within ${searchRadiusKm} km.`);
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

                // --- Draw route polyline + endpoint markers ---
                const path = (route.overview_path || []).map(p => [p.lat(), p.lng()]);
                if (path.length) {
                    L.polyline(path, {
                        color: '#1a73e8', weight: 5, opacity: 0.85
                    }).addTo(routeLayer);
                }
                L.marker([leg.start_location.lat(), leg.start_location.lng()])
                    .bindPopup('Start').addTo(routeLayer);
                L.marker([leg.end_location.lat(), leg.end_location.lng()])
                    .bindPopup(destName || 'Destination').addTo(routeLayer);

                if (routeLayer.getLayers().length) {
                    map.fitBounds(routeLayer.getBounds(), { padding: [40, 40] });
                }

                clearRouteBtn.style.display = 'inline-block';

                // --- Populate Route Panel ---
                const panel = document.getElementById('routePanel');
                document.getElementById('routePanelTitle').textContent = destName || 'Destination';
                document.getElementById('routeDistance').textContent  = leg.distance.text;
                document.getElementById('routeDuration').textContent  = leg.duration.text;

                const stepsEl = document.getElementById('routeSteps');
                stepsEl.innerHTML = leg.steps.map((step, i) => {
                    const raw = (step.html_instructions || step.instructions || '');
                    const instruction = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                    if (!instruction) return ''; // skip steps with no text
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

    // --- Polygon Draw (Custom Point-by-Point) ---
    let isDrawingPolygon = false;
    let polygonLatLngs = [];
    let activePolygon = null;
    let activePolyline = null;
    let cursorPolyline = null;
    let startMarker = null;

    const drawButton = document.createElement('div');
    drawButton.innerHTML = '⬟';
    drawButton.style.backgroundColor = 'white';
    drawButton.style.border = '2px solid rgba(0,0,0,0.2)';
    drawButton.style.borderRadius = '4px';
    drawButton.style.width = '34px';
    drawButton.style.height = '34px';
    drawButton.style.textAlign = 'center';
    drawButton.style.lineHeight = '30px';
    drawButton.style.fontSize = '18px';
    drawButton.style.cursor = 'pointer';
    drawButton.style.margin = '10px';
    drawButton.title = 'Draw Polygon (Click point by point, click starting point to finish)';

    const clearButton = document.createElement('div');
    clearButton.innerHTML = '🗑️';
    clearButton.style.backgroundColor = 'white';
    clearButton.style.border = '2px solid rgba(0,0,0,0.2)';
    clearButton.style.borderRadius = '4px';
    clearButton.style.width = '34px';
    clearButton.style.height = '34px';
    clearButton.style.textAlign = 'center';
    clearButton.style.lineHeight = '30px';
    clearButton.style.fontSize = '16px';
    clearButton.style.cursor = 'pointer';
    clearButton.style.margin = '10px 0';
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
                color: '#0000FF',
                opacity: 0.8,
                weight: 3,
                interactive: false
            }).addTo(map);
            cursorPolyline = L.polyline([], {
                color: '#0000FF',
                opacity: 0.5,
                weight: 3,
                interactive: false
            }).addTo(map);
            startMarker = null;
            drawnPolygonGeoJSON = null;
        } else {
            finishPolygon();
        }
    });

    map.on('click', e => {
        if (!isDrawingPolygon) return;
        polygonLatLngs.push(e.latlng);
        activePolyline.setLatLngs(polygonLatLngs);

        if (polygonLatLngs.length === 1) {
            startMarker = L.circleMarker(e.latlng, {
                radius: 6,
                fillColor: '#FFFFFF',
                fillOpacity: 1,
                color: '#0000FF',
                weight: 2
            }).addTo(map);
            startMarker.on('click', () => {
                if (isDrawingPolygon) finishPolygon();
            });
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
                color: '#0000FF',
                opacity: 0.8,
                weight: 3,
                fillColor: '#0000FF',
                fillOpacity: 0.2
            }).addTo(map);

            const coordinates = polygonLatLngs.map(p => [p.lng, p.lat]);
            coordinates.push([polygonLatLngs[0].lng, polygonLatLngs[0].lat]); // Close polygon

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
                    await refreshCurrentFilters();
                }
            };

            activePolygon.on('edit', updatePolygonFilter);

            await refreshCurrentFilters();
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
        await refreshCurrentFilters();
    });

    // --- Update Radius ---
    function updateRadiusCircleAndPin(radius = 0) {
        if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }

        if (radius > 0 && lastClickedLocation) {
            radiusCircle = L.circle(lastClickedLocation, {
                color: 'red', fillColor: '#f03', fillOpacity: 0.3, radius: radius * 1000
            }).addTo(map);
        }
    }

    // Red pin marker for searched location (separate from radius circle)
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

    // Enable/disable radius section based on whether location is set
    function setRadiusSectionEnabled(enabled) {
        const section = document.getElementById('radiusSection');
        if (!section) return;
        section.style.opacity = enabled ? '1' : '0.4';
        section.style.pointerEvents = enabled ? 'auto' : 'none';
    }

    map.on('click', e => {
        if (isDrawingPolygon) return; // klik dipakai untuk menggambar polygon
        lastClickedLocation = { lat: e.latlng.lat, lng: e.latlng.lng };
        const radius = parseInt(document.querySelector('#radiusRangeMap')?.value || 0);
        document.querySelector('#radiusValueMap').textContent = radius;
        placeLocationPin(lastClickedLocation);
        setRadiusSectionEnabled(true);
        updateRadiusCircleAndPin(radius);
        categoryBar.style.display = 'flex';
    });

    // --- Init Location Search — Google Places Autocomplete ---
    // .pac-container is repositioned to position:fixed via MutationObserver
    // to bypass the map/panel container overflow:hidden clipping.
    function initLocationSearch() {
        const input = document.getElementById('locationSearchMap');
        if (!input) {
            setTimeout(initLocationSearch, 300);
            return;
        }

        const clearBtn = document.getElementById('locationSearchClear');

        // ── 1. Create Google Places Autocomplete ──────────────────────────────
        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode', 'establishment'],
            fields: ['geometry', 'name', 'formatted_address']
        });

        // ── 2. Fix .pac-container position to avoid map overflow:hidden ───────
        // Google appends .pac-container to <body> but uses position:absolute,
        // calculated from the element's document offset. Because the map container
        // applies its own offset context, the top/left values are wrong.
        // We override with position:fixed + getBoundingClientRect().
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

        // Watch for Google to inject .pac-container into <body>
        const observer = new MutationObserver(() => {
            if (!pacContainer) {
                pacContainer = document.querySelector('.pac-container');
                if (pacContainer) {
                    fixPacPosition();
                    // Re-fix on every style mutation (Google repositions it on scroll etc.)
                    new MutationObserver(fixPacPosition).observe(
                        pacContainer, { attributes: true, attributeFilter: ['style'] }
                    );
                }
            }
        });
        observer.observe(document.body, { childList: true, subtree: false });

        // Keep in sync with input position on scroll / resize
        window.addEventListener('scroll', fixPacPosition, true);
        window.addEventListener('resize', fixPacPosition);
        input.addEventListener('focus',  fixPacPosition);
        input.addEventListener('input',  fixPacPosition);

        // ── 3. Prevent the map from capturing keyboard / pointer input ─────────
        L.DomEvent.on(input, 'keydown keypress keyup mousedown dblclick wheel', L.DomEvent.stopPropagation);

        // ── 4. Focus styling ───────────────────────────────────────────────────
        input.addEventListener('focus', () => {
            input.style.borderColor = '#1a73e8';
            input.style.boxShadow   = '0 0 0 3px rgba(26,115,232,0.15)';
        });
        input.addEventListener('blur', () => {
            input.style.borderColor = '#ddd';
            input.style.boxShadow   = 'none';
        });

        // Show/hide × button
        input.addEventListener('input', () => {
            if (clearBtn) clearBtn.style.display = input.value.length ? 'inline' : 'none';
        });

        // ── 5. Handle place selection ─────────────────────────────────────────
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

            const badge    = document.getElementById('locationFoundBadge');
            const badgeName = document.getElementById('locationFoundName');
            if (badge)     badge.style.display = 'block';
            if (badgeName) badgeName.textContent = label;

            setRadiusSectionEnabled(true);
            const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
            updateRadiusCircleAndPin(radius);
            refreshCurrentFilters();

            // Show category bar
            categoryBar.style.display = 'flex';
        });

        // ── 6. Clear button ───────────────────────────────────────────────────
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

                // Hide category bar & clear category markers
                categoryBar.style.display = 'none';
                clearCategoryMarkers();
                if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
                closeRoutePanel();

                setRadiusSectionEnabled(false);
                const rEl    = document.getElementById('radiusRangeMap');
                const rValEl = document.getElementById('radiusValueMap');
                if (rEl)    rEl.value          = 0;
                if (rValEl) rValEl.textContent = '0';

                refreshCurrentFilters();
                input.focus();
            });
        }
    }

    // --- Fetch Data ---
    async function fetchData(url, filters = {}) {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([k, v]) => {
            if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
            else if (v !== '' && v != null) params.append(k, v);
        });
        if (drawnPolygonGeoJSON) params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));
        //  console.log(url + '?' + params.toString());

        try {
            const res = await fetch(`${url}?${params.toString()}`);
            return res.ok ? await res.json() : [];
        } catch (e) {
            console.error(`Error fetching ${url}:`, e);
            return [];
        }
    }

    // --- Add Markers ---
    function addMarkers(data, group, defaultIconUrl) {
        group.clearLayers();
        data.forEach(item => {
            if (!item || !item.latitude || !item.longitude) return;

            let iconSize = [24, 24];
            let iconAnchor = [12, 24];

            // Police icon lebih kecil
            if (item.name_police) {
                iconSize = [12, 12];
                iconAnchor = [6, 6];
            }

            const icon = L.icon({
                iconUrl: item.icon || defaultIconUrl || 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconSize: iconSize,
                iconAnchor: iconAnchor,
                popupAnchor: [0, -20]
            });

            const marker = L.marker([item.latitude, item.longitude], { icon }).addTo(group);

            let itemName = '', detailUrl = '', popupContent = '';

            if (item.airport_name) {
                itemName = item.airport_name;
                detailUrl = `/airports/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Classification:</strong> ${item.category || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city_name ? ', ' + item.city_name : ''}
                        ${item.province_name ? ', ' + item.province_name : ''}, Thailand <br>
                    <strong>Website:</strong> ${item.website || 'N/A'} <br>
                `;
            } else if (item.name) {
                itemName = item.name;
                detailUrl = `/hospitals/${item.id}`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Global Classification:</strong> ${item.facility_category || 'N/A'}<br>
                    <strong>Country Classification:</strong> ${item.facility_level || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}, Thailand <br>
                `;
            } else if (item.name_police) {
                itemName = item.name_police;
                detailUrl = `/police/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Category:</strong> ${item.category || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.location || 'N/A'}
                        ${item.city_name ? ', ' + item.city_name : ''}
                        ${item.province_name ? ', ' + item.province_name : ''}, Thailand <br>
                    <strong>Phone:</strong> ${item.telephone || 'N/A'}<br>
                    <strong>Fax:</strong> ${item.fax || 'N/A'}<br>
                    <strong>Email:</strong> ${item.email || 'N/A'}<br>
                    <strong>Website:</strong> ${item.website || 'N/A'}<br>
                `;
            }
            else if (item.name_embassiees) {
                itemName = item.name_embassiees;
                detailUrl = `/embassiees/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}, Thailand <br>
                    <strong>Phone:</strong> ${item.telephone || 'N/A'}<br>
                    <strong>Fax:</strong> ${item.fax || 'N/A'}<br>
                    <strong>Email:</strong> ${item.email || 'N/A'}<br>
                    <strong>Website:</strong> ${item.website || 'N/A'}<br>
                `;
            }

            if (item.id && detailUrl)
                popupContent += `<a href="${detailUrl}" class="btn btn-primary btn-sm mt-2" style="color:white;">Read More</a>`;

            marker.bindPopup(popupContent);
        });
    }

    // --- Apply Filters ---
    async function applyFiltersWithMapControl(
        facilities = [],
        hospitalLevels = [],
        airportClasses = [],
        provinces = [],
        radius = 0,
        airportName = '',
        hospitalName = ''
    ) {
        let common = { provinces };
        if (radius > 0 && lastClickedLocation) {
            common.radius = radius;
            common.center_lat = lastClickedLocation.lat;
            common.center_lng = lastClickedLocation.lng;
        }

        totalHospitals = 0;
        totalAirports = 0;
        totalPolice = 0;
        totalEmbassies = 0;

        // jika tidak ada checkbox dipilih => tampilkan semua
        const showAllFacilities = facilities.length === 0;

        const showHospital =
            showAllFacilities || facilities.includes('hospital');

        const showAirport =
            showAllFacilities || facilities.includes('airport');

        const showPolice =
            showAllFacilities || facilities.includes('police');

        const showEmbassy =
            showAllFacilities || facilities.includes('embassy');

        // === HOSPITALS ===
        if (showHospital) {
             const result = await fetchData('/api/hospital', {
                ...common,
                name: hospitalName,
                category: hospitalLevels
            });

            addMarkers(result.hospitals, hospitalMarkers, null);

            totalHospitals = result.hospitals.length;
        } else {
            hospitalMarkers.clearLayers();
        }

        // === AIRPORTS ===
       if (showAirport) {

            const airportResponse = await fetchData('/api/airports', {
                ...common,
                name: airportName
            });

            const airports = Array.isArray(airportResponse)
                    ? airportResponse
                    : airportResponse.airports || [];
            const categoryCounts = airportResponse.categoryCounts || {};

            const filteredAirports = airports.filter(a => {

                if (airportClasses.length === 0) {
                    return true;
                }

                if (!a.category) {
                    return false;
                }

                const dbCategories = a.category
                    .split(',')
                    .map(c => c.trim().toLowerCase());

                return airportClasses.some(sel =>
                    dbCategories.includes(sel.toLowerCase())
                );
            });

            addMarkers(
                filteredAirports,
                airportMarkers,
                'https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png'
            );

            totalAirports = filteredAirports.length;
        }else {
            airportMarkers.clearLayers();
        }

        // === POLICE ===
       if (showPolice) {

            const result = await fetchData('/api/polices', {
                ...common
            });

            const police = result.polices || [];
            const categoryCounts = result.categoryCounts || {};

            addMarkers(
                police,
                policeMarkers,
                null
            );

            totalPolice = police.length;

            Object.keys(categoryCounts).forEach(cat => {

                const id = cat.replace(/[^a-zA-Z0-9]/g, '-');

                const el = document.getElementById(`count-${id}`);

                if (el) {
                    el.textContent = categoryCounts[cat];
                }
            });
        } else {
            policeMarkers.clearLayers();
        }

        // === EMBASSY ===
        if (showEmbassy) {

            const embassies = await fetchData('/api/embassy', {
                ...common
            });

            addMarkers(
                embassies,
                embassyMarkers,
                '/images/embassy-icon-new.png'
            );

            totalEmbassies = embassies.length;

        } else {
            embassyMarkers.clearLayers();
        }

        updateRadiusCircleAndPin(radius);
        updateTotalCountDisplay();
    }

    function updateTotalCountDisplay() {
        document.getElementById('airportCount').textContent = totalAirports;
        document.getElementById('hospitalCount').textContent = totalHospitals;
        document.getElementById('policeCount').textContent = totalPolice;
        document.getElementById('embassyCount').textContent = totalEmbassies;

        const el = document.getElementById('totalCountDisplay');
    }

    // === COMBINED PANEL ===
    let filterPanelDiv = null;

    const CombinedPanel = L.Control.extend({
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
                    <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;"> Search Location</strong>
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
                        <!-- Autocomplete dropdown - inside input wrapper so position relative works correctly -->
                        <div id="locationAutocompleteList"
                            style="display:none;position:absolute;left:0;right:0;top:100%;margin-top:2px;background:white;border:1px solid #ddd;border-radius:6px;box-shadow:0 4px 16px rgba(0,0,0,0.18);z-index:999999;max-height:220px;overflow-y:auto;"
                        ></div>
                    </div>
                    <div id="locationFoundBadge" style="display:none;margin-top:6px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:5px;padding:4px 8px;font-size:12px;color:#2e7d32;">
                        &#128204; <span id="locationFoundName"></span>
                    </div>
                </div>

                <!-- Radius - also outside scrollable, enabled after location selected -->
                <div id="radiusSection" style="flex:0 0 auto;padding:0 10px 0 10px;opacity:0.4;pointer-events:none;transition:opacity 0.3s;">
                    <hr style="margin:8px 0;">
                    <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">&#11096; Radius: <span id="radiusValueMap">0</span> km</strong>
                    <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin:4px 0;">
                    <div style="display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:5px;">
                        <span>0</span><span>250 km</span><span>500 km</span>
                    </div>
                    <div style="display:flex;gap:5px;margin-bottom:6px;">
                        <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
                        <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
                    </div>
                </div>

                <!-- Scrollable filters below -->
                <div id="filterPanel" style="
                    flex:1 1 auto;
                    min-height:0;
                    padding:0 10px 10px 10px;
                    overflow-y:auto;
                    border-top:1px solid #eee;
                ">
                    <strong style="margin-top:8px;">Facilities</strong>
                    <div class="form-check">
                        <input class="form-check-input facility-checkbox" type="checkbox" value="hospital" id="facilityHospital">
                        <label class="form-check-label" for="facilityHospital">
                            Medical (<span id="hospitalCount">0</span>)
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input facility-checkbox" type="checkbox" value="airport" id="facilityAirport">
                        <label class="form-check-label" for="facilityAirport">
                            Aviation (<span id="airportCount">0</span>)
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input facility-checkbox" type="checkbox" value="police" id="facilityPolice">
                        <label class="form-check-label" for="facilityPolice">
                            Police (<span id="policeCount">0</span>)
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input facility-checkbox" type="checkbox" value="embassy" id="facilityEmbassy">
                        <label class="form-check-label" for="facilityEmbassy">
                            Embassies (<span id="embassyCount">0</span>)
                        </label>
                    </div>

                    <hr>
                    <div class="filter-box" id="provinceSelect">
                        <label class="filter-label">
                            Region
                        </label>

                        <div class="select-input">
                            <input
                                type="text"
                                id="provinceSearch"
                                placeholder="🔍 Select Region"
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
                                @foreach($provinces as $province)
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
                    <button id="resetMapFilter"
                            class="btn btn-sm btn-secondary w-100"
                            style="margin-top:auto;">
                        Reset All
                    </button>
                    <div id="totalCountDisplay" style="margin-top:8px;text-align:center;font-size:13px;"></div>
                </div>`;
            filterPanelDiv = div;
            L.DomEvent.disableClickPropagation(div);
            L.DomEvent.disableScrollPropagation(div);
            return div;
        }
    });
    map.addControl(new CombinedPanel());

    // Tinggi panel mengikuti tinggi peta supaya tombol "Reset All" tidak terpotong
    function syncFilterPanelHeight() {
        if (!filterPanelDiv) return;
        filterPanelDiv.style.maxHeight = Math.max(220, map.getSize().y - 20) + 'px';
    }
    syncFilterPanelHeight();
    map.on('resize', syncFilterPanelHeight);
    window.addEventListener('resize', syncFilterPanelHeight);

    // === INIT SELECT2 ===
    setTimeout(() => {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select-search-airport').select2({ placeholder: 'Select Airport', width: '100%' });
            $('.select-search-hospital').select2({ placeholder: 'Select Hospital', width: '100%' });
        }
    }, 300);

    function getCurrentFiltersFromUI() {
        const facilities = [...document.querySelectorAll('.facility-checkbox:checked')].map(el => el.value);
        const hLevels = [...document.querySelectorAll('input[name="hospitalLevel"]:checked')].map(e => e.value);
        const aClasses = [...document.querySelectorAll('input[name="airportClass"]:checked')].map(e => e.value);
        const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
        const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
        // untuk select2, .value akan tetap bekerja because Select2 keeps value in the <select>
        const airportName = document.getElementById('airport_name_map')?.value || '';
        const hospitalName = document.getElementById('hospital_name_map')?.value || '';
        return { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName };
    }

    async function refreshCurrentFilters() {
        const {
            facilities,
            hLevels,
            aClasses,
            provs,
            radius,
            airportName,
            hospitalName
        } = getCurrentFiltersFromUI();

        await applyFiltersWithMapControl(
            facilities,
            hLevels,
            aClasses,
            provs,
            radius,
            airportName,
            hospitalName
        );
    }

    // === Event Logic ===
    document.addEventListener('change', async e => {
        const facilities = [...document.querySelectorAll('.facility-checkbox:checked')].map(el => el.value);
        const hLevels = [...document.querySelectorAll('input[name="hospitalLevel"]:checked')].map(e => e.value);
        const aClasses = [...document.querySelectorAll('input[name="airportClass"]:checked')].map(e => e.value);
        const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
        const radius = parseInt(document.getElementById('radiusRangeMap').value || 0);
        const airportName = document.getElementById('airport_name_map')?.value || '';
        const hospitalName = document.getElementById('hospital_name_map')?.value || '';

        await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
    });

    // === INPUT: update tampilan radius saat slider digeser (live) ===
document.addEventListener('input', (e) => {
    if (e.target && e.target.id === 'radiusRangeMap') {
        const r = parseInt(e.target.value || 0);
        const el = document.getElementById('radiusValueMap');
        if (el) el.textContent = r;
        // hanya update tampilan lingkaran saja (belum apply ke filter)
        updateRadiusCircleAndPin(r);
    }
});

// === CLICK: apply / reset radius dan reset all ===
document.addEventListener('click', async (e) => {
    if (!e.target) return;

    // APPLY RADIUS => ambil filter sekarang lalu panggil applyFiltersWithMapControl dengan radius
    if (e.target.id === 'applyRadiusMap') {
        const {
            facilities,
            hLevels,
            aClasses,
            provs,
            radius,
            airportName,
            hospitalName
        } = getCurrentFiltersFromUI();
        // pastikan lastClickedLocation ada jika radius > 0
        if (radius > 0 && !lastClickedLocation) {
            alert('Tentukan titik di peta terlebih dahulu dengan klik peta untuk menggunakan filter radius.');
            return;
        }
        await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        return;
    }

    // RESET RADIUS (hanya reset radius visual & reapply tanpa radius)
    if (e.target.id === 'resetRadiusMap') {
        // reset slider & tampilan
        const rEl = document.getElementById('radiusRangeMap');
        const rValEl = document.getElementById('radiusValueMap');
        if (rEl) rEl.value = 0;
        if (rValEl) rValEl.textContent = '0';

        // hapus circle & pin
        if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }
        if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }
        lastClickedLocation = null;

        // reset location search + nearby
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
        setRadiusSectionEnabled(false);

        // apply ulang tanpa radius (tetap simpan filter lain)
        const { facilities, hLevels, aClasses, provs, airportName, hospitalName } = getCurrentFiltersFromUI();
        await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, 0, airportName, hospitalName);
        return;
    }

    // RESET ALL FILTERS (tombol Reset All) -> gunakan handler yang sudah komprehensif
    if (e.target.id === 'resetMapFilter') {
        // 1) UI reset
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });

        // reset province dropdown text
        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            provinceSearch.value = '';
        }

        // reset search keyword pada dropdown
        const provinceSearchInput = document.getElementById('provinceSearchInput');
        if (provinceSearchInput) {
            provinceSearchInput.value = '';
        }

        // tampilkan kembali semua province
        document.querySelectorAll('#provinceList li').forEach(li => {
            li.style.display = '';
        });

        // sembunyikan sub-panels
        const af = document.getElementById('airportFilter');
        const hf = document.getElementById('hospitalFilter');
        if (af) af.style.display = 'none';
        if (hf) hf.style.display = 'none';

        // 2) Reset Select2 (jika ada)
        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            $('.select-search-airport').each(function () { $(this).val(null).trigger('change'); });
            $('.select-search-hospital').each(function () { $(this).val(null).trigger('change'); });
        } else {
            const airportSel = document.getElementById('airport_name_map');
            const hospitalSel = document.getElementById('hospital_name_map');
            if (airportSel) airportSel.value = '';
            if (hospitalSel) hospitalSel.value = '';
        }

        // 3) Reset radius visual & location search
        const radiusRange = document.getElementById('radiusRangeMap');
        const radiusValue = document.getElementById('radiusValueMap');
        if (radiusRange) radiusRange.value = 0;
        if (radiusValue) radiusValue.textContent = '0';
        if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }
        if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        // sembunyikan nearby bar + hapus marker kategori & rute
        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
        closeRoutePanel();
        setRadiusSectionEnabled(false);

        // 4) Remove drawn polygon and layers
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

        // 5) Clear markers and counters
        if (airportMarkers) airportMarkers.clearLayers();
        if (hospitalMarkers) hospitalMarkers.clearLayers();
        if (policeMarkers) policeMarkers.clearLayers();
        if (embassyMarkers) embassyMarkers.clearLayers();
        totalAirports = 0;
        totalHospitals = 0;
        totalPolice = 0;
        totalEmbassies = 0;
        updateTotalCountDisplay();

        // 6) Re-fetch semua data
        await applyFiltersWithMapControl(
            [],
            [],
            [],
            [],
            0,
            '',
            ''
        );

        e.stopPropagation();
        e.preventDefault();
        return;
    }
});

// === LISTEN TO CHANGE on filter inputs (kategori/provinsi/select nama) ===
// Ini memastikan ketika user change checkbox / select2, filter langsung ter-apply
function bindFilterChangeAutoApply() {
    // checkbox change
    document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(el => {
        el.addEventListener('change', async () => {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
    });

    // select2 change (nama)
    // if Select2 is used, listen with jQuery; otherwise plain change event above covers plain <select>
    if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
        $(document).on('change', '#airport_name_map, #hospital_name_map', async function () {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
    } else {
        document.getElementById('airport_name_map')?.addEventListener('change', async () => {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
        document.getElementById('hospital_name_map')?.addEventListener('change', async () => {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
    }
}

// call binding after panel is rendered
setTimeout(() => {
    bindFilterChangeAutoApply();
    initLocationSearch();
}, 350);

    // --- Initial Load ---
    refreshCurrentFilters();
</script>

@endpush
