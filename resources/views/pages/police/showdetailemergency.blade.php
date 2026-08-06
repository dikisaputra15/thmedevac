@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'Papua New Guinea Airports')

@push('styles')

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

    .legend-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        width: 100%;
        align-items: start;
    }

    .legend-grid-item {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        width: 100%;
        text-align: left;
        white-space: nowrap;
    }

    .legend-grid-item img {
        flex-shrink: 0;
    }

    .legend-grid-item small {
        text-align: left;
    }

    /* ====== DIRECTIONS PANEL - Modern Styling ====== */
    #directionsPanel {
        font-family: 'Segoe UI', Roboto, -apple-system, sans-serif !important;
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 transparent;
    }
    #directionsPanel::-webkit-scrollbar { width: 5px; }
    #directionsPanel::-webkit-scrollbar-thumb {
        background: #c1c1c1; border-radius: 10px;
    }
    #directionsPanel .dp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: linear-gradient(135deg, #1a73e8, #4285f4);
        border-radius: 8px 8px 0 0;
        margin: 0;
        color: #fff;
    }
    #directionsPanel .dp-header-title {
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #directionsPanel .dp-header-title i { color: #fff !important; font-size: 16px; }
    #directionsPanel .dp-close-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        width: 28px; height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.2s;
    }
    #directionsPanel .dp-close-btn:hover { background: rgba(255,255,255,0.35); }
    #directionsPanel .dp-close-btn i { color: #fff !important; }

    /* Google-generated table overrides */
    #directionsPanel table { border: none !important; width: 100%; }
    #directionsPanel td {
        border: none !important;
        padding: 6px 4px !important;
        font-size: 13px;
        vertical-align: top;
    }
    #directionsPanel .adp-directions { margin: 0 !important; }

    /* Route summary (origin → destination bar) */
    #directionsPanel .adp-placemark {
        background: #f0f4ff;
        border-radius: 8px;
        margin-bottom: 8px !important;
        overflow: hidden;
    }
    #directionsPanel .adp-placemark td {
        padding: 10px 12px !important;
        font-weight: 600;
        color: #1a3c6e;
        font-size: 13px;
    }
    #directionsPanel .adp-placemark img {
        filter: hue-rotate(200deg) saturate(1.5);
    }

    /* Summary bar (distance & time) */
    #directionsPanel .adp-summary {
        background: linear-gradient(135deg, #e8f0fe, #d2e3fc);
        border-radius: 8px;
        padding: 10px 14px !important;
        margin: 8px 0 !important;
        font-size: 13px;
        color: #1a3c6e;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Step list */
    #directionsPanel .adp-listsel,
    #directionsPanel .adp-list {
        border: none !important;
    }
    #directionsPanel .adp-listinfo {
        border: none !important;
        background: transparent !important;
    }

    /* Individual step rows */
    #directionsPanel .adp-step {
        border-bottom: 1px solid #eef1f5 !important;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        transition: background 0.15s;
        border-radius: 6px;
        margin-bottom: 2px;
    }
    #directionsPanel .adp-step:hover {
        background: #f5f8ff !important;
    }
    #directionsPanel .adp-step:last-child {
        border-bottom: none !important;
    }

    /* Step icon cell */
    #directionsPanel .adp-step .adp-stepicon {
        padding: 8px 4px 8px 8px !important;
    }
    #directionsPanel .adp-step .adp-stepicon .adp-maneuver {
        width: 20px;
        height: 20px;
    }

    /* Step text */
    #directionsPanel .adp-step .adp-substep {
        padding: 8px 12px 8px 4px !important;
        color: #333;
        line-height: 1.5;
        font-size: 12.5px;
    }
    #directionsPanel .adp-step .adp-substep b {
        color: #1a73e8;
        font-weight: 600;
    }
    /* Step distance */
    #directionsPanel .adp-step td:last-child {
        color: #5f6368;
        font-size: 12px;
        white-space: nowrap;
        padding-right: 10px !important;
    }

    /* Warning / legal */
    #directionsPanel .adp-warnbox,
    #directionsPanel .adp-legal {
        font-size: 11px;
        color: #888;
        padding: 6px 12px !important;
        border: none !important;
    }
    #directionsPanel .adp-legal a { color: #1a73e8; }

    /* Highlighted / selected step */
    #directionsPanel .adp-listsel {
        background: #e8f0fe !important;
        border-radius: 6px;
    }
</style>

@endpush

@section('conten')

<div class="card">

