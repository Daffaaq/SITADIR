@extends('Karyawan.layouts.index')

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New Attendance</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div id="reader" style="height: 300px;"></div>
                <input type="hidden" id="qr_code_result" name="qr_code_result" value="">
            </div>
        </div>
    </div>
    <!-- Other HTML content -->

    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="pulangModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Absensi Datang</h5>
                </div>
                <div class="modal-body">
                    <p>Error sam</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="pulangModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Absensi Datang</h5> <!-- Ganti id modal title -->
                </div>
                <div class="modal-body">
                    <p>Mohon Maaf anda Absensi Datang 2 kali</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script type="text/javascript">
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);
            $('#qr_code_result').val(decodedText); // Set value to hidden input
            let id = decodedText;
            html5QrcodeScanner.clear().then(_ => {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var url = '{{ url('/dashboardkaryawan/Absensi/QrCode/datang/scan/Store') }}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: "POST",
                        _token: CSRF_TOKEN,
                        qr_code_datang: id
                    },
                    success: function(response) {
                        console.log(response);
                        alert('berhasil');
                        window.location.href = '/dashboardkaryawan/Absensi/QrCode';
                    },
                    error: function(error) {
                        if (error.responseJSON && error.responseJSON.message) {
                            $('#infoModal').modal('show');
                            console.log(error.responseJSON.message);
                            setTimeout(function() {
                                $('#infoModal').modal('hide');
                                window.location.href = '/dashboardkaryawan/Absensi/QrCode';
                            }, 2500);
                        } else {
                            $('#errorModal').modal('show');
                            console.log(error);
                            setTimeout(function() {
                                $('#errorModal').modal('hide');
                                window.location.href = '/dashboardkaryawan/Absensi/QrCode';
                            }, 2500);
                        }
                    }
                });
            }).catch(error => {
                alert('something wrong');
            });
        }

        let config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            },
            rememberLastUsedCamera: true,
            // Only support camera scan type.
            supportedScanTypes: [
                Html5QrcodeScanType.SCAN_TYPE_CAMERA,
                Html5QrcodeScanType.SCAN_TYPE_FILE
            ]
        };

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", config, /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection
