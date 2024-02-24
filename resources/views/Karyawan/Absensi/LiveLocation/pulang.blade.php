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

                        <form method="POST"
                            action="{{ url('/dashboardkaryawan/Absensi/LiveLocation/pulang/updateDatang/' . $absensi->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Peta -->
                            <div id="map" style="height: 300px;"></div>

                            <!-- Longitude dan Latitude -->
                            <div class="form-group">
                                <label for="longitude_pulang">Longitude:</label>
                                <input type="text" id="longitude_pulang" name="longitude_pulang" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="latitude_pulang">Latitude:</label>
                                <input type="text" id="latitude_pulang" name="latitude_pulang" class="form-control">
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/dashboardkaryawan/Absensi/LiveLocation') }}"
                                        class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Pulang</button>
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
            document.getElementById('longitude_pulang').value = e.latlng.lng;
            document.getElementById('latitude_pulang').value = e.latlng.lat;
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
                    document.getElementById('longitude_pulang').value = lng;
                    document.getElementById('latitude_pulang').value = lat;
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Memanggil getLocation saat halaman dimuat
        getLocation();

        // Fungsi untuk mereset peta ke lokasi awal
        function refreshMap() {
            getLocation(); // Panggil fungsi getLocation untuk mendapatkan lokasi pengguna saat ini
        }
    </script>
@endsection
