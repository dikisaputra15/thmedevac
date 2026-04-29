@extends('layouts.master-admin')

@section('title','Edit Police')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Edit Police</h3>
    </div>

<form action="{{ route('policedata.update', $police->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Name</label>
                <input type="text" class="form-control" name="name_police" value="{{ $police->name_police; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Region</label>
                <select class="form-control" name="province_id" id="city">
                    <?php
                        foreach ($provinces as $prov) {

                            if ($prov->id==$police->province_id) {
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
                <label>Edit Township</label>
                <select class="form-control" name="city" id="city">
                    <?php
                        foreach ($cities as $city) {

                            if ($city->id==$police->city_id) {
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
                <input type="text" class="form-control" name="latitude" value="{{$police->latitude}}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Longitude</label>
                <input type="text" class="form-control" name="longitude" value="{{$police->longitude}}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Location</label>
                <input type="text" class="form-control" name="location" value="{{ $police->location; }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Police Level</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 1"
                        {{ old('Layer 1', $police->level ?? '') == 'Layer 1' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 1</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 2"
                        {{ old('Layer 2', $police->level ?? '') == 'Layer 2' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 2</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 3"
                        {{ old('Layer 3', $police->level ?? '') == 'Layer 3' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 3</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 4"
                        {{ old('Layer 4', $police->level ?? '') == 'Layer 4' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 4</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Police Classification</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="National HQ"
                        {{ old('National HQ', $police->classification ?? '') == 'National HQ' ? 'checked' : '' }}>
                    <label class="form-check-label">National HQ</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="Regional / Macro Command"
                        {{ old('Regional / Macro Command', $police->classification ?? '') == 'Regional / Macro Command' ? 'checked' : '' }}>
                    <label class="form-check-label">Regional / Macro Command</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="Provincial / Territorial Command"
                        {{ old('Provincial / Territorial Command', $police->classification ?? '') == 'Provincial / Territorial Command' ? 'checked' : '' }}>
                    <label class="form-check-label">Provincial / Territorial Command</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="Local Police Station"
                        {{ old('Local Police Station', $police->classification ?? '') == 'Local Police Station' ? 'checked' : '' }}>
                    <label class="form-check-label">Local Police Station</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Police Category</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Royal Thai Police (National HQ)"
                        {{ old('Royal Thai Police (National HQ)', $police->category ?? '') == 'Royal Thai Police (National HQ)' ? 'checked' : '' }}>
                    <label class="form-check-label">Royal Thai Police (National HQ)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Provincial Police Regions"
                        {{ old('Provincial Police Regions', $police->category ?? '') == 'Provincial Police Regions' ? 'checked' : '' }}>
                    <label class="form-check-label">Provincial Police Regions</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Provincial Police HQ"
                        {{ old('Provincial Police HQ', $police->category ?? '') == 'Provincial Police HQ' ? 'checked' : '' }}>
                    <label class="form-check-label">Provincial Police HQ</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Police Station"
                        {{ old('Police Station', $police->category ?? '') == 'Police Station' ? 'checked' : '' }}>
                    <label class="form-check-label">Police Station</label>
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

                <textarea id="summernote" name="telephone">
                    <?php echo $police->telephone; ?>
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

                <textarea id="summernote4" name="fax">
                    <?php echo $police->fax; ?>
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

                <textarea id="summernote2" name="email">
                    <?php echo $police->email; ?>
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

                <textarea id="summernote3" name="website">
                    <?php echo $police->website; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Hours of Operation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote5" name="hrs_of_operation">
                    <?php echo $police->hrs_of_operation; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
    <div class="form-group">
        <label>Icon</label><br>

        @php
            $icons = [
                ['url' => asset('images/dot-blue-ring-royal-papua.png'), 'label' => 'Royal Thai Police (National HQ)'],
                ['url' => asset('images/dot-red.png'), 'label' => 'Provincial Police Regions'],
                ['url' => asset('images/dot-orange-ppc.png'), 'label' => 'Provincial Police HQ'],
                ['url' => asset('images/dot-green.png'), 'label' => 'Police Station'],
            ];
        @endphp

        @foreach($icons as $icon)
            <label style="margin-right: 15px; cursor:pointer;">
                <input
                    type="radio"
                    name="icon"
                    value="{{ $icon['url'] }}"
                    {{ $police->icon == $icon['url'] ? 'checked' : '' }}
                >

                <img src="{{ $icon['url'] }}" style="width:17px; height:17px;">
                {{ $icon['label'] }}
            </label>
        @endforeach

    </div>
</div>

        <button type="submit" class="btn btn-primary">Submit</button>
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
                    $('#city').append('<option value="">-- Choosse Township --</option>');
                    $.each(data, function (key, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.city + '</option>');
                    });
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">-- Choosse Township  --</option>');
        }
    });
</script>
@endpush
