@extends('Karyawan.layouts.index')

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New Permission</div>

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
                    <p>Mohon Maaf anda Absensi Pulang 2 kali</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var id = {{ $id }};

        function onScanSuccess(decodedText, decodedResult) {
            // handle the scanned code as you like, for example:
            console.log(`Code matched = ${decodedText}`, decodedResult);
            html5QrcodeScanner.clear().then(_ => {
                let id1 = decodedText;
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var url = '/dashboardkaryawan/Absensi/QrCode/Pulang/scan/' + id;
                console.log(url)
                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
                        _token: CSRF_TOKEN,
                        qr_code_pulang: id1
                    },
                    success: function(response) {
                        console.log(response);
                        alert('berhasil');
                        window.location.href = '/dashboardkaryawan/Absensi/QrCode';
                    },
                    error: function(error) {
                        $('#errorModal').modal('show');
                        console.log(error); // Perbaikan: Menggunakan error, bukan response
                        setTimeout(function() {
                            $('#errorModal').modal('hide');
                            window.location.href = '/dashboardkaryawan/Absensi/QrCode';
                        }, 2500);
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
