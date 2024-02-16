@extends('Superadmin.layouts.index')

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New User</div>

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

                        <form method="POST" action="{{ url('/dashboardSuperadmin/Users/store') }}">
                            @csrf
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <!-- Role -->
                            <div class="form-group">
                                <label for="role">Role:</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="superadmin">Superadmin</option>
                                    <option value="supervisor">supervisor</option>
                                    <option value="karyawan">karyawan</option>
                                    <option value="hrd">hrd</option>
                                </select>
                            </div>
                            <!-- Status -->
                            <div class="form-group">
                                <label>Status:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="aktif"
                                        value="aktif" checked>
                                    <label class="form-check-label" for="aktif">Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="nonaktif"
                                        value="nonaktif">
                                    <label class="form-check-label" for="nonaktif">Nonaktif</label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/dashboardSuperadmin/Users') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Create User</button>
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
