@extends('Karyawan.layouts.index')
@section('container')
    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <script>
                setTimeout(function() {
                    document.getElementById('logout-form').submit();
                }, 5000); // 5000 milliseconds = 5 seconds
            </script>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Absensi Live Location</h6>
        </div>
        <div class="card-body">
            <a href="{{ url('/dashboardkaryawan/Absensi/LiveLocation/datang') }}" class="btn btn-success float-right mb-3">
                <i class="fas fa-plus"></i> Create Absensi Live Location
            </a>
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu Datang</th>
                            <th>Waktu_Pulang</th>
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
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('/dashboardkaryawan/Absensi/LiveLocation/data') }}',
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
                        data: 'waktu_datang_LiveLoc',
                        name: 'waktu_datang_LiveLoc'
                    },
                    {
                        data: 'waktu_pulang',
                        name: 'waktu_pulang',
                        render: function(data) {
                            return data ? data :
                                'belum absensi'; // Jika data tidak null, gunakan nilainya. Jika null, gunakan "belum absensi".
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<a href="javascript:void(0)" class="pulang-btn" data-id="' + row
                                .absensi_id +
                                '">Absensi Pulang</a>';
                        }

                    },
                ]
            });
        });
    </script>
@endsection
