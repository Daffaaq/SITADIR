@extends('Superadmin.layouts.index')
@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit User</div>
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
                        <form method="POST" action="{{ url('/superadmin/Users/update/' . $users->id) }}">
                            @csrf
                            @method('PUT')
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Name: </label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $users->name }}" required>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email: </label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $users->email }}" required>
                            </div>
                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password: </label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Leave blank to keep the current password">
                            </div>
                            <!-- Role -->
                            <div class="form-group">
                                <label for="role">Role:</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="superadmin" {{ $users->role == 'superadmin' ? 'selected' : '' }}>
                                        Superadmin</option>
                                    <option value="admin" {{ $users->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/dashboardSuperadmin/Users') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Update User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
