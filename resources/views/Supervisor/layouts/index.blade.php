@include('Supervisor.layouts.sidebar')
@include('Supervisor.layouts.header')
{{-- @include('Supervisor.layouts_baru.content') --}}

<main>
    @yield('container') <!-- Ini adalah tempat untuk konten yang akan digantikan -->
</main>
@include('Supervisor.layouts.footer')