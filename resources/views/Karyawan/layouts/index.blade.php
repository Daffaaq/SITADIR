@include('Karyawan.layouts.sidebar')
@include('Karyawan.layouts.header')
{{-- @include('Karyawan.layouts_baru.content') --}}

<main>
    @yield('container') <!-- Ini adalah tempat untuk konten yang akan digantikan -->
</main>
@include('Karyawan.layouts.footer')