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
                <video id="preview"></video>
            </div>
        </div>
    </div>
    <!-- Other HTML content -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <!-- Instascan library -->

    <script type="text/javascript">
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        scanner.addListener('scan', function(content) {
            console.log(content);

            // Send AJAX request to handle scanned QR code
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url = '{{ url('/dashboardkaryawan/Absensi/QrCode/datang/scan/Store') }}';
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    qr_code_datang: content
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
        });

        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
    </script>
@endsection
