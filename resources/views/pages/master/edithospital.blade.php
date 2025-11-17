@extends('layouts.master-admin')

@section('title','Edit Hospital')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Edit Hospital</h3>
    </div>

<form action="{{ route('hospitaldata.update', $hospital->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Hospital Name</label>
                <input type="text" class="form-control" name="name" value="{{ $hospital->name; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Address</label>
                <input type="text" class="form-control" name="address" value="{{ $hospital->address; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Municipality</label>
                <select class="form-control" name="province_id">
                    <?php
                        foreach ($provinces as $prov) {

                            if ($prov->id==$hospital->province_id) {
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

                            if ($city->id==$hospital->city_id) {
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
                <input type="text" class="form-control" name="latitude" value="{{ $hospital->latitude; }}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Longitude</label>
                <input type="text" class="form-control" name="longitude" value="{{ $hospital->longitude; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Facility Level</label>
                <select class="form-control" name="facility_level">
                    <option value="Class A" {{ old('facility_level', $hospital->facility_level ?? '') == 'Class A' ? 'selected' : '' }}>
                        Class A
                    </option>
                    <option value="Class B" {{ old('facility_level', $hospital->facility_level ?? '') == 'Class B' ? 'selected' : '' }}>
                        Class B
                    </option>
                    <option value="Class C" {{ old('facility_level', $hospital->facility_level ?? '') == 'Class C' ? 'selected' : '' }}>
                        Class C
                    </option>
                    <option value="Class D" {{ old('facility_level', $hospital->facility_level ?? '') == 'Class D' ? 'selected' : '' }}>
                        Class D
                    </option>
                    <option value="Public Health Center (PUSKESMAS)" {{ old('facility_level', $hospital->facility_level ?? '') == 'Public Health Center (PUSKESMAS)' ? 'selected' : '' }}>
                        Public Health Center (PUSKESMAS)
                    </option>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Hospital Classification Group</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="facility_category" value="Advanced"
                        {{ old('Advanced', $hospital->facility_category ?? '') == 'Advanced' ? 'checked' : '' }}>
                    <label class="form-check-label">Advanced</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="facility_category" value="Intermediete"
                        {{ old('Intermediete', $hospital->facility_category ?? '') == 'Intermediete' ? 'checked' : '' }}>
                    <label class="form-check-label">Intermediete</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="facility_category" value="Basic"
                        {{ old('Basic', $hospital->facility_category ?? '') == 'Basic' ? 'checked' : '' }}>
                    <label class="form-check-label">Basic</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="facility_category" value="Specialized"
                        {{ old('Specialized', $hospital->facility_category ?? '') == 'Specialized' ? 'checked' : '' }}>
                    <label class="form-check-label">Specialized</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Icon</label><br>

                @php
                    $icons = [
                        ['url' => 'https://id.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital-pin-red.png', 'label' => 'Class A'],
                        ['url' => 'https://id.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-blue.png', 'label' => 'Class B'],
                        ['url' => 'https://id.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-purple.png', 'label' => 'Class C'],
                        ['url' => 'https://id.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-green.png', 'label' => 'Class D'],
                        ['url' => 'https://id.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-tosca.png', 'label' => 'Public Health Center (PUSKESMAS)'],
                    ];
                @endphp

                @foreach($icons as $icon)
                    <label style="margin-right: 15px;">
                        <input type="radio" name="icon" value="{{ $icon['url'] }}"
                            @checked(old('icon', $hospital->icon ?? '') === $icon['url'])>
                        <img src="{{ $icon['url'] }}" style="width:24px; height:24px;"> {{ $icon['label'] }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit status</label>
                <input type="text" class="form-control" name="status" value="{{ $hospital->status; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Number Of Beds</label>
                <input type="text" class="form-control" name="number_of_beds" value="{{ $hospital->number_of_beds; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Population Catchment</label>
                <input type="text" class="form-control" name="population_catchment" value="{{ $hospital->population_catchment; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Ownership</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Government"
                        {{ old('Government', $hospital->ownership ?? '') == 'Government' ? 'checked' : '' }}>
                    <label class="form-check-label">Government</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Private"
                        {{ old('Private', $hospital->ownership ?? '') == 'Private' ? 'checked' : '' }}>
                    <label class="form-check-label">Private</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Community"
                        {{ old('Community', $hospital->ownership ?? '') == 'Community' ? 'checked' : '' }}>
                    <label class="form-check-label">Community</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Army"
                        {{ old('Army', $hospital->ownership ?? '') == 'Army' ? 'checked' : '' }}>
                    <label class="form-check-label">Army</label>
                </div>

                 <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Navy"
                        {{ old('Navy', $hospital->ownership ?? '') == 'Navy' ? 'checked' : '' }}>
                    <label class="form-check-label">Navy</label>
                </div>

                 <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Air Force"
                        {{ old('Air Force', $hospital->ownership ?? '') == 'Air Force' ? 'checked' : '' }}>
                    <label class="form-check-label">Air Force</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Marine"
                        {{ old('Marine', $hospital->ownership ?? '') == 'Marine' ? 'checked' : '' }}>
                    <label class="form-check-label">Marine</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ownership" value="Police"
                        {{ old('Police', $hospital->ownership ?? '') == 'Police' ? 'checked' : '' }}>
                    <label class="form-check-label">Police</label>
                </div>
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

                <textarea id="summernote4" name="hrs_of_operation">
                    <?php echo $hospital->hrs_of_operation; ?>
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

                <textarea id="summernote15" name="others">
                    <?php echo $hospital->others; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Other Medical Info
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote13" name="other_medical_info">
                    <?php echo $hospital->other_medical_info; ?>
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

                <textarea id="summernote5" name="telephone">
                    <?php echo $hospital->telephone; ?>
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

                <textarea id="summernote6" name="fax">
                    <?php echo $hospital->fax; ?>
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

                <textarea id="summernote7" name="email">
                    <?php echo $hospital->email; ?>
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

                <textarea id="summernote8" name="website">
                    <?php echo $hospital->website; ?>
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

                <textarea id="summernote11" name="nearest_accommodation">
                    <?php echo $hospital->nearest_accommodation; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Inpatient Services</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inpatient_services" value="Yes"
                        {{ old('Yes', $hospital->inpatient_services ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inpatient_services" value="No"
                        {{ old('No', $hospital->inpatient_services ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inpatient_services" value="Data not identified"
                        {{ old('Data not identified', $hospital->inpatient_services ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Outpatient Services</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="outpatient_services" value="Yes"
                        {{ old('Yes', $hospital->outpatient_services ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="outpatient_services" value="No"
                        {{ old('No', $hospital->outpatient_services ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="outpatient_services" value="Data not identified"
                        {{ old('Data not identified', $hospital->outpatient_services ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Hour Emergency Services</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hour_emergency_services" value="Yes"
                        {{ old('Yes', $hospital->hour_emergency_services ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hour_emergency_services" value="No"
                        {{ old('No', $hospital->hour_emergency_services ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hour_emergency_services" value="Data not identified"
                        {{ old('Data not identified', $hospital->hour_emergency_services ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Ambulance</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambulance" value="Yes"
                        {{ old('Yes', $hospital->ambulance ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambulance" value="No"
                        {{ old('No', $hospital->ambulance ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambulance" value="Data not identified"
                        {{ old('Data not identified', $hospital->ambulance ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Helipad</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="helipad" value="Yes"
                        {{ old('Yes', $hospital->helipad ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="helipad" value="No"
                        {{ old('No', $hospital->helipad ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="helipad" value="Data not identified"
                        {{ old('Data not identified', $hospital->helipad ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
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

                <textarea id="summernote10" name="comments">
                    <?php echo $hospital->comments; ?>
                </textarea>

            </div>

          </div>
        </div>

          <div class="col-md-12">
            <div class="form-group">
                <label>Edit ICU</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="icu" value="Yes"
                        {{ old('Yes', $hospital->icu ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="icu" value="No"
                        {{ old('No', $hospital->icu ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="icu" value="Data not identified"
                        {{ old('Data not identified', $hospital->icu ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Medical</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical" value="Yes"
                        {{ old('Yes', $hospital->medical ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical" value="No"
                        {{ old('No', $hospital->medical ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical" value="Data not identified"
                        {{ old('Data not identified', $hospital->medical ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Pediatric</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pediatric" value="Yes"
                        {{ old('Yes', $hospital->pediatric ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pediatric" value="No"
                        {{ old('No', $hospital->pediatric ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pediatric" value="Data not identified"
                        {{ old('Data not identified', $hospital->pediatric ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Maternal</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="maternal" value="Yes"
                        {{ old('Yes', $hospital->maternal ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="maternal" value="No"
                        {{ old('No', $hospital->maternal ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="maternal" value="Data not identified"
                        {{ old('Data not identified', $hospital->maternal ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Dental</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dental" value="Yes"
                        {{ old('Yes', $hospital->dental ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dental" value="No"
                        {{ old('No', $hospital->dental ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dental" value="Data not identified"
                        {{ old('Data not identified', $hospital->dental ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Optical</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="optical" value="Yes"
                        {{ old('Yes', $hospital->optical ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="optical" value="No"
                        {{ old('No', $hospital->optical ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="optical" value="Data not identified"
                        {{ old('Data not identified', $hospital->optical ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit IOC</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ioc" value="Yes"
                        {{ old('Yes', $hospital->ioc ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ioc" value="No"
                        {{ old('No', $hospital->ioc ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ioc" value="Data not identified"
                        {{ old('Data not identified', $hospital->ioc ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Laboratory</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="laboratory" value="Yes"
                        {{ old('Yes', $hospital->laboratory ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="laboratory" value="No"
                        {{ old('No', $hospital->laboratory ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="laboratory" value="Data not identified"
                        {{ old('Data not identified', $hospital->laboratory ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Pharmacy</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pharmacy" value="Yes"
                        {{ old('Yes', $hospital->pharmacy ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pharmacy" value="No"
                        {{ old('No', $hospital->pharmacy ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pharmacy" value="Data not identified"
                        {{ old('Data not identified', $hospital->pharmacy ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Medical Imaging</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical_imaging" value="Yes"
                        {{ old('Yes', $hospital->medical_imaging ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical_imaging" value="No"
                        {{ old('No', $hospital->medical_imaging ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical_imaging" value="Data not identified"
                        {{ old('Data not identified', $hospital->medical_imaging ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Medical Student Training</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical_student_training" value="Yes"
                        {{ old('Yes', $hospital->medical_student_training ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical_student_training" value="No"
                        {{ old('No', $hospital->medical_student_training ?? '') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medical_student_training" value="Data not identified"
                        {{ old('Data not identified', $hospital->medical_student_training ?? '') == 'Data not identified' ? 'checked' : '' }}>
                    <label class="form-check-label">Data not identified</label>
                </div>

            </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Medical Personel Disclimer
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote14" name="medical_personel_disclaimer">
                    <?php echo $hospital->medical_personel_disclaimer; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Doctors</label>
                <input type="text" class="form-control" name="doctors" value="{{ $hospital->doctors; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Nurses</label>
                <input type="text" class="form-control" name="nurses" value="{{ $hospital->nurses; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Dental Therapist</label>
                <input type="text" class="form-control" name="dental_therapist" value="{{ $hospital->dental_therapist; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Laboratory Assistants</label>
                <input type="text" class="form-control" name="laboratory_assistants" value="{{ $hospital->laboratory_assistants; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Community Health</label>
                <input type="text" class="form-control" name="community_health" value="{{ $hospital->community_health; }}">
            </div>
        </div>

          <div class="col-md-12">
            <div class="form-group">
                <label>Edit Health Inspectors</label>
                <input type="text" class="form-control" name="health_inspectors" value="{{ $hospital->health_inspectors; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Malaria Control Officers</label>
                <input type="text" class="form-control" name="malaria_control_officers" value="{{ $hospital->malaria_control_officers; }}">
            </div>
        </div>


        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Health Extention officer</label>
                <input type="text" class="form-control" name="health_extention_officers" value="{{ $hospital->health_extention_officers; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Casuals</label>
                <input type="text" class="form-control" name="casuals" value="{{ $hospital->casuals; }}">
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Nearest Police Station
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote" name="nearest_police_station">
                    <?php echo $hospital->nearest_police_station; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Medical Support Website
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote9" name="medical_support_website">
                    <?php echo $hospital->medical_support_website; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Evacuation Option
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote3" name="evacuation_option">
                    <?php echo $hospital->evacuation_option; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Nearest Airport
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote2" name="nearest_airport">
                    <?php echo $hospital->nearest_airfield; ?>
                </textarea>

            </div>

          </div>
        </div>

          <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Travel Agent
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote12" name="travel_agent">
                    <?php echo $hospital->travel_agent; ?>
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
