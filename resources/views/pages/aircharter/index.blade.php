@extends('layouts.master')

@section('title','More Details')

@push('styles')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>

    .btn-danger{
        background-color:#395272;
        border-color: transparent;
    }

    .btn-danger:hover{
        background-color:#5686c3;
        border-color: transparent;
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

    th {
      text-align: center;
      vertical-align: middle;
      font-weight: bold;
    }
    td {
      vertical-align: middle;
    }
    .table thead th {
      text-align: center;
      vertical-align: middle;
      font-weight: bold;
      font-size: 14px;
      color: #000;
    }
    .table td{
        vertical-align: middle;
    }
    .header-charter { background-color: #d9ceb2; }
    .header-charter-2 { background-color: #ede8db; }
    .header-charter-3 { background-color: #f4f1e9; }
    .header-fleet-2 { background-color: #f4f0e6; }
    .header-fleet-3 { background-color: #faf8f3; }
    .header-aircraft { background-color: #a7a18f; }
    .header-aircraft-2 { background-color: #c9c5bb; }
    .header-aircraft-3 { background-color: #dfdcd6; }
    .header-rotary { background-color: #d5d1c9; }
    .header-rotary-2 { background-color: #e6e3df; }
    .header-service  { background-color: #c0cec6; }
    .header-service-2  { background-color: #d5dfd9; }
    .header-service-3  { background-color: #e6ece8; }
    .header-service-cargo-1  { background-color: #e2eae5; }
    .header-service-cargo-2  { background-color: #eef2ef; }
    .header-service-medevac-1  { background-color: #e8efeb; }
    .header-service-medevac-2  { background-color: #f3f8f5; }
    .header-other    { background-color: #b6c8ca; }
    .header-other-2    { background-color: #cedadc; }
    .header-other-3    { background-color: #e2e9ea; }
    .header-icon {
      display: block;
      margin: 0 auto;
    }
    .icon.minus{
        font-size: 36px;
        color: #9b9b9b;
        font-weight: bold;
    }
    .notes-col {
        text-align: left !important;
    }

</style>

@endpush

@section('conten')

<div class="card">
     <div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">
        <div class="d-flex gap-2 align-items-center">
            <h2 class="fw-bold">AIR CHARTER INFORMATION - Thailand </h2>
        </div>

        <div class="d-flex gap-2 ms-auto">
             <!-- Button 5 -->
            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3">
                <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                  <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <!-- Button 7 -->
            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3">
            <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                <small>Embassies</small>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <?php echo $airport->charter_info; ?>
            </div>
        </div>
    </div>

</div>


@endsection

@push('service')


@endpush
