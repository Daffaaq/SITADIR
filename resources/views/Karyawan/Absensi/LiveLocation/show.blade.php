@extends('Karyawan.layouts.index')
@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Absensi Details</span>
                        <span>{{ $absensi->tanggal }}</span>
                    </div>


                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Waktu Datang:</strong> {{ $absensi->waktu_datang_LiveLoc }} </p>
                                <p><strong>Longitude Datang:</strong> {{ $absensi->longitude_datang }}</p>
                                <p><strong>Latitude Datang:</strong> {{ $absensi->latitude_datang }}</p>
                                <div id="map-datang" style="height: 200px;"></div>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Waktu Pulang:</strong>
                                    {{ $absensi->waktu_pulang_LiveLoc ?? 'Belum Absen Pulang' }}</p>
                                <p><strong>Longitude Pulang:</strong>
                                    {{ $absensi->longitude_pulang ?? 'Belum Absen Pulang' }}</p>
                                <p><strong>Latitude Pulang:</strong> {{ $absensi->latitude_pulang ?? 'Belum Absen Pulang' }}
                                </p>
                                @if ($absensi->latitude_pulang && $absensi->longitude_pulang)
                                    <div id="map-pulang" style="height: 200px;"></div>
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <p><strong>Letter of Assignment:</strong>
                                <a href="{{ url('storage/' . $absensi->letter_of_assignment) }}"
                                    target="_blank">Download</a>
                            </p>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Script pertama
        var datangMap = L.map('map-datang').setView([{{ $absensi->latitude_datang }}, {{ $absensi->longitude_datang }}],
            13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(datangMap);
        L.marker([{{ $absensi->latitude_datang }}, {{ $absensi->longitude_datang }}]).addTo(datangMap)
            .bindPopup('Lokasi Datang')
            .openPopup();
    </script>
    <script>
        // Menambahkan delay sebelum memuat peta kedua
        setTimeout(function() {
            var pulangMap = L.map('map-pulang').setView([{{ $absensi->latitude_pulang }},
                {{ $absensi->longitude_pulang }}
            ], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(pulangMap);
            L.marker([{{ $absensi->latitude_pulang }}, {{ $absensi->longitude_pulang }}]).addTo(pulangMap)
                .bindPopup('Lokasi Pulang')
                .openPopup();
        }, 1000); // Menggunakan delay 1000 ms (1 detik), Anda bisa menyesuaikannya jika diperlukan
    </script>
@endsection
