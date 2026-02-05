@extends('layouts.master-admin')

@section('title','Airport')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Airport List</h3>
    </div>

     <div id="session" data-session="{{ session('success') }}"></div>

    <div class="card-body">
         <div class="form-group">
            <a href="{{route('airportdata.create')}}" class="btn btn-primary">Add Data</a>
        </div>
            <table id="airportTable" class="table" style="width:100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>ID</th>
                      <th>Airport Name</th>
                      <th>City</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>

            </table>
    </div>
</div>
@endsection

@push('service')
  <script>
        $(document).ready(function() {

            let session = $('#session').data('session');

            if (session) {
                Swal.fire({
                    title: "Sukses!",
                    text: session,
                    icon: "success",
                    timer: 3000,
                    showConfirmButton: true
                });
            }

            // table data
            $('#airportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/airportdata",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'airport_name',
                        name: 'airport_name'
                    },
                    {
                        data: 'citi',
                        name: 'cities.city'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

              // Event listener untuk tombol hapus
            $('#airportTable').on('click', '.delete-btn', function () {
                var roleId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "Data will be deleted permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/airportdata/' + roleId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response){
                            if(response.success == 1){
                                alert("Record deleted.");
                                var oTable = $('#airportTable').dataTable();
                                oTable.fnDraw(false);
                            }else{
                                    alert("Invalid ID.");
                                }
                            },

                        });
                    }
                });
            });

           $(document).on('click', '.status-btn', function() {
                var id = $(this).data('id');
                var buttonText = $(this).text(); // bisa "Publish" atau "Unpublish"

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to " + buttonText + " this airport?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, ' + buttonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/airportdata/' + id + '/toggle-status',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if(res.success) {
                                    Swal.fire(
                                        'Updated!',
                                        'Airport status changed to: ' + (res.status ? 'Publish' : 'Unpublish'),
                                        'success'
                                    );
                                    $('#airportTable').DataTable().ajax.reload();
                                }
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
