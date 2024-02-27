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
            {{-- <a href="#" id="addTiket" class="btn btn-success float-right mb-3" data-toggle="modal"
                data-target="#scannerModal">
                <i class="fas fa-plus"></i> Open Camera
            </a> --}}
            <a href="{{ url('/dashboardkaryawan/Absensi/QrCode/datang') }}" class="btn btn-success float-right mb-3">
                <i class="fas fa-plus"></i> Create Absensi Live Location
            </a>
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu Datang</th>
                            <th>Waktu Pulang</th>
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
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scannerModalLabel">Scan QR Code</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="width: 100%;" id="qr-reader"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="pulangModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Absensi Pulang</h5>
                </div>
                <div class="modal-body">
                    <p>Mohon Maaf anda Absensi pulang 2 kali</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('/dashboardkaryawan/Absensi/QrCode/data') }}',
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
                        data: 'waktu_datang_Qr_code',
                        name: 'waktu_datang_Qr_code'
                    },
                    {
                        data: 'waktu_pulang_Qr_code',
                        name: 'waktu_pulang_Qr_code',
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
    {{-- <script>
        $(document).ready(function() {
            var html5QrCode;
            var codeQr;
            // Inisialisasi dan konfigurasi scanner QR Code
            $('#scannerModal').on('show.bs.modal', function(e) {
                html5QrCode = new Html5Qrcode("qr-reader");
                html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: 250
                }, qrCodeMessage => {
                    console.log(qrCodeMessage);
                    codeQr = qrCodeMessage;
                    $('#scannerModal').modal('hide');
                    $('#addScanModal').modal('show');
                    // add
                    scanQRCode(codeQr);
                    html5QrCode.stop().then(ignore => {
                        $('#scannerModal').modal('hide');
                    }).catch(err => {
                        console.log(`Unable to stop the QR Code scanner, reason: ${err}`);
                    });
                }).catch(err => {
                    console.log(`Unable to start the QR Code scanner, reason: ${err}`);
                });
            });

            $('#scannerModal').on('hide.bs.modal', function(e) {
                if (html5QrCode) {
                    html5QrCode.stop().then(ignore => {}).catch(err => {
                        console.log(`Unable to stop the QR Code scanner, reason: ${err}`);
                    });
                }
            });

            // Fungsi untuk menangani hasil scan QR Code
            function scanQRCode(qrCode) {
                $.ajax({
                    url: '/dashboardkaryawan/Absensi/QrCode/scan/Store',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        qr_code_datang: qrCode
                    },
                    success: function(response) {
                        console.log(response.message);
                        // Tampilkan modal addScanModal
                        $('#addScanModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

            // Menangani submit form di addScanModal
            $('#submitAddForm').click(function(event) {
                event.preventDefault();
                // var qrCode = codeQr;
                var formData = new FormData($('#addDataForm')[0]);
                formData.append('qr_code_datang', codeQr);
                console.log(codeQr);
                $.ajax({
                    url: "{{ url('/dashboardkaryawan/Absensi/QrCode/scan/Store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        $('#addScanModal').modal('hide');
                    },
                    error: function(err) {
                        console.error(err.responseText);
                    }
                });
            });

        });
        $(document).ready(function() {
            var html5QrCode;

            // Inisialisasi dan konfigurasi scanner QR Code
            $('#scannerModal').on('show.bs.modal', function(e) {
                html5QrCode = new Html5Qrcode("qr-reader");
                html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: 250
                }, qrCodeMessage => {
                    console.log(qrCodeMessage);
                    // Tambahkan logika untuk mengirimkan data QR code ke endpoint yang sesuai melalui AJAX
                    $.ajax({
                        url: '/dashboardkaryawan/Absensi/QrCode/scan/Store',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            qr_code_datang: qrCodeMessage
                        },
                        success: function(response) {
                            console.log(response.message);
                            // Tampilkan pesan sukses atau lakukan tindakan lain sesuai kebutuhan
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            // Tampilkan pesan error atau lakukan tindakan lain sesuai kebutuhan
                        }
                    });
                }).catch(err => {
                    console.log(`Unable to start the QR Code scanner, reason: ${err}`);
                });
            });

            $('#scannerModal').on('hide.bs.modal', function(e) {
                if (html5QrCode) {
                    html5QrCode.stop().then(ignore => {}).catch(err => {
                        console.log(`Unable to stop the QR Code scanner, reason: ${err}`);
                    });
                }
            });
        });
    </script> --}}
@endsection
