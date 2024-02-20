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

                        <form method="POST" action="{{ url('/dashboardkaryawan/Permission/store') }}">
                            @csrf
                            <!-- Explanation -->
                            <div class="form-group">
                                <label for="explanation">Explanation:</label>
                                <textarea name="explanation" id="explanation" class="form-control" required></textarea>
                            </div>
                            <!-- Permission Type -->
                            <div class="form-group">
                                <label for="permission_type">Permission Type:</label>
                                <select name="permission_type" id="permission_type" class="form-control" required>
                                    <option value="izin">Izin</option>
                                    <option value="cuti">Cuti</option>
                                </select>
                            </div>
                            <!-- Start Date -->
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <!-- End Date -->
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/dashboardkaryawan/Permission') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Create Permission</button>
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

@endsection
