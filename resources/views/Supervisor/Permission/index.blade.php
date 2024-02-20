@extends('Supervisor.layouts.index')

@section('container')
    @foreach ($karyawanUsers as $karyawanUser)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Attendence for {{ $karyawanUser->name }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="user-table-{{ $karyawanUser->id }}" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Keterangan</th>
                                <th>Tipe</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Akhir</th>
                                <th>Status</th>
                                <th>Batas Waktu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#user-table-{{ $karyawanUser->id }}').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('get.recap.Permission.supervisor', ['userId' => $karyawanUser->id]) }}",
                    columns: [
                        {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'explanation',
                            name: 'explanation'
                        },
                        {
                            data: 'permission_type',
                            name: 'permission_type'
                        },
                        {
                            data: 'start_date',
                            name: 'start_date'
                        },
                        {
                            data: 'end_date',
                            name: 'end_date'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'period',
                            name: 'period',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return '<button onclick="acceptAttendance(' + full.id +
                                    ')" class="btn btn-success">Accept</button>' +
                                    '<button onclick="rejectAttendance(' + full.id +
                                    ')" class="btn btn-danger ml-2">Reject</button>';
                            }
                        },
                    ],
                });
            });

            function acceptAttendance(id) {
                $.ajax({
                    url: "{{ url('/kasubag/accept') }}/" + id,
                    type: 'GET', // Change to GET
                    dataType: 'json',
                    success: function(data) {
                        alert(data.message);
                        location.reload();
                        $('#user-table-' + id).DataTable().ajax.reload();
                        // You may need to reload or update the DataTable after accepting attendance
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function rejectAttendance(id) {
                $.ajax({
                    url: "{{ url('/kasubag/reject') }}/" + id,
                    type: 'GET', // Change to GET
                    dataType: 'json',
                    success: function(data) {
                        alert(data.message);
                        location.reload();
                        $('#user-table-' + id).DataTable().ajax.reload();
                        // You may need to reload or update the DataTable after rejecting attendance
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        </script>
    @endforeach
@endsection
