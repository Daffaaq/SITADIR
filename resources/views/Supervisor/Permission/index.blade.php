    @extends('Supervisor.layouts.index')

    @section('container')
        @foreach ($karyawanUsers as $karyawanUser)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List Permission for {{ $karyawanUser->name }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="user-table-{{ $karyawanUser->id }}" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Keterangan</th>
                                    <th>Tipe</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Status</th>
                                    <th>Batas Waktu</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(document).ready(function() {
                    @foreach ($karyawanUsers as $karyawanUser)
                        console.log('User ID:', '{{ $karyawanUser->id }}');
                        $('#user-table-{{ $karyawanUser->id }}').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('get.recap.Permission.supervisor', ['userId' => $karyawanUser->id]) }}",
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'explanation',
                                    name: 'explanation'
                                },
                                {
                                    data: 'permission_type',
                                    name: 'permission_type'
                                },
                                {
                                    data: 'start_date',
                                    name: 'start_date'
                                },
                                {
                                    data: 'end_date',
                                    name: 'end_date'
                                },
                                {
                                    data: 'status',
                                    name: 'status'
                                },
                                {
                                    data: 'period',
                                    name: 'period'
                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false,
                                },
                            ],
                        });
                    @endforeach
                });
            });
        </script>
    @endsection
