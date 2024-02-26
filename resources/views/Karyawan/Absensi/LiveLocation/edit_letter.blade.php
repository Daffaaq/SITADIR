@extends('Karyawan.layouts.index')

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Update Letter of Assignment</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
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
                            action="{{ url('/dashboardkaryawan/Absensi/LiveLocation/Update/letter/update_letter/' . $absensi->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="letter_of_assignment">Upload New Letter of Assignment</label>
                                <input id="letter_of_assignment" type="file" class="form-control"
                                    name="letter_of_assignment">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Update Letter of Assignment
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
