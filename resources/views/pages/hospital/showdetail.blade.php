@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'Papua New Guinea Medical Facility')

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

     .leaflet-routing-container-hide .leaflet-routing-collapse-btn
    {
        left: 8px;
        top: 8px;
    }

    .leaflet-control-container .leaflet-routing-container-hide {
        width: 48px;
        height: 48px;
    }

    /* Classification */
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
</style>

@endpush

@section('conten')

<div class="card">

    <div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">

        <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $hospital->name }}</h2>
            <span class="fw-bold"><b>Global Classification:</b> {{ $hospital->facility_category }} | <b>Country Classification:</b> {{ $hospital->facility_level }}</span>
        </div>

        <div class="d-flex gap-2 ms-auto">

            <a href="{{ url('hospitals') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <a href="{{ url('hospitals/clinic') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/clinic/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-medical-facility-white.png') }}" style="width: 18px; height: 24px;">
                <small>Clinical</small>
            </a>

            <a href="{{ url('hospitals/emergency') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/emergency/'.$hospital->id) ? 'active' : '' }}">
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
            <small><i>Last Updated {{ $hospital->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('hospitaldata.edit', $hospital->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-general-info.png') }}" style="width: 24px; height: 24px;"> General Medical Facility Info</div>
                <div class="card-body overflow-auto">
                    <p>
                        <strong>Status:</strong> {{ $hospital->status }}
                    </p>
                    <p>
                        <strong>Number Of Beds:</strong> {{ $hospital->number_of_beds }}
                    </p>
                    <p>
                        <strong>Population Catchment:</strong> {{ $hospital->population_catchment }}
                    </p>
                    <p>
                        <strong>Ownership:</strong> {{ $hospital->ownership }}
                    </p>
                    <p>
                        <strong>Hours Of Operation:</strong><br>
                        <?php echo $hospital->hrs_of_operation; ?>
                    </p>
                    <p>
                        <strong>Note:</strong>
                        <?php echo $hospital->others; ?>
                    </p>
                    <p>
                        <strong>Medical Services Info:</strong> <?php echo $hospital->other_medical_info; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-location.png') }}" style="width: 18px; height: 24px;"> Location</div>
                <div class="card-body overflow-auto">
                    <p>
                        <strong>Address:</strong>
                        {{ $hospital->address }},
                        {{ $city->city }},
                        {{ $province->provinces_region }}, Thailand
                    </p>
                    <p>
                        <strong>Latitude:</strong> {{ $hospital->latitude }}
                    </p>
                    <p>
                        <strong>Longitude:</strong> {{ $hospital->longitude }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/contact-icon.png') }}" style="width: 24px; height: 24px;"> Contact Details</div>
                <div class="card-body overflow-auto">
                    <p>
                        <strong>Telephone:</strong> <?php echo $hospital->telephone; ?>
                    </p>
                    <p>
                        <strong>Fax:</strong> <?php echo $hospital->fax; ?>
                    </p>
                    <p>
                        <strong>Email:</strong> <?php echo $hospital->email; ?>
                    </p>
                    <p>
                        <strong>Website:</strong> <?php echo $hospital->website; ?>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-nearest-accomodation.png') }}" style="width: 24px; height: 18px;">  Nearest Accommodation</div>
                <div class="card-body overflow-auto">
                    <?php echo $hospital->nearest_accommodation; ?>
                </div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="card">

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

                <div class="card-body p-0">
                    <div id="map"></div>
                </div>

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
                <a href="https://anamai.moph.go.th/en/home">MOPH (Ministry of Public Health) website</a>
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

        <h6 class="fw-bold">
          <b>Subdistrict Health Promoting Hospitals/Center – SHPH (Primary Care Units – No Inpatient Service)</b>
        </h6>
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
          <b><u>Note:</u></b> The MOPH website can be difficult to access and may require a VPN.
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const latitude = {{ $hospital->latitude }};
    const longitude = {{ $hospital->longitude }};
    const embassyName = '{{ $hospital->name }}';

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
