@extends('Supervisor.layouts.index')

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Permission</div>

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

                        <form method="POST" enctype="multipart/form-data"
                            action="{{ url('/dashboardsupervisor/Rekap_Permission/rejected/update/' . $permission->id) }}">
                            @csrf
                            @method('PUT')
                            <!-- Explanation -->
                            <label for="supervisor_comment">Supervisor Comment:</label><br>
                            <textarea id="supervisor_comment" name="supervisor_comment"></textarea><br>

                            <!-- Tambahkan input untuk supervisor_letter (opsional) -->
                            <label for="supervisor_letter">Supervisor Letter:</label><br>
                            <input type="file" id="supervisor_letter" name="supervisor_letter"><br>

                            <!-- Submit Button -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/dashboardsupervisor/Rekap_Permission') }}"
                                        class="btn btn-secondary">Back</a>
                                    <button type="submit" id="submit-button" class="btn btn-primary">Update
                                        Permission</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
