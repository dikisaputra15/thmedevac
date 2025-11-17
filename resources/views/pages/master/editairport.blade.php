@extends('layouts.master-admin')

@section('title','Edit Airport')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Edit Airport</h3>
    </div>

<form action="{{ route('airportdata.update', $airport->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Airport Name</label>
                <input type="text" class="form-control" name="airport_name" value="{{ $airport->airport_name; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Address</label>
                <input type="text" class="form-control" name="address" value="{{ $airport->address; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Municipality</label>
                <select class="form-control" name="province_id">
                    <?php
                        foreach ($provinces as $prov) {

                            if ($prov->id==$airport->province_id) {
                                $select="selected";
                            }else{
                                $select="";
                            }

                        ?>
                            <option <?php echo $select; ?> value="<?php echo $prov->id;?>"><?php echo $prov->provinces_region; ?></option>

                    <?php } ?>

                </select>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Administrative Post</label>
                <select class="form-control" name="city" id="city">
                    <?php
                        foreach ($cities as $city) {

                            if ($city->id==$airport->city_id) {
                                $select="selected";
                            }else{
                                $select="";
                            }

                        ?>
                            <option <?php echo $select; ?> value="<?php echo $city->id;?>"><?php echo $city->city; ?></option>

                    <?php } ?>

                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Latitude</label>
                <input type="text" class="form-control" name="latitude" value="{{ $airport->latitude; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Longitude</label>
                <input type="text" class="form-control" name="longitude" value="{{ $airport->longitude; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Category</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="international" value="International"
                        {{ in_array('International', $category) ? 'checked' : '' }}>
                    <label class="form-check-label" for="international">International</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="domestic" value="Domestic"
                        {{ in_array('Domestic', $category) ? 'checked' : '' }}>
                    <label class="form-check-label" for="domestic">Domestic</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="regionaldomestic" value="Regional Domestic"
                        {{ in_array('Regional Domestic', $category) ? 'checked' : '' }}>
                    <label class="form-check-label" for="regional domestic">Regional Domestic</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="military" value="Military"
                        {{ in_array('Military', $category) ? 'checked' : '' }}>
                    <label class="form-check-label" for="military">Military</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="private" value="Private"
                        {{ in_array('Private', $category) ? 'checked' : '' }}>
                    <label class="form-check-label" for="private">Private</label>
                </div>
            </div>
        </div>

          <div class="col-md-12">
            <div class="form-group">
                <label>Edit Military / Police</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="military_branch" value="Army"
                        {{ old('Army', $airport->military_branch ?? '') == 'Army' ? 'checked' : '' }}>
                    <label class="form-check-label">Army</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="military_branch" value="Navy"
                        {{ old('Navy', $airport->military_branch ?? '') == 'Navy' ? 'checked' : '' }}>
                    <label class="form-check-label">Navy</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="military_branch" value="Air Force"
                        {{ old('Air Force', $airport->military_branch ?? '') == 'Air Force' ? 'checked' : '' }}>
                    <label class="form-check-label">Air Force</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="military_branch" value="Marine"
                        {{ old('Marine', $airport->military_branch ?? '') == 'Marine' ? 'checked' : '' }}>
                    <label class="form-check-label">Marine</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="military_branch" value="Police"
                        {{ old('Police', $airport->military_branch ?? '') == 'Police' ? 'checked' : '' }}>
                    <label class="form-check-label">Police</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Icon</label><br>

                @php
                    $icons = [
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png', 'label' => 'International'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png', 'label' => 'Domestic'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png', 'label' => 'Regional Domestic'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png', 'label' => 'Military'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png', 'label' => 'Combined (Civil - Military)'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png', 'label' => 'Private'],
                    ];
                @endphp

                @foreach($icons as $icon)
                    <label style="margin-right: 15px;">
                        <input type="radio" name="icon" value="{{ $icon['url'] }}"
                            @checked(old('icon', $airport->icon ?? '') === $icon['url'])>
                        <img src="{{ $icon['url'] }}" style="width:24px; height:24px;"> {{ $icon['label'] }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit IATA Code</label>
                <input type="text" class="form-control" name="iata_code" value="{{ $airport->iata_code; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit ICAO Code</label>
                <input type="text" class="form-control" name="icao_code" value="{{ $airport->icao_code; }}">
            </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Hours Of Operation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote6" name="hrs_of_operation">
                    <?php echo $airport->hrs_of_operation; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Distance To
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote7" name="distance_from">
                    <?php echo $airport->distance_from; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Elevation</label>
                <input type="text" class="form-control" name="elevation" value="{{ $airport->elevation; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Time Zone</label>
                <input type="text" class="form-control" name="time_zone" value="{{ $airport->time_zone; }}">
            </div>
        </div>

        <!-- <div class="col-md-12">
            <div class="form-group">
                <label>Edit Operator</label>
                <input type="text" class="form-control" name="operator" value="{{ $airport->operator; }}">
            </div>
        </div> -->

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Magnetic Variation</label>
                <input type="text" class="form-control" name="magnetic_variation" value="{{ $airport->magnetic_variation; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Beacon</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="beacon" id="beaconYes" value="Yes"
                        {{ old('beacon', $airport->beacon ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label" for="beaconYes">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="beacon" id="beaconNo" value="No"
                        {{ old('beacon', $airport->beacon ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label" for="beaconNo">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="beacon" id="beaconUnknown" value="Data not identified"
                        {{ old('beacon', $airport->beacon ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label" for="beaconUnknown">Data not identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Max Aircraft Capability</label>
                <input type="text" class="form-control" name="max_aircraft_capability" value="{{ $airport->max_aircraft_capability; }}">
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Directorate General of Civil Aviation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote24" name="dgoca">
                     <?php echo $airport->dgoca; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Operator</label><br>

                @php
                    $selectedOperator = old('operator', $airport->operator ?? '');
                @endphp

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="State-Owned Enterprise"
                        {{ $selectedOperator == 'State-Owned Enterprise' ? 'checked' : '' }}>
                    <label class="form-check-label">State-Owned Enterprise</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Local-Level Government"
                        {{ $selectedOperator == 'Local-Level Government' ? 'checked' : '' }}>
                    <label class="form-check-label">Local-Level Government</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Mission / Community / Organization"
                        {{ $selectedOperator == 'Mission / Community / Organization' ? 'checked' : '' }}>
                    <label class="form-check-label">Mission / Community / Organization</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Private"
                        {{ $selectedOperator == 'Private' ? 'checked' : '' }}>
                    <label class="form-check-label">Private</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Military (Army)"
                        {{ $selectedOperator == 'Military (Army)' ? 'checked' : '' }}>
                    <label class="form-check-label">Military (Army)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Military (Navy)"
                        {{ $selectedOperator == 'Military (Navy)' ? 'checked' : '' }}>
                    <label class="form-check-label">Military (Navy)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Military (Air Force)"
                        {{ $selectedOperator == 'Military (Air Force)' ? 'checked' : '' }}>
                    <label class="form-check-label">Military (Air Force)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Police"
                        {{ $selectedOperator == 'Police' ? 'checked' : '' }}>
                    <label class="form-check-label">Police</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="operator" value="Customs"
                        {{ $selectedOperator == 'Customs' ? 'checked' : '' }}>
                    <label class="form-check-label">Customs</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Link
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote25" name="soao">
                     <?php echo $airport->soao; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Other Airport Info
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote14" name="other_reference_website">
                    <?php echo $airport->other_reference_website; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Note
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote11" name="note">
                    <?php echo $airport->note; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Telephone
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote20" name="telephone">
                    <?php echo $airport->telephone; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Fax
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote21" name="fax">
                    <?php echo $airport->fax; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Email
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote22" name="email">
                    <?php echo $airport->email; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Website
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote23" name="website">
                    <?php echo $airport->website; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Nearest Accommodation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote12" name="nearest_accommodation">
                    <?php echo $airport->nearest_accommodation; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Air Traffic</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="air_traffic" id="air_trafficYes" value="Available"
                        {{ old('air_traffic', $airport->air_traffic ?? '') == 'Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="air_trafficYes">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="air_traffic" id="air_trafficNo" value="Not Available"
                        {{ old('air_traffic', $airport->air_traffic ?? '') == 'Not Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="air_trafficNo">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="air_traffic" id="air_trafficUnknown" value="Data not identified"
                        {{ old('air_traffic', $airport->air_traffic ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label" for="air_trafficUnknown">Data not identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Meteorology Services</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="meteorology_services" id="meteorology_servicesYes" value="Available"
                        {{ old('meteorology_services', $airport->meteorology_services ?? '') == 'Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="meteorology_servicesYes">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="meteorology_services" id="meteorology_servicesNo" value="Not Available"
                        {{ old('meteorology_services', $airport->meteorology_services ?? '') == 'Not Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="meteorology_servicesNo">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="meteorology_services" id="meteorology_servicesUnknown" value="Data not identified"
                        {{ old('meteorology_services', $airport->meteorology_services ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label" for="meteorology_servicesUnknown">Data not identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Aviation Fuel Depot</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aviation_fuel_depot" id="aviation_fuel_depotYes" value="Available"
                        {{ old('aviation_fuel_depot', $airport->aviation_fuel_depot ?? '') == 'Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="aviation_fuel_depotYes">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aviation_fuel_depot" id="aviation_fuel_depotNo" value="Not Available"
                        {{ old('aviation_fuel_depot', $airport->aviation_fuel_depot ?? '') == 'Not Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="aviation_fuel_depotNo">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aviation_fuel_depot" id="aviation_fuel_depotUnknown" value="Data not identified"
                        {{ old('aviation_fuel_depot', $airport->aviation_fuel_depot ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label" for="aviation_fuel_depotUnknown">Data not identified</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Internet Services</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="internet_services" id="internet_servicesYes" value="Available"
                        {{ old('internet_services', $airport->internet_services ?? '') == 'Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="internet_servicesYes">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="internet_services" id="internet_servicesNo" value="Not Available"
                        {{ old('internet_services', $airport->internet_services ?? '') == 'Not Available' ? 'checked' : '' }}>
                    <label class="form-check-label" for="internet_servicesNo">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="internet_services" id="internet_servicesUnknown" value="Data not identified"
                        {{ old('internet_services', $airport->internet_services ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label" for="internet_servicesUnknown">Data not identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Supplies Equipment
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote8" name="supplies_eqipment">
                    <?php echo $airport->supplies_eqipment; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Public Facilities</label>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Terminal building" {{ in_array('Terminal building', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Terminal building</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Check-in counter" {{ in_array('Check-in counter', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Check-in counter</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Airport customs/immigration" {{ in_array('Airport customs/immigration', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Airport customs/immigration</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Waiting area" {{ in_array('Waiting area', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Waiting area</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="ATM" {{ in_array('ATM', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">ATM</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Money changer" {{ in_array('Money changer', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Money changer</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Retail stores and restaurant" {{ in_array('Retail stores and restaurant', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Retail stores and restaurant</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Prayer room/Mushola" {{ in_array('Prayer room/Mushola', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Prayer room/Mushola</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Restrooms" {{ in_array('Restrooms', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Restrooms</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Parking" {{ in_array('Parking', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Parking</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_facilities[]" value="Limited facilities available or no facilities available" {{ in_array('Limited facilities available or no facilities available', $public_facilities) ? 'checked' : '' }}>
                    <label class="form-check-label">Limited facilities available or no facilities available</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Public Facilities
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote9">
                    <?php echo $airport->public_facilities; ?>
                </textarea>

            </div>

          </div>
        </div>


        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Public Transportation</label>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="Airport shuttle" {{ in_array('Airport shuttle', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Airport shuttle</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name=public_transportation[]" value="Airport bus" {{ in_array('Airport bus', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Airport bus</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="Airport train" {{ in_array('Airport train', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Airport train</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="Airport Taxi" {{ in_array('Airport Taxi', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Airport Taxi</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="Car rental" {{ in_array('Car rental', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Car rental</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="Travel vehicle" {{ in_array('Travel vehicle', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Travel vehicle</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="Online transportation" {{ in_array('Online transportation', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Online transportation</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="Taxi bike" {{ in_array('Taxi bike', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">Taxi bike</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_transportation[]" value="None identified at site" {{ in_array('None identified at site', $public_transportation) ? 'checked' : '' }}>
                    <label class="form-check-label">None identified at site</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Public Transportation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote10">
                    <?php echo $airport->public_transportation; ?>
                </textarea>

            </div>

          </div>
        </div>


         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Navigation Aids (NAVAIDs)
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote2" name="navigation_aids">
                    <?php echo $airport->navigation_aids; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Communication
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote18" name="communication">
                    <?php echo $airport->communication; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Runway Edge Lights</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="runway_edge_lights" id="runway_edge_lightsYes" value="Yes"
                        {{ old('runway_edge_lights', $airport->runway_edge_lights ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label" for="runway_edge_lightsYes">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="runway_edge_lights" id="runway_edge_lightsNo" value="No"
                        {{ old('runway_edge_lights', $airport->runway_edge_lights ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label" for="runway_edge_lightsNo">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="runway_edge_lights" id="runway_edge_lightsUnknown" value="Data not identified"
                        {{ old('runway_edge_lights', $airport->runway_edge_lights ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label" for="runway_edge_lightsUnknown">Data not identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Reil</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reil" id="reilYes" value="Yes"
                        {{ old('reil', $airport->reil ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label" for="reilYes">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reil" id="reilNo" value="No"
                        {{ old('reil', $airport->reil ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label" for="reilNo">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reil" id="reilUnknown" value="Data not identified"
                        {{ old('reil', $airport->reil ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label" for="reilUnknown">Data not identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Runways
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote17" name="runways">
                    <?php echo $airport->runways; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Domestic Airlines / Destination
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote4" name="domestic_flights">
                    <?php echo $airport->domestic_flights; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit International Flight
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote16" name="international_flight">
                    <?php echo $airport->international_flight; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Spesific Airport Flight Information
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote13" name="other_flight_information">
                    <?php echo $airport->other_flight_information; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit General Flight Information
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote26" name="general_flight_information">
                    <?php echo $airport->general_flight_information; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Aircraft Information
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote27" name="aircraft_information">
                    <?php echo $airport->aircraft_information; ?>
                </textarea>

            </div>

          </div>
        </div>

         <!-- <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Flight Information
              </h3>
            </div>

            <div class="card-body">

                <textarea id="summernote15" name="flight_information">

                </textarea>

            </div>

          </div>
        </div> -->

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Nearest Police Station
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote3" name="nearest_police_station">
                    <?php echo $airport->nearest_police_station; ?>
                </textarea>

            </div>

          </div>
        </div>

          <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Nearest Medical Facility
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote5" name="nearest_medical_facility">
                    <?php echo $airport->nearest_medical_facility; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Nearest Airports
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote" name="nearest_airport">
                    <?php echo $airport->nearest_airport; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Nearby Airport Navigation Aids
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote19" name="nearby_airport_navigation_aids">
                    <?php echo $airport->nearby_airport_navigation_aids; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Image</label>
                <input type="file" class="form-control" name="image">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Data</button>
    </div>
</form>
</div>
@endsection

@push('service')
<script>
  $(function () {
    // Summernote
    $('#summernote').summernote()
    $('#summernote2').summernote()
    $('#summernote3').summernote()
    $('#summernote4').summernote()
    $('#summernote5').summernote()
    $('#summernote6').summernote()
    $('#summernote7').summernote()
    $('#summernote8').summernote()
    $('#summernote9').summernote()
    $('#summernote10').summernote()
    $('#summernote11').summernote()
    $('#summernote12').summernote()
    $('#summernote13').summernote()
    $('#summernote14').summernote()
    $('#summernote15').summernote()
    $('#summernote16').summernote()
    $('#summernote17').summernote()
    $('#summernote18').summernote()
    $('#summernote19').summernote()
    $('#summernote20').summernote()
    $('#summernote21').summernote()
    $('#summernote22').summernote()
    $('#summernote23').summernote()
    $('#summernote24').summernote()
    $('#summernote25').summernote()
    $('#summernote26').summernote()
    $('#summernote27').summernote()
    $('#summernote28').summernote()

  })
</script>

<script>
    $('#province').on('change', function () {
        var provinceId = $(this).val();
        if (provinceId) {
            $.ajax({
                url: '/get-cities/' + provinceId,
                type: 'GET',
                success: function (data) {
                    $('#city').empty();
                    $('#city').append('<option value="">-- Choosse City/Regency --</option>');
                    $.each(data, function (key, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.city + '</option>');
                    });
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">-- Choosse City/Regency  --</option>');
        }
    });
</script>
@endpush
