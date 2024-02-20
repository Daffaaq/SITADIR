@extends('Karyawan.layouts.index')

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Profile {{ auth()->user()->role }}</div>

                    <div class="card-body">
                        @if (session('info'))
                            <div class="alert alert-info">
                                {{ session('info') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ url('/dashboardkaryawan/Profiles/update/' . $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 col-1">
                                    <!-- Name -->
                                    <div class="form-group">
                                        <label for="name">Name: <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ $user->name }}" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email">Email: <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ $user->email }}" required>
                                    </div>

                                </div>

                                <div class="col-md-6 col-2">
                                    <div class="form-group">
                                        <label for="password">Password: </label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Leave blank to keep the current password">
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role: <span class="text-danger">*</span></label>
                                        <select name="role" id="role" class="form-control" required>
                                            @foreach (['superadmin', 'supervisor', 'karyawan', 'hrd'] as $role)
                                                <option value="{{ $role }}"
                                                    {{ $user->role == $role ? 'selected' : '' }}>
                                                    {{ ucfirst($role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 text-center">
                                    <label>Status: <span class="text-danger">*</span></label><br>
                                    @foreach (['aktif', 'nonaktif'] as $status)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="{{ $status }}" value="{{ $status }}"
                                                {{ $user->status == $status ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="{{ $status }}">{{ ucfirst($status) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12" style="text-align: center;">
                                    <a href="{{ url('/dashboardkaryawan') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
