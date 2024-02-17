@include('HRD.layouts.sidebar')
@include('HRD.layouts.header')
{{-- @include('HRD.layouts_baru.content') --}}

<main>
    @yield('container') <!-- Ini adalah tempat untuk konten yang akan digantikan -->
</main>
@include('HRD.layouts.footer')