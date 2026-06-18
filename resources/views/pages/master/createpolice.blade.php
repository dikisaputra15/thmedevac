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
                <label>Police Classification (Global)</label><br>

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
                <label>Police Classification (Country)</label><br>

                <input type="hidden" name="icon" id="icon">
                <div class="form-check form-check-inline">
                    <input class="form-check-input category-radio" type="radio" name="category" value="National Police (HQ)" data-icon="{{ asset('images/dot-blue-ring-royal-papua.png') }}">
                    <img src="{{ asset('images/dot-blue-ring-royal-papua.png') }}" style="width:12px; height:12px;">
                    <label class="form-check-label">National Police (HQ)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input category-radio" type="radio" name="category" value="Regional Police" data-icon="{{ asset('images/dot-red.png') }}">
                    <img src="{{ asset('images/dot-red.png') }}" style="width:12px; height:12px;">
                    <label class="form-check-label">Regional Police</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input category-radio" type="radio" name="category" value="Provincial Police" data-icon="{{ asset('images/dot-orange-ppc.png') }}">
                    <img src="{{ asset('images/dot-orange-ppc.png') }}" style="width:12px; height:12px;">
                    <label class="form-check-label">Provincial Police</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input category-radio" type="radio" name="category" value="City Police Station" data-icon="{{ asset('images/dot-green.png') }}">
                    <img src="{{ asset('images/dot-green.png') }}" style="width:12px; height:12px;">
                    <label class="form-check-label">City Police Station</label>
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
document.querySelectorAll('.category-radio').forEach(radio => {
    radio.addEventListener('change', function() {

        document.getElementById('icon').value = this.dataset.icon;

    });
});
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
