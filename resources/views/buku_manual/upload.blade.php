@extends('layouts.app')

@section('subtitle', 'Upload Buku Panduan User ASB-SK')
@section('content_header_title', 'Upload Buku Panduan User ASB-SK')

@section('content_body')
    <div class="container-fluid">
        {{-- Tampilkan file yang sudah ada (jika ada) --}}
        @if (!empty($existingFile))
            <div class="mb-3">
                <label>File Saat Ini:</label><br>
                <a href="{{ asset('buku-panduan-user-asb-sk.pdf') }}" target="_blank">
                    <i class="fas fa-file-pdf"></i> Lihat Buku Panduan
                </a>
            </div>
        @endif

        <form action="{{ route('buku-manual.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="manual">Unggah File Baru (PDF)</label>
                <input type="file" name="manual" id="manual" class="form-control" accept="application/pdf" required>
                @error('manual')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload</button>
            <a href="{{ route('dashboard') }}" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </form>
    </div>
@endsection
