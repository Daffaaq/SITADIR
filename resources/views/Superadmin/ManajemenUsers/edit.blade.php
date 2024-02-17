@extends('Superadmin.layouts.index')
@section('container')
    <style>
        .complaint-info {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }

        .question-mark:hover {
            color: blue;
        }

        .question-mark {
            cursor: pointer;
            color: red;
            font-weight: bold;
            font-size: 20px;
            margin-left: 5px;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit User</div>
                    <div class="mb-3" style="text-align: center;">
                        <label for="complaint_info" class="form-label" style="font-weight: bold;"">Hal-hal apa saja yang perlu
                            diperhatikan dalam edit user <span id="showModal" class="question-mark">?</span></label>
                        <div id="complaint_info" class="complaint-info">
                        </div>
                    </div>
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
                        <form method="POST" action="{{ url('/dashboardSuperadmin/Users/update/' . $users->id) }}">
                            @csrf
                            @method('PUT')
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Name: <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $users->name }}" required>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email: <span class="text-danger">*</span></label>
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
                                <label for="role">Role: <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-control" required>
                                    @foreach (['superadmin', 'supervisor', 'karyawan', 'hrd'] as $role)
                                        <option value="{{ $role }}" {{ $users->role == $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label>Status: <span class="text-danger">*</span></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="aktif"
                                        value="aktif" {{ $users->status == 'aktif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aktif">Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="nonaktif"
                                        value="nonaktif" {{ $users->status == 'nonaktif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="nonaktif">Nonaktif</label>
                                </div>
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

    {{-- showModal --}}
    <div class="modal fade" id="caraPengaduanModal" tabindex="-1" role="dialog" aria-labelledby="caraPengaduanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="caraPengaduanModalLabel">Cara edit Users yang baik dan benar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Isi modal -->
                    <p>Berikut adalah beberapa hal yang diperhatikan dalam edit Users:</p>
                    <ol style="text-align: justify;">
                        <li>Informasi yang diberi tanda bintang merah (*) wajib diisi.</li>
                        <li>Password tidak wajib diisi dan boleh dikosongkan.</li>
                        <li>Password tidak boleh sama dengan yang ada di database.</li>
                        <li>Ketika pengguna yang sedang login mengupdate data:</li>
                        <ul>
                            <li>Tidak diperbolehkan untuk mengubah status dan peran. Jika memaksa untuk mengganti, perubahan
                                tidak akan terjadi karena sudah diatur secara kaku dalam kode.</li>
                            <li>Jika melakukan pembaruan pada password atau email, pengguna akan otomatis logout dalam waktu
                                5 detik. Selain itu, password yang baru juga tidak boleh sama dengan yang ada di database.
                            </li>
                        </ul>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ambil elemen tanda tanya (?) dan modal
                var showModal = document.getElementById('showModal');
                var modal = document.getElementById('caraPengaduanModal');

                // Tambahkan event click ke tanda tanya (?)
                showModal.addEventListener('click', function() {
                    // Tampilkan modal saat tanda tanya (?) diklik
                    $(modal).modal('show');
                });
            });
        </script>
    @endsection
