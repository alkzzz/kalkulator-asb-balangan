@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle')
        | @yield('subtitle')
    @endif

@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
    @yield('content_body')

    @if (session('success'))
        @push('js')
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>
        @endpush
    @endif
@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Versi: 1.0.2025
    </div>

    <strong>
        <a href="#" style="color: green">ASB-SK Kabupaten Balangan</a>
    </strong>
@stop

@push('js')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-alert').alert('close');
            }, 3000);
        });
    </script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
@endpush