<div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">
       <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $police->name_police }}</h2>
            <span class="fw-bold"><b>Police Classification (Global):</b> {{ $police->level }} | <b>Police Classification (Country):</b> {{ $police->category }}</span>
        </div>

        <div class="d-flex gap-2 ms-auto">

              <!-- Button 2 -->
            <a href="{{ url('police') }}/{{$police->id}}/detail" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('police/'.$police->id.'/detail') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <!-- Button 5 -->
             <a href="{{ url('police') }}/{{$police->id}}/emergency" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('police/'.$police->id.'/emergency') ? 'active' : '' }}">
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

            <a href="{{ url('police') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('police') ? 'active' : '' }}">
                <i class="bi bi-person-badge" style="width: 24px; height: 24px;"></i>
                <small>Police</small>
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
            <small><i>Last Updated {{ $police->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('policedata.edit', $police->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">

        <div class="col-sm-8 d-flex flex-column gap-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-emergency-support.png') }}" style="width: 24px; height: 24px;"> Emergency Support Tools</div>

                <div class="classification">
                    <!-- Airfield Classification -->
                    <div class="classification" style="margin-right: 30px; width: 30%;">
                      <!-- Airport -->
                      <div class="class-column">
                        <div class="class-header class-airport-category">Airfield Classification</div>
                        <div class="airport-list" style="align-items:start;">
                          <div class="hospital-row legend-grid">

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level6Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:18px; height:18px;">
                                  <small>International</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level5Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:18px; height:18px;">
                                  <small>Domestic</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level4Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:18px; height:18px;">
                                  <small>Regional</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                  <small>Civil-Military</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level3Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:18px; height:18px;">
                                  <small>Military</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level1Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:18px; height:18px;">
                                  <small>Private</small>
                              </button>

                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Hospital Classification -->
                    <div class="classification" style="flex-direction: column; width:100%;">
                      <div class="class-header class-medical-classification" style="text-align:left;">Medical Facility Classification</div>
                      <div class="classification">
                        <!-- Advanced -->
                        <div class="class-column" style="align-items: flex-start; text-align: left;">
                          <div class="class-header class-advanced">Advanced</div>
                          <div style="display: flex; flex-direction: row; align-items: flex-start; gap: 10px;">
                              <button class="btn p-1 legend-grid-item" style="width: auto; padding-left: 0 !important;" data-bs-toggle="modal" data-bs-target="#level66Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:24px; height:24px;">
                                <small>Class A</small>
                              </button>
                          </div>
                        </div>

                        <!-- Intermediate -->
                        <div class="class-column" style="align-items: flex-start; text-align: left;">
                          <div class="class-header class-intermediate">Intermediate</div>
                          <div style="display: flex; flex-direction: row; align-items: flex-start; gap: 10px;">
                              <button class="btn p-1 legend-grid-item" style="width: auto; padding-left: 0 !important;" data-bs-toggle="modal" data-bs-target="#level55Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:24px; height:24px;">
                                <small>Class B</small>
                              </button>
                              <button class="btn p-1 legend-grid-item" style="width: auto; padding-left: 0 !important;" data-bs-toggle="modal" data-bs-target="#level44Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:24px; height:24px;">
                                <small>Class C</small>
                              </button>
                          </div>
                        </div>

                        <!-- Basic -->
                        <div class="class-column" style="align-items: flex-start; text-align: left;">
                          <div class="class-header class-basic">Basic</div>
                          <div style="display: flex; flex-direction: row; align-items: flex-start; gap: 10px;">
                              <button class="btn p-1 legend-grid-item" style="width: auto; padding-left: 0 !important;" data-bs-toggle="modal" data-bs-target="#level33Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:24px; height:24px;">
                                <small>Class D</small>
                              </button>
                              <button class="btn p-1 legend-grid-item" style="width: auto; padding-left: 0 !important;" data-bs-toggle="modal" data-bs-target="#level11Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:24px; height:24px;">
                                  <small>PUSKESMAS</small>
                              </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="class-column" style="margin-left: 50px;">
                        <div class="class-header class-airport-category">POLICE CLASSIFICATION</div>

                        <div class="airport-list" style="align-items:start;">
                            <div class="hospital-row legend-grid">

                                <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police6Modal">
                                    <img src="{{ asset('images/Layer1.png') }}" style="width:12px; height:12px;">
                                    <small>Polri HQ (National)</small>
                                </button>

                                <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police5Modal">
                                    <img src="{{ asset('images/Layer2.png') }}" style="width:12px; height:12px;">
                                    <small>Polda</small>
                                </button>

                                <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police4Modal">
                                    <img src="{{ asset('images/Layer3.png') }}" style="width:12px; height:12px;">
                                    <small>Polres</small>
                                </button>

                                <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police3Modal">
                                    <img src="{{ asset('images/Layer4.png') }}" style="width:12px; height:12px;">
                                    <small>Polsek</small>
                                </button>

                                <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police2Modal">
                                    <img src="{{ asset('images/Brimob.png') }}" style="width:12px; height:12px;">
                                    <small>Brimob</small>
                                </button>

                                <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police1Modal">
                                    <img src="{{ asset('images/Gegana.png') }}" style="width:12px; height:12px;">
                                    <small>Gegana</small>
                                </button>

                            </div>
                        </div>

                    </div>
                  </div>

                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 d-flex flex-column gap-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="https://concord-consulting.com/static/img/cmt/icon/radar-icon.png" style="width: 24px; height: 24px;"> Nearest Support Facilities</div>
                <div class="card-body overflow-auto">
                    <?php echo $police->nearest_medical_facility; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/hotlines-icon.png') }}" style="width: 24px; height: 24px;"> Emergency Hotline</div>
                <div class="card-body">
                    <?php echo $hospital->travel_agent; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-medical-support-website.png') }}" style="width: 24px; height: 24px;"> Emergency Medical Support</div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                        <?php echo $hospital->medical_support_website; ?>
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
        <p class="p-modal text-justify">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal text-justify">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
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
            <h5 class="modal-title" id="disclaimerLabel">Combined (Civil-Military) Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal text-justify">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
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
        <p class="p-modal text-justify">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
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
        <p class="p-modal text-justify">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal text-justify">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>

<!-- PUSKESMAS -->
<div class="modal fade" id="level11Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Public Health Center (PUSKESMAS)</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">A Public Health Center (Pusat Kesehatan Masyarakat / Puskesmas) is a government-operated primary healthcare facility regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH), under national health service regulations. Puskesmas function as a first-level healthcare provider (Fasilitas Kesehatan Tingkat Pertama / FKTP) within Indonesia’s health system and BPJS Kesehatan referral framework, it operates at the sub-district (kecamatan) level and serves as the backbone of community-based healthcare delivery. Puskesmas provides comprehensive primary care services, including promotive, preventive, curative, and rehabilitative care focusing on maternal and child health, immunization, and public health programs for the defined population it serves.</p>

        <p class="p-modal text-justify">
            Most Puskesmas are automatically BPJS-contracted as government facilities. Private clinics acting as FKTP must formally contract with BPJS to serve insured patients. BPJS participants generally must first access care at FKTP before being referred to a hospital, except in emergencies.
        </p>

        <p class="p-modal text-justify">
            <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>

        <p class="p-modal text-justify">
            <strong>Bed Capacity</strong>
            <ul>
                <li>
                    <strong>Non-Inpatient Puskesmas (Rawat Jalan)</strong>
                    <ul>
                        <li>No inpatient beds</li>
                        <li>Focused on outpatient and preventive services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Inpatient Puskesmas (Rawat Inap)</strong>
                    <ul>
                        <li>Typically 5–10 short-stay beds</li>
                        <li>Designed for basic observation, uncomplicated deliveries, and short-term stabilization</li>
                        <li>Bed capacity is limited and not comparable to hospital inpatient facilities</li>
                    </ul>
                </li>
            </ul>
        </p>

        <p class="p-modal text-justify">
            <strong>Clinical Services</strong>
            <ul>
                <li>
                    <strong>Primary Medical Services</strong>
                    <ul>
                        <li>General practitioner consultations</li>
                        <li>Basic diagnosis and treatment of common illnesses</li>
                        <li>Maternal and child health services</li>
                        <li>Immunization services</li>
                        <li>Family planning services</li>
                        <li>Basic dental services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Public Health & Preventive Services</strong>
                    <ul>
                        <li>Disease surveillance and outbreak response</li>
                        <li>Health promotion and education programs</li>
                        <li>Community nutrition programs</li>
                        <li>Environmental health services</li>
                        <li>School health programs (UKS)</li>
                        <li>Posyandu supervision</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Stabilization Services</strong>
                    <ul>
                        <li>Basic emergency care</li>
                        <li>Initial trauma stabilization</li>
                        <li>Basic life support</li>
                        <li>Referral coordination to hospitals (Class D/C/B/A)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Services</strong>
                    <ul>
                        <li>Basic laboratory testing</li>
                        <li>Basic pharmacy services</li>
                        <li>Basic medical procedures (wound care, minor procedures)</li>
                        <li>Antenatal and postnatal care services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Outreach & Community Services</strong>
                    <ul>
                        <li>Mobile health services (Puskesmas Keliling)</li>
                        <li>Home visits</li>
                        <li>Integrated community health programs</li>
                    </ul>
                </li>
            </ul>
        </p>

        <p class="p-modal text-justify">
            <strong>Public Health Center (PUSKESMAS) Role</strong>
            <ul>
                <li>First-level entry point into Indonesia’s healthcare system</li>
                <li>Primary gatekeeper in the BPJS referral system</li>
                <li>Community health program implementation center</li>
                <li>Preventive and promotive health service hub</li>
                <li>Early detection and disease surveillance center</li>
                <li>Referral coordinator to higher-level hospitals</li>
            </ul>
        </P>
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
            <h5 class="modal-title" id="disclaimerLabel">Class D — Sub-district Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            A Class D Hospital (Rumah Sakit Kelas D), regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH). Class D hospitals provide basic inpatient, outpatient, and emergency services with general practitioners and limited specialist support, including basic medical and surgical capability.
        </p>
        <p class="p-modal text-justify">
            Class D hospitals operate mainly at the sub-district level, it serves as an entry-level facility within the referral system, managing uncomplicated cases, stabilizing emergency patients, and referring more complex conditions to higher-level hospitals. This classification applies to both public and private institutions that meet the established minimum infrastructure, staffing, and service standards.
        </p>
        <p class="p-modal text-justify">
            Public Class D hospitals commonly contract with BPJS. Private Class D hospitals may choose whether to participate. In the referral system, they receive patients from Puskesmas or other first-level facilities if contracted.
        </p>
        <p class="p-modal text-justify">
            Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
            <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 50 inpatient beds (Most Class D hospitals operate between 50–100 beds)
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>At least 2 basic specialist services (typically Internal Medicine and Surgery, or adjusted based on regional need)</li>
                        <li>General practitioner-led services</li>
                        <li>Basic maternal and child health services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Unit (basic capability)</li>
                        <li>Initial stabilization of trauma and acute cases</li>
                        <li>Referral coordination to Class C/B hospitals</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>Basic laboratory</li>
                        <li>Basic radiology / X-ray (limited)</li>
                        <li>Standard ultrasound (if available)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Minor surgical procedures</li>
                        <li>Basic obstetric procedures</li>
                        <li>Wound care and emergency interventions</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>Pharmacy</li>
                        <li>Basic sterilization services</li>
                        <li>Medical records system</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class D Hospital Role</strong>
            <ul>
                <li>First-level hospital within the referral system</li>
                <li>Bridging facility between primary care (Puskesmas/clinics) and higher-level hospitals</li>
                <li>Basic inpatient and emergency care provider</li>
                <li>Stabilization and referral coordination center</li>
                <li>Healthcare access expansion tool in remote or newly developed areas</li>
            </ul>
        </P>
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
            <h5 class="modal-title" id="disclaimerLabel">Class C — District-Level Hospital</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            A secondary-level hospital regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH). Class C hospitals provide core specialist services in internal medicine, surgery, obstetrics, and pediatrics, managing common medical conditions across inpatient and outpatient settings.
        </p>
        <p class="p-modal text-justify">
            Class C hospitals function primarily as a regency/city (kabupaten/kota) referral hospital, a Class C facility performs common surgical procedures, stabilizes emergency patients, and refers more complex or subspecialty cases to Class B or Class A hospitals. This classification applies to both public and private hospitals that meet the prescribed infrastructure, staffing, and service standards.
        </p>
        <p class="p-modal text-justify">
            Many Class C hospitals (particularly public facilities) contract with BPJS and therefore serve as the most common hospital-level provider for BPJS participants. However, private Class C hospitals may operate partially or entirely outside the BPJS system depending on their contractual status.
        </p>
        <p class="p-modal text-justify">
            Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
            Note: BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 100 inpatient beds (Most Class C hospitals operate between 100–200 beds, depending on district demand)
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>4 basic specialists: Internal Medicine, Surgery, Pediatrics, Obstetrics & Gynecology</li>
                        <li>General anesthesia services</li>
                        <li>Basic radiology and pathology services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Department (IGD)</li>
                        <li>Basic resuscitation capability</li>
                        <li>Limited ICU or high-dependency care (depending on facility)</li>
                        <li>Maternal and neonatal emergency care</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>Basic laboratory services</li>
                        <li>X-ray radiology</li>
                        <li>Standard ultrasound</li>
                        <li>Blood transfusion service (limited capacity)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Operating theatre(s) for general surgery</li>
                        <li>Obstetric surgery capability (C-section)</li>
                        <li>Minor orthopedic and emergency surgical procedures</li>
                        <li>Basic inpatient and outpatient treatment</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>Pharmacy</li>
                        <li>CSSD (basic sterilization services)</li>
                        <li>Medical records system</li>
                        <li>Nutrition services</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class C Hospital Role</strong>
            <ul>
                <li>District-level referral hospital</li>
                <li>Primary inpatient and surgical provider for local population</li>
                <li>Stabilization point before referral to Class B/A hospitals</li>
                <li>Key BPJS referral destination from primary care (Puskesmas/clinics)</li>
                <li>Essential maternal and emergency care provider at regional level</li>
            </ul>
        </P>
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
            <h5 class="modal-title" id="disclaimerLabel">Class B — Provincial Referral Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            Secondary–tertiary level referral hospital regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH). Class B hospitals provide comprehensive specialist medical services and selected subspecialist services, supported by advanced diagnostic and therapeutic facilities.
        </p>
        <p class="p-modal text-justify">
           Class B hospitals function as provincial or inter-district referral centers, managing moderate to complex medical and surgical cases referred from lower-level hospitals (Class C and D), while referring highly complex subspecialty cases to Class A hospitals. This classification applies equally to public and private hospitals that meet the required standards of infrastructure, human resources, equipment, and service capability.
        </p>
        <p class="p-modal text-justify">
           Public Class B hospitals typically contract with BPJS. Private Class B hospitals may selectively contract or operate fully private services. BPJS patients are accepted only in contracted facilities and generally arrive through referrals from Class C or D hospitals.
        </p>
        <p class="p-modal text-justify">
           Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
           <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 200 inpatient beds. Most Class B hospitals operate between 200–400+ beds, depending on regional demand and provincial role.
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>4 basic specialists: Internal Medicine, Surgery, Pediatrics, Obstetrics & Gynecology</li>
                        <li>Additional major specialties (e.g., Anesthesiology, Radiology, Pathology, Neurology, Psychiatry, Dermatology, ENT, Ophthalmology)</li>
                        <li>Selected subspecialty services (e.g., cardiology, orthopedics, urology, pulmonology — depending on hospital capability)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Department (IGD)</li>
                        <li>ICU</li>
                        <li>NICU and/or PICU (depending on capacity)</li>
                        <li>HCU (High Care Unit)</li>
                        <li>Trauma stabilization capability</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>CT Scan (standard in most Class B hospitals)</li>
                        <li>Advanced ultrasound</li>
                        <li>Comprehensive laboratory services</li>
                        <li>Blood bank/transfusion unit</li>
                        <li>Endoscopy services</li>
                        <li>Basic interventional procedures</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Multiple operating theatres</li>
                        <li>Major general surgery capability</li>
                        <li>Orthopedic and obstetric surgery capability</li>
                        <li>Dialysis unit (in most provincial hospitals)</li>
                        <li>Chemotherapy (in hospitals with oncology service)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>24-hour pharmacy</li>
                        <li>Central Sterile Supply Department (CSSD)</li>
                        <li>Medical rehabilitation service</li>
                        <li>Nutrition & dietetics service</li>
                        <li>Medical records system</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class B Hospital Role</strong>
            <ul>
                <li>Provincial-level referral hospital</li>
                <li>Secondary escalation point in the BPJS referral system (from Class C/D)</li>
                <li>Regional center for specialist services</li>
                <li>Stabilization and management center for moderate to complex cases</li>
                <li>Supporting teaching hospital (in many provinces)</li>
            </ul>
        </P>
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
            <h5 class="modal-title" id="disclaimerLabel">Class A — National Referral Hospital</h5>
        </div>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            A Class A Hospital (Rumah Sakit Kelas A), regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH), represents the highest hospital classification in Indonesia.
        </p>
        <p class="p-modal text-justify">
            Class A hospitals function as national or apex referral centers within Indonesia’s tiered healthcare and Badan Penyelenggara Jaminan Sosial (BPJS) referral system, provide the most comprehensive range of specialist and subspecialist services, supported by advanced diagnostic, therapeutic, critical care capability, and large bed capacity. Serving as national and/or top-tier referral centers within the healthcare system.
        </p>
        <p class="p-modal text-justify">
            Class A hospitals manage highly complex, multidisciplinary medical and surgical cases referred from Class B, C, and D hospitals, and frequently function as teaching and research institutions.
        </p>
        <p class="p-modal text-justify">
            This classification applies to both public and private hospitals that meet the highest standards of infrastructure, medical personnel, equipment, and service capability.
        </p>
        <p class="p-modal text-justify">
            Public Class A hospitals generally participate in BPJS Kesehatan, receive BPJS patients primarily through referral from Class B hospitals or directly in emergency cases.
        </p>
        <p class="p-modal text-justify">
            Private Class A hospitals may or may not contract with BPJS. Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
            <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 250 inpatient beds. Major national referral hospitals often exceed 500–1,000 beds depending on scope and regional demand.
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>4 basic specialists: Internal Medicine, Surgery, Pediatrics, Obstetrics & Gynecology (Ob/gyn)</li>
                        <li>Full range of medical subspecialties (cardiology, nephrology, pulmonology, oncology, etc.)</li>
                        <li>Full range of surgical subspecialties (neurosurgery, cardiothoracic, orthopedics, urology, plastic surgery, etc.)</li>
                        <li>Comprehensive non-surgical specialties (neurology, psychiatry, dermatology, ENT, ophthalmology, rehabilitation medicine)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Department (IGD)</li>
                        <li>ICU, NICU, PICU, HCU</li>
                        <li>Advanced trauma and resuscitation capability</li>
                        <li>Disaster response readiness</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>CT Scan & MRI</li>
                        <li>Cath Lab (cardiac catheterization)</li>
                        <li>Advanced radiology & interventional radiology</li>
                        <li>Full clinical & anatomical pathology labs</li>
                        <li>Blood bank</li>
                        <li>Endoscopy & advanced imaging</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Multiple fully equipped operating theatres</li>
                        <li>Cardiac & neurosurgery capability</li>
                        <li>Dialysis units</li>
                        <li>Chemotherapy & oncology services</li>
                        <li>Radiotherapy (in comprehensive centers)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>24-hour pharmacy</li>
                        <li>CSSD (Central Sterile Supply Department)</li>
                        <li>Medical rehabilitation center</li>
                        <li>Medical gas system</li>
                        <li>Electronic medical records (in modern facilities)</li>
                        <li>Nutrition & dietetics service</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class A Hospital Role</strong>
            <ul>
                <li>National and/or top-tier referral hospital</li>
                <li>Highest escalation level in BPJS referral system</li>
                <li>Teaching hospital for medical students, residents, and specialists</li>
                <li>Research and clinical innovation center</li>
                <li>Complex case management center (multi-disciplinary cases)</li>
                <li>National disaster and emergency medical support hub</li>
            </ul>
        </P>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police1Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="{{ asset('images/Gegana.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Bomb Squad / Special Police Force — Pasukan Gegana</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                Gegana is a specialized force under Korbrimob Polri. It is not only a bomb squad. Bomb disposal is one of its core capabilities, but Gegana has a wider special police role covering counter-terrorism, hostage rescue, armed high-risk incidents, bomb disposal, tactical technical support, and response to chemical, biological, radiological, and nuclear threats.
            </p>
            <p class="p-modal text-justify">
                For public-facing police structure, Gegana can be described as Polri’s specialist Brimob unit for bomb disposal and high-risk special police operations. Its bomb-disposal element is commonly associated with Jibom, or Penjinakan Bom, while other Gegana capabilities include Wanteror for counter-terrorism and KBR/KBRN for hazardous-material and CBRN-related threats.
            </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police2Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="{{ asset('images/Brimob.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Mobile Brigade Corps — Korps Brigade Mobil / Brimob</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                The Mobile Brigade Corps, or Brimob, is Polri’s national paramilitary-style tactical police force for high-intensity internal security operations. It is deployed when regular territorial police units require heavier tactical capability, including riot control, armed criminal threats, counter-insurgency support, counter-terrorism support, disaster response, and other major security disturbances.
            </p>
            <p class="p-modal text-justify">
                Brimob is part of the Indonesian National Police, not the Indonesian Armed Forces. It remains a police force, but it is trained, equipped, and organized for high-risk operations that require rapid deployment, disciplined formations, tactical weapons capability, and specialist field support. At national level, Brimob is organized under Korbrimob Polri. At regional level, Brimob capability is represented by Satbrimob Polda.
            </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police3Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="{{ asset('images/Layer4.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Polsek</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
             <p class="p-modal text-justify">
                <strong>Command level:</strong> Frontline police command
            </p>
            <p class="p-modal text-justify">
                <strong>Head rank:</strong> Depends on Polsek classification. Type A is usually led by AKBP, Type B by Kompol, Type C by AKP, and Type D by a Police Inspector-level officer.
            </p>
            <p class="p-modal text-justify">
                <strong>Administrative equivalent:</strong> District / kecamatan
            </p>
            <p class="p-modal text-justify">
                <strong>Commander:</strong> Kapolsek
            </p>
            <p class="p-modal text-justify">
                Polsek is the main frontline police command at district level. It handles direct community-facing policing, first response, incident reporting, local investigation support, patrol, public assistance, local public-order control, and coordination with subdistrict authorities.
            </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police4Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer3.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Polres</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
             <p class="p-modal text-justify">
                <strong>Command level:</strong> Regency, city, or metropolitan police command
            </p>
            <p class="p-modal text-justify">
                <strong>Head position and rank:</strong> Kapolres / Kapolresta / Kapolrestabes / Kapolres Metro (Komisaris Besar Polisi / Ajun Komisaris Besar Polisi)
            </p>
            <p class="p-modal text-justify">
                <strong>Administrative equivalent:</strong> Regency, city, large city, or major metropolitan police area
                Polres is the main district-level police command below Polda. It supervises Polsek and provides law enforcement, investigation, public-order management, traffic policing, emergency response, and public service at regency or city level.
                Polres-level territorial commands are classified into Type A, Type B, Type C, and Type D. The classification reflects urban scale, population, operational complexity, threat level, administrative importance, and workload.
            </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police5Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer2.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Polda</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                <strong>Command level:</strong> Top territorial police command
            </p>
            <p class="p-modal text-justify">
                <strong>Head rank:</strong> Usually Inspektur Jenderal Polisi for Type A Polda; Brigadir Jenderal Polisi for Type B Polda
            </p>
            <p class="p-modal text-justify">
                <strong>Administrative equivalent:</strong> Province or police area
            </p>
            <p class="p-modal text-justify">
                <strong>Commander:</strong> Kepala Polda (Kapolda)
            </p>
            <p class="p-modal text-justify">
                Polda is the main territorial police command under Mabes Polri. It carries out Polri duties in its assigned police area. A Polda supervises Municipality Police (Polres/Polresta/Polrestabes/Polres Metro), specialist directorates, Police Mobile Brigade (Brimob) units, traffic units, intelligence units, investigation units, and public-security elements.
            </p>
            <p class="p-modal text-justify">
                Primary responsibilities include:
            </p>
            <ul>
                <li>Regional law enforcement</li>
                <li>Public-security and public-order control</li>
                <li>Criminal investigation support</li>
                <li>Intelligence and early warning</li>
                <li>Traffic policing</li>
                <li>Community policing</li>
                <li>Regional patrol and emergency response</li>
                <li>Coordination with governors, regional military commands, prosecutors, courts, and local agencies</li>
            </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer1.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Polri HQ (National)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                <strong>Command level:</strong> National headquarters
            </p>
            <p class="p-modal text-justify">
                <strong>Location:</strong> Jakarta
            </p>
            <p class="p-modal text-justify">
                <strong>Head rank:</strong> Police General (Jenderal Polisi) at Kapolri level
            </p>
            <p class="p-modal text-justify">
                Subordinate senior leadership: Komisaris Jenderal, Inspektur Jenderal, Brigadir Jenderal, and senior commissioner-level officers
            </p>
            <p class="p-modal text-justify">
                Mabes Polri is the national command, planning, administrative, operational, and coordination center of Polri. It supports Kapolri and Wakapolri in controlling the full police institution, from national-level operational corps to Polda, Polres, and Polsek.
            </p>
      </div>
    </div>
  </div>
