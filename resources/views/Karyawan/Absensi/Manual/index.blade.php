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
            <h6 class="m-0 font-weight-bold text-primary">Absensi Manual</h6>
        </div>
        <div class="card-body">
            <a href="#" class="btn btn-success float-right mb-3" data-toggle="modal"
                data-target="#createAbsensiModal">
                <i class="fas fa-plus"></i> Create Absensi Manual
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
    <!-- Modal -->
    <div class="modal fade" id="createAbsensiModal" tabindex="-1" aria-labelledby="createAbsensiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAbsensiModalLabel">Create Absensi Manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk membuat absensi manual -->
                    <form id="createAbsensiForm" action="{{ url('/dashboardkaryawan/Absensi/Manual/Store') }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Create Absensi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal untuk menyimpan waktu pulang -->
    <div class="modal fade" id="pulangModal" tabindex="-1" role="dialog" aria-labelledby="pulangModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pulangModalLabel">Absensi Pulang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="pulangForm" action="" method="POST">
                        @csrf
                        <input type="hidden" name="absensi_id" id="absensi_id">
                        <button type="submit" class="btn btn-primary">Update Waktu Pulang</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('/dashboardkaryawan/Absensi/Manual/data') }}',
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
            $('#createAbsensiModal').on('shown.bs.modal', function() {
                var now = new Date();
                var formattedDate = now.toISOString().slice(0, 10);
                var formattedTime = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                $('#tanggal').val(formattedDate);
                $('#waktu_datang').val(formattedTime);
            });
            var absensiId; // Variabel absensiId diinisialisasi di luar fungsi click event
            $('#usersTable').on('click', '.pulang-btn', function() {
                absensiId = $(this).data('id');
                console.log(absensiId);
                $('#absensi_id').val(absensiId);
                $('#pulangModal').modal('show');
            });


            // Mengirimkan formulir untuk menyimpan waktu pulang melalui AJAX
            $('#pulangForm').on('submit', function(e) {
                e.preventDefault();

                // Check if absensiId is defined
                if (typeof absensiId !== 'undefined') {
                    // If absensiId is defined, proceed with the AJAX request
                    $(this).append('<input type="hidden" name="_method" value="PUT">');

                    var form = $(this);
                    var url = '{{ url('/dashboardkaryawan/Absensi/Manual/Pulang') }}/' + absensiId;
                    var data = form.serialize();

                    $.ajax({
                        type: 'PUT',
                        url: url,
                        data: data,
                        success: function(response) {
                            $('#pulangModal').modal('hide');
                            // Reload the table after updating the data
                            $('#usersTable').DataTable().ajax.reload();
                            window.location.reload();
                        },
                        error: function(error) {
                            alert('Error: ' + error.responseJSON.error);
                        }
                    });
                } else {
                    // If absensiId is not defined, show an error message
                    alert('Error: absensiId is not defined.');
                }
            });


        });
    </script>
@endsection
