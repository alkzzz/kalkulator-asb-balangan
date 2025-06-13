@extends('layouts.app')

@section('subtitle', 'Buku Panduan User ASB-SK')
@section('content_header_title', 'Buku Panduan User ASB-SK')

@section('content_body')
    <div class="container-fluid">

        @if (\Storage::disk('public')->exists('manuals/buku-panduan-user-asb-sk.pdf'))
            <div class="mb-3">
                <a href="{{ asset('buku-panduan-user-asb-sk.pdf') }}" class="btn btn-success" target="_blank" download>
                    <i class="fas fa-download"></i> Unduh Buku Panduan
                </a>
            </div>

            <div class="embed-responsive embed-responsive-4by3" style="min-height: 600px;">
                <iframe class="embed-responsive-item w-100 h-100" src="{{ asset('buku-panduan-user-asb-sk.pdf') }}"
                    frameborder="0">
                </iframe>
            </div>
        @else
            <div class="alert alert-warning">
                Buku panduan belum tersedia.
            </div>
        @endif

    </div>
@endsection