</div>

@endsection

@push('service')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const policeData = {!! json_encode([
        'id'        => $police->id,
        'name'      => $police->name_police,
        'latitude'  => $police->latitude,
        'longitude' => $police->longitude,
        'icon'      => $police->icon ?? '',
        'location'  => $police->location ?? '',
        'telephone' => $police->telephone ?? '',
        'website'   => $police->website ?? '',
    ]) !!};

    const nearbyHospitals = @json($nearbyHospitals);
    const nearbyAirports = @json($nearbyAirports);
    const nearbyPolices = @json($nearbyPolices);
    const nearbyEmbassy = @json($nearbyEmbassy);
    let radiusKm = 100; // default radius

    let map, mainMarker, radiusCircle, directionsService, directionsRenderer;
    let nearbyMarkersGroup = [];
    let searchLocation = null;
    let searchMarker = null;

    // === ICON DEFAULT ===
    const DEFAULT_HOSPITAL_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
    const DEFAULT_AIRPORT_ICON_URL  = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
    const DEFAULT_MAIN_POLICE_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
    const DEFAULT_POLICE_ICON_URL = 'https://png.pngtree.com/png-vector/20221211/ourmid/pngtree-minimal-location-map-icon-logo-symbol-vector-design-transparent-background-png-image_6520892.png';
    const DEFAULT_EMBASSY_ICON_URL = '/images/embassy-icon-new.png';

    // === INISIALISASI PETA ===
    function initializeMap() {
        const center = new google.maps.LatLng(policeData.latitude, policeData.longitude);
        map = new google.maps.Map(document.getElementById('map'), {
            center: center,
            zoom: 11,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: true,
            fullscreenControl: true,
            streetViewControl: false
        });

        const directionsPanel = document.createElement('div');
        directionsPanel.id = 'directionsPanel';
        directionsPanel.style.width = '370px';
        directionsPanel.style.maxHeight = '450px';
        directionsPanel.style.overflowY = 'auto';
        directionsPanel.style.backgroundColor = 'white';
        directionsPanel.style.display = 'none';
        directionsPanel.style.boxShadow = '0 4px 20px rgba(0,0,0,0.2)';
        directionsPanel.style.borderRadius = '12px';
        directionsPanel.style.margin = '10px';
        directionsPanel.style.padding = '0';
        directionsPanel.style.fontSize = '13px';

        // Header
        const dpHeader = document.createElement('div');
        dpHeader.className = 'dp-header';
        dpHeader.innerHTML = `
            <div class="dp-header-title">
                <i class="fas fa-route"></i> Route Directions
            </div>
            <button class="dp-close-btn" title="Close">
                <i class="fas fa-times"></i>
            </button>
        `;
        directionsPanel.appendChild(dpHeader);

        // Content area (Google renders steps here)
        const dpContent = document.createElement('div');
        dpContent.style.padding = '10px';
        directionsPanel.appendChild(dpContent);

        // Close button handler
        dpHeader.querySelector('.dp-close-btn').addEventListener('click', () => {
            directionsPanel.style.display = 'none';
            directionsRenderer.setDirections({routes: []});
        });

        google.maps.event.addDomListener(directionsPanel, 'click', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'dblclick', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'mousedown', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'touchstart', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'wheel', e => e.stopPropagation());

        map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(directionsPanel);

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            panel: dpContent,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#1a73e8',
                strokeOpacity: 0.8,
                strokeWeight: 5
            }
        });
    }

    function addMainPoliceAndCircle() {
        mainMarker = new google.maps.Marker({
            position: new google.maps.LatLng(policeData.latitude, policeData.longitude),
            map: map,
            icon: {
                url: DEFAULT_MAIN_POLICE_ICON_URL,
                scaledSize: new google.maps.Size(25, 41)
            },
            title: policeData.name
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `<b>${policeData.name}</b><br>This is the main police station.`
        });

        mainMarker.addListener('click', () => {
            infoWindow.open(map, mainMarker);
        });

        radiusCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.1,
            map: map,
            center: { lat: parseFloat(policeData.latitude), lng: parseFloat(policeData.longitude) },
            radius: radiusKm * 1000
        });
    }

    function clearNearbyMarkers() {
        for (let i = 0; i < nearbyMarkersGroup.length; i++) {
            nearbyMarkersGroup[i].setMap(null);
        }
        nearbyMarkersGroup = [];
    }

    // === Tambahkan Marker Sekitar ===
    function addNearbyMarkers(data, defaultIconUrl, type, filters = {}) {
        data.forEach(item => {
            const distance = calculateDistance(
                policeData.latitude, policeData.longitude,
                item.latitude, item.longitude
            );
            if (distance > radiusKm) return;

            // Filter hospital
            if (type === 'Hospital' && filters.hospitalLevels?.length > 0) {
                const level = (item.facility_level || '').toLowerCase();
                const allowed = filters.hospitalLevels.map(l => l.toLowerCase());
                if (!allowed.includes(level)) return;
            }

            // Filter airport
            if (type === 'Airport' && filters.airportClassifications?.length > 0) {
                const categories = (item.category || '').split(',').map(c => c.trim().toLowerCase());
                const allowed = filters.airportClassifications.map(c => c.toLowerCase());
                if (!categories.some(cat => allowed.includes(cat))) return;
            }

            // Filter police
            if (type === 'Police' && filters.policeCategories?.length > 0) {
                const categories = (item.category || '').split(',').map(c => c.trim().toLowerCase());
                const allowed = filters.policeCategories.map(c => c.toLowerCase());
                if (!categories.some(cat => allowed.includes(cat))) return;
            }

            const isPolice = type === 'Police';
            const iconSize = isPolice ? new google.maps.Size(12, 12) : new google.maps.Size(24, 24);

            const marker = new google.maps.Marker({
                position: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) },
                map: map,
                icon: {
                    url: item.icon || defaultIconUrl,
                    scaledSize: iconSize
                }
            });

            const name = item.name || item.airport_name || item.name_police || item.name_embassiees || 'N/A';
            const level = item.facility_level || item.category || '';

            let url = '#';
            if (type === 'Airport') url = `/airports/${item.id}/detail`;
            else if (type === 'Hospital') url = `/hospitals/${item.id}`;
            else if (type === 'Police') url = `/police/${item.id}/detail`;
            else if (type === 'Embassy') url = `/embassiees/${item.id}/detail`;

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="font-size:13px;">
                        <a href="${url}" target="_blank">${name}</a><br>
                        ${level}<br>
                        <strong>Distance:</strong> ${distance.toFixed(2)} km<br>
                        <button class="btn btn-sm btn-primary mt-2"
                            onclick="getDirection(${item.latitude}, ${item.longitude})">
                            Get Direction
                        </button>
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            nearbyMarkersGroup.push(marker);
        });
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    // === NEARBY HOTELS (shown once a location is searched) ===
    let categoryMarkers   = [];
    let activeCategoryBtn = null;
    let categoryBar       = null;

    function resetCategoryBtn(btn) {
        btn.style.background  = '#fff';
        btn.style.color       = '#222';
        btn.style.borderColor = 'rgba(0,0,0,0.12)';
    }

    function clearCategoryMarkers() {
        categoryMarkers.forEach(m => m.setMap(null));
        categoryMarkers = [];
    }

    function showNearbyCategory(type, label) {
        if (!searchLocation) return;
        clearCategoryMarkers();

        const center  = new google.maps.LatLng(searchLocation.lat, searchLocation.lng);
        const service = new google.maps.places.PlacesService(map);

        const iconColors = { lodging: '#1a73e8' };
        const color = iconColors[type] || '#555';

        function makeSvgIcon(col) {
            const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='32' height='40' viewBox='0 0 32 40'>`
                      + `<path d='M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24S32 28 32 16C32 7.16 24.84 0 16 0z' fill='${col}'/>`
                      + `<circle cx='16' cy='16' r='7' fill='#fff'/>`
                      + `</svg>`;
            return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
        }

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

                const marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map,
                    title: place.name,
                    icon: { url: makeSvgIcon(color), scaledSize: new google.maps.Size(32, 40) },
                    animation: google.maps.Animation.DROP
                });

                const dist     = google.maps.geometry.spherical.computeDistanceBetween(center, place.geometry.location);
                const distText = dist >= 1000 ? (dist / 1000).toFixed(1) + ' km' : Math.round(dist) + ' m';
                const rating   = place.rating ? `⭐ ${place.rating.toFixed(1)}` : '';
                const destLat  = place.geometry.location.lat();
                const destLng  = place.geometry.location.lng();

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="font-size:13px;min-width:190px;">
                            <h5 style="border-bottom:1px solid #ccc;margin:0 0 6px;font-size:14px;">${place.name}</h5>
                            <div style="color:#666;font-size:12px;margin-bottom:3px;">${label}</div>
                            ${rating  ? `<div style="font-size:12px;">${rating}</div>` : ''}
                            <div style="margin-top:4px;font-size:12px;color:#555;"> ${distText} from search location</div>
                            <button class="btn btn-sm btn-primary mt-2"
                                onclick="getDirection(${destLat}, ${destLng})">
                                Get Direction
                            </button>
                        </div>`
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                categoryMarkers.push(marker);
            });
        });
    }

    function setupNearbyCategoryBar() {
        categoryBar = document.createElement('div');
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

        map.controls[google.maps.ControlPosition.TOP_CENTER].push(categoryBar);
    }

    // === ROUTING ===
    window.getDirection = function(lat, lng) {
        const origin = searchLocation
            ? new google.maps.LatLng(searchLocation.lat, searchLocation.lng)
            : new google.maps.LatLng(policeData.latitude, policeData.longitude);

        directionsService.route({
            origin: origin,
            destination: new google.maps.LatLng(lat, lng),
            travelMode: 'DRIVING'
        }, (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                const panel = document.getElementById('directionsPanel');
                if(panel) panel.style.display = 'block';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Route Not Found',
                    text: status === 'ZERO_RESULTS'
                        ? 'No driving route could be found between these two locations.'
                        : 'Directions request failed (' + status + ').',
                    confirmButtonColor: '#d33'
                });
            }
        });
    };

    function fitMapToBounds() {
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(policeData.latitude, policeData.longitude));
        if (searchLocation) {
            bounds.extend(new google.maps.LatLng(searchLocation.lat, searchLocation.lng));
        }
        nearbyMarkersGroup.forEach(m => bounds.extend(m.getPosition()));

        const circleBounds = radiusCircle.getBounds();
        if(circleBounds) {
            bounds.union(circleBounds);
        }

        map.fitBounds(bounds);
    }

    function updateMarkers(filterType, hospitalLevels, airportClassifications, policeCategories) {
        clearNearbyMarkers();
        if (radiusCircle) radiusCircle.setMap(null);
        addMainPoliceAndCircle();

        const filters = { hospitalLevels, airportClassifications, policeCategories };
        if (filterType === 'hospital') {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
        } else if (filterType === 'airport') {
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
        } else if (filterType === 'police') {
            addNearbyMarkers(nearbyPolices, DEFAULT_POLICE_ICON_URL, 'Police', filters);
        } else if (filterType === 'embassy') {
            addNearbyMarkers(nearbyEmbassy, DEFAULT_EMBASSY_ICON_URL, 'Embassy', filters);
        } else {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
            addNearbyMarkers(nearbyPolices, DEFAULT_POLICE_ICON_URL, 'Police', filters);
            addNearbyMarkers(nearbyEmbassy, DEFAULT_EMBASSY_ICON_URL, 'Embassy', filters);
        }

        fitMapToBounds();
    }

    // === FILTER CONTROL ===
    function setupFilterControl() {
        const container = document.createElement('div');
        container.className = 'p-2 bg-white rounded';
        container.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
        container.style.width = '220px';
        container.style.maxHeight = '75vh';
        container.style.overflowY = 'auto';
        container.style.marginRight = '10px';
        container.style.marginTop = '10px';
        container.style.cursor = 'default';

        container.innerHTML = `
            <h6><strong>Filter</strong></h6>

            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Search Location</strong>
            <div style="position:relative;margin-top:5px;">
                <input type="text" id="gmSearchInput" class="form-control form-control-sm"
                    placeholder="Search Location..." autocomplete="off" style="padding-right:28px;">
                <i class="fas fa-times" id="gmClearBtn"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);color:#70757a;font-size:13px;cursor:pointer;display:none;"></i>
            </div>

            <label><strong>Radius:</strong> <span id="radiusLabel">${radiusKm}</span> km</label>
            <input type="range" id="radiusRange" min="10" max="500" step="10" value="${radiusKm}" class="form-range mb-2" style="display:block;width:100%;">

            <select id="mapFilter" class="form-select form-select-sm mb-2" style="display:block;width:100%;">
                <option value="all">Show All</option>
                <option value="hospital">Hospitals</option>
                <option value="airport">Aviation</option>
                <option value="police">Police</option>
                <option value="embassy">Embassy</option>
            </select>

            <div id="hospitalFilter" style="display:none;">
                <strong>Facility Level:</strong><br>
                ${['Class A','Class B','Class C','Class D','Public Health Center (PUSKESMAS)']
                    .map(lvl => `<label style="display:block;font-size:13px;">
                        <input type="checkbox" name="hospitalLevel" value="${lvl}"> ${lvl}
                    </label>`).join('')}
            </div>

            <div id="airportFilter" style="display:none;margin-top:8px;">
                <strong>Category:</strong><br>
                ${['International','Domestic','Military','Regional','Private']
                    .map(cls => `<label style="display:block;font-size:13px;">
                        <input type="checkbox" name="airportClass" value="${cls}"> ${cls}
                    </label>`).join('')}
            </div>

            <div id="policeFilter" style="display:none;margin-top:8px;">
                <strong>Police Category:</strong><br>
                ${[
                    'Indonesian National Police (Polri) HQ',
                    'Provincial Police (Polda)',
                    'Municipality Police (Polres)',
                    'District Police (Polsek)',
                    'Police Mobile Brigade (Brimob)',
                    'Police Bomb Squad (Gegana)'
                ].map(cat => `
                    <label style="display:block;font-size:13px;">
                        <input type="checkbox" name="policeCategory" value="${cat}"> ${cat}
                    </label>
                `).join('')}
            </div>

            <button id="resetFilter" class="btn btn-sm btn-secondary mt-3 w-100">Reset Filter</button>
        `;

        // Prevent events from passing to the map
        google.maps.event.addDomListener(container, 'click', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'dblclick', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'mousedown', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'touchstart', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'wheel', e => e.stopPropagation());

        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(container);

        const radiusSlider = container.querySelector('#radiusRange');
        const radiusLabel = container.querySelector('#radiusLabel');
        radiusSlider.addEventListener('input', () => {
            radiusKm = parseInt(radiusSlider.value);
            radiusLabel.textContent = radiusKm;
            refreshFilters();
        });

        const filterSelect = container.querySelector('#mapFilter');
        const hospitalDiv = container.querySelector('#hospitalFilter');
        const airportDiv = container.querySelector('#airportFilter');
        const policeDiv = container.querySelector('#policeFilter');
        const resetBtn = container.querySelector('#resetFilter');

        function refresh() {
            const selectedType = filterSelect.value;
            const selectedHospitalLevels = Array.from(container.querySelectorAll('input[name="hospitalLevel"]:checked')).map(el => el.value);
            const selectedAirportClasses = Array.from(container.querySelectorAll('input[name="airportClass"]:checked')).map(el => el.value);
            const selectedPoliceCategories = Array.from(container.querySelectorAll('input[name="policeCategory"]:checked')).map(el => el.value);
            updateMarkers(selectedType, selectedHospitalLevels, selectedAirportClasses, selectedPoliceCategories);
        }

        filterSelect.addEventListener('change', () => {
            const val = filterSelect.value;
            hospitalDiv.style.display = val === 'hospital' ? 'block' : 'none';
            airportDiv.style.display = val === 'airport' ? 'block' : 'none';
            policeDiv.style.display = val === 'police' ? 'block' : 'none';
            refresh();
        });

        container.querySelectorAll('input[name="hospitalLevel"]').forEach(chk => chk.addEventListener('change', refresh));
        container.querySelectorAll('input[name="airportClass"]').forEach(chk => chk.addEventListener('change', refresh));
        container.querySelectorAll('input[name="policeCategory"]').forEach(chk => chk.addEventListener('change', refresh));

        resetBtn.addEventListener('click', () => {
            container.querySelectorAll('input[type="checkbox"]').forEach(chk => chk.checked = false);
            filterSelect.value = 'all';
            hospitalDiv.style.display = 'none';
            airportDiv.style.display = 'none';
            policeDiv.style.display = 'none';
            radiusKm = 100;
            radiusSlider.value = radiusKm;
            radiusLabel.textContent = radiusKm;

            const gmInput = container.querySelector('#gmSearchInput');
            if(gmInput) gmInput.value = '';

            if (searchMarker) {
                searchMarker.setMap(null);
                searchMarker = null;
            }
            searchLocation = null;

            if (categoryBar) categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            directionsRenderer.setDirections({routes: []});
            const panel = document.getElementById('directionsPanel');
            if(panel) panel.style.display = 'none';

            refresh();
        });

        return container;
    }

    function refreshFilters() {
        const selectedType = document.querySelector('#mapFilter')?.value || 'all';
        const selectedHospitalLevels = Array.from(document.querySelectorAll('input[name="hospitalLevel"]:checked')).map(el => el.value);
        const selectedAirportClasses = Array.from(document.querySelectorAll('input[name="airportClass"]:checked')).map(el => el.value);
        const selectedPoliceCategories = Array.from(document.querySelectorAll('input[name="policeCategory"]:checked')).map(el => el.value);
        updateMarkers(selectedType, selectedHospitalLevels, selectedAirportClasses, selectedPoliceCategories);
    }

    // === SEARCH LOCATION CONTROL (now part of the filter panel) ===
    function setupSearchControl(filterContainer) {
        const input = filterContainer.querySelector('#gmSearchInput');
        const clearBtn = filterContainer.querySelector('#gmClearBtn');
        if (!input || !clearBtn) return;

        input.addEventListener('keydown', (e) => {
            if(e.key === 'Enter') e.preventDefault();
        });

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        // The input lives inside a custom map control, so Google's ".pac-container"
        // dropdown (appended to <body> with position:absolute) ends up clipped/
        // hidden behind the map's own control panes. Force position:fixed and keep
        // re-applying it, since Google resets the container's inline style on every
        // prediction update (a one-shot fix gets silently overwritten).
        let pacContainer = null;

        function fixPacPosition() {
            if (!pacContainer) return;
            if (pacContainer.parentElement !== document.body) {
                document.body.appendChild(pacContainer);
            }
            const rect = input.getBoundingClientRect();
            pacContainer.style.position = 'fixed';
            pacContainer.style.zIndex = '2147483647';
            pacContainer.style.top = (rect.bottom + 2) + 'px';
            pacContainer.style.left = rect.left + 'px';
            pacContainer.style.width = rect.width + 'px';
            pacContainer.style.visibility = 'visible';
            pacContainer.style.opacity = '1';
            pacContainer.style.pointerEvents = 'auto';
        }

        function claimPacContainer() {
            if (pacContainer) return true;
            pacContainer = document.querySelector('.pac-container');
            if (pacContainer) {
                fixPacPosition();
                new MutationObserver(fixPacPosition).observe(
                    pacContainer, { attributes: true, attributeFilter: ['style'] }
                );
                return true;
            }
            return false;
        }

        const pacObserver = new MutationObserver(() => claimPacContainer());
        pacObserver.observe(document.body, { childList: true, subtree: true });

        // Fallback in case Google created ".pac-container" before the observer
        // above started watching (a MutationObserver only reports *future*
        // mutations, so a container created earlier would otherwise be missed).
        if (!claimPacContainer()) {
            const pollId = setInterval(() => {
                if (claimPacContainer()) clearInterval(pollId);
            }, 200);
            setTimeout(() => clearInterval(pollId), 10000);
        }

        window.addEventListener('scroll', fixPacPosition, true);
        window.addEventListener('resize', fixPacPosition);
        input.addEventListener('focus', fixPacPosition);
        input.addEventListener('input', fixPacPosition);

        input.addEventListener('input', (e) => {
            if (e.target.value.length > 0) {
                clearBtn.style.display = 'block';
            } else {
                clearBtn.style.display = 'none';
            }
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            clearBtn.style.display = 'none';
            input.focus();
            if (pacContainer) pacContainer.style.display = 'none';

            if (searchMarker) {
                searchMarker.setMap(null);
                searchMarker = null;
            }
            searchLocation = null;

            if (categoryBar) categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            directionsRenderer.setDirections({routes: []});
            const panel = document.getElementById('directionsPanel');
            if(panel) panel.style.display = 'none';
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                return;
            }

            if (searchMarker) searchMarker.setMap(null);

            searchMarker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                icon: {
                    url: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    scaledSize: new google.maps.Size(25, 41)
                }
            });

            const lat = place.geometry.location.lat();
            const lon = place.geometry.location.lng();
            searchLocation = { lat: lat, lng: lon };

            if (categoryBar) categoryBar.style.display = 'flex';

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="font-size:13px;">
                        <b>${place.name}</b><br>
                        <small>Lat: ${lat.toFixed(5)}, Lng: ${lon.toFixed(5)}</small><br>
                        <button class="btn btn-sm btn-primary mt-2"
                            onclick="getDirection(${policeData.latitude}, ${policeData.longitude})">
                            Get Direction to Main Police Station
                        </button>
                    </div>
                `
            });

            infoWindow.open(map, searchMarker);
            searchMarker.addListener('click', () => {
                infoWindow.open(map, searchMarker);
            });

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(14);
            }
        });
    }

    // === JALANKAN ===
    initializeMap();
    addMainPoliceAndCircle();
    updateMarkers('all', [], [], []);
    const filterContainer = setupFilterControl();
    setupSearchControl(filterContainer);
    setupNearbyCategoryBar();
});
</script>

@endpush
