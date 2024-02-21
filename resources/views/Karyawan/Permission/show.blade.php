@extends('Karyawan.layouts.index')
@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Permission Details
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>User:</strong> {{ $permission->user->name }}</p>
                                <p><strong>Explanation:</strong> {{ $permission->explanation }}</p>
                                <p><strong>Permission Type:</strong> {{ $permission->permission_type }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Start Date:</strong> {{ $permission->start_date }}</p>
                                <p><strong>End Date:</strong> {{ $permission->end_date }}</p>
                                <p><strong>Period:</strong> {{ $period }} days</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status:</strong>
                                <div
                                    class="badge bg-{{ $permission->status == 'pending' ? 'primary' : ($permission->status == 'approved' ? 'success' : 'danger') }} text-white">
                                    {{ $permission->status }}
                                </div>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Supervisor Comment:</strong>
                                    {{ $permission->supervisor_comment ?? 'No comment' }}</p>
                                <p><strong>Supervisor Letter:</strong>
                                    @if ($permission->supervisor_letter)
                                        <a href="{{ url('storage/' . $permission->supervisor_letter) }}"
                                            target="_blank">{{ $permission->supervisor_letter }}</a>
                                    @else
                                        {{ 'No letter' }}
                                    @endif
                                </p>
                            </div>
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
@endsection
