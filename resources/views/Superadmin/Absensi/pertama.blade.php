@extends('Superadmin.layouts.index')
@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Search Absensi</div>

                    <div class="card-body">
                        <form action="{{ url('DashboardSuperadmin/absensi/search') }}" method="GET">
                            <div class="form-group">
                                <label for="search_by">Search by:</label>
                                <select class="form-control" name="search_by" id="search_by">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->email }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
