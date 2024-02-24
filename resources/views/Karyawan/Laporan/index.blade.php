@extends('Karyawan.layouts.index')
@section('container')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Absensi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu Datang</th>
                            <th>Waktu_Pulang</th>
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
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('/dashboardkaryawan/Laporan/Absensi/data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'waktu_datang',
                        name: 'waktu_datang'
                    },
                    {
                        data: 'waktu_pulang',
                        name: 'waktu_pulang',
                        render: function(data) {
                            return data ? data :
                                'belum absensi'; // Jika data tidak null, gunakan nilainya. Jika null, gunakan "belum absensi".
                        }
                    },
                ]
            });
        });
    </script>
@endsection
