@extends('layouts.master-admin')

@section('title','Add Police')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Add Police</h3>
    </div>

<form action="{{ route('policedata.store') }}" method="POST">
    @csrf
    <div class="card-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="name_police">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Region</label>
                <select class="form-control" name="province_id" id="province">
                        <option value="0">-Choosse Region-</option>
                    @foreach($provinces as $prov)
                        <option value="{{$prov->id}}">{{$prov->provinces_region}}</option>
                    @endforeach
                </select>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label for="city">City</label>
                <select name="city" id="city" class="form-control">
                    <option value="">-Choose City-</option>
                </select>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Latitude</label>
                <input type="text" class="form-control" name="latitude">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Longitude</label>
                <input type="text" class="form-control" name="longitude">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Location</label>
                <input type="text" class="form-control" name="location">
            </div>
        </div>

            <div class="col-md-12">
            <div class="form-group">
                <label>Police Level</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 1">
                    <label class="form-check-label">Layer 1</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 2">
                    <label class="form-check-label">Layer 2</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 3">
                    <label class="form-check-label">Layer 3</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 4">
                    <label class="form-check-label">Layer 4</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Police Classification</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="National HQ">
                    <label class="form-check-label">National HQ</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="Regional / Macro Command">
                    <label class="form-check-label">Regional / Macro Command</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="Provincial / Territorial Command">
                    <label class="form-check-label">Provincial / Territorial Command</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="classification" value="Local Police Station">
                    <label class="form-check-label">Local Police Station</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Police Category</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Royal Thai Police (National HQ)">
                    <label class="form-check-label">Royal Thai Police (National HQ)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Provincial Police Regions">
                    <label class="form-check-label">Provincial Police Regions</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Provincial Police HQ">
                    <label class="form-check-label">Provincial Police HQ</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="category" value="Police Station">
                    <label class="form-check-label">Police Station</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Telephone
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote" name="telephone">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Fax
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote4" name="fax">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Email
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote2" name="email">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Website
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote3" name="website">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Hours of Operation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote5" name="hrs_of_operation">
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
                    <label style="margin-right: 15px;">
                        <input type="radio" name="icon" value="{{ $icon['url'] }}">
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
                    $('#city').append('<option value="">-- Choosse City --</option>');
                    $.each(data, function (key, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.city + '</option>');
                    });
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">-- Choosse City  --</option>');
        }
    });
</script>
@endpush
