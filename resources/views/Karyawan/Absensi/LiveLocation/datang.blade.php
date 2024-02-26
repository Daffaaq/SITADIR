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

                        <form method="POST" action="{{ url('/dashboardkaryawan/Absensi/LiveLocation/datang/storeDatang') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Peta -->
                            <div id="map" style="height: 300px;"></div>

                            <!-- Longitude dan Latitude -->
                            <div class="form-group">
                                <label for="longitude_datang">Longitude:</label>
                                <input type="text" id="longitude_datang" name="longitude_datang" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="latitude_datang">Latitude:</label>
                                <input type="text" id="latitude_datang" name="latitude_datang" class="form-control">
                            </div>
                            <input type="hidden" id="longitude_datang_real" name="longitude_datang_real" value="">
                            <input type="hidden" id="latitude_datang_real" name="latitude_datang_real" value="">
                            <!-- File Surat Tugas -->
                            <div class="form-group">
                                <label for="letter_of_assignment">Letter of Assignment:</label>
                                <input type="file" class="form-control" id="letter_of_assignment"
                                    name="letter_of_assignment" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/dashboardkaryawan/Absensi/LiveLocation') }}"
                                        class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Datang</button>
                                    <button type="button" class="btn btn-info" onclick="refreshMap()">Refresh Map</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
            var map = L.map('map').setView([-6.2088, 106.8456], 13); // Inisialisasi peta dengan koordinat default
            var defaultLatLng = [-6.2088, 106.8456];

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker;

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = new L.Marker(e.latlng).addTo(map);
                document.getElementById('longitude_datang').value = e.latlng.lng;
                document.getElementById('latitude_datang').value = e.latlng.lat;
            });

            // Mengambil lokasi pengguna saat ini dan menampilkan pada peta
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;

                        map.setView([lat, lng], 13);
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        marker = new L.Marker([lat, lng]).addTo(map);
                        document.getElementById('longitude_datang').value = lng;
                        document.getElementById('latitude_datang').value = lat;
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }
            function updateHiddenInputsWithUserLocation(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            document.getElementById('longitude_datang_real').value = lng;
            document.getElementById('latitude_datang_real').value = lat;
        }

        function getLocationAndUpdateHiddenInputs() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(updateHiddenInputsWithUserLocation);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        getLocationAndUpdateHiddenInputs();
            getLocation(); // Panggil fungsi getLocation saat halaman dimuat

        // Fungsi untuk mereset peta ke lokasi awal
        function refreshMap() {
            getLocation(); // Panggil fungsi getLocation untuk mendapatkan lokasi pengguna saat ini
        }
    </script>

@endsection
