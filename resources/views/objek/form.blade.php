@extends('layouts.app')

@section('subtitle', $title)
@section('content_header_title', $title)

@section('content_body')
    <div class="container pb-4">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="nama_objek">Nama Objek Belanja</label>
                <input type="text" name="nama_objek" id="nama_objek"
                    class="form-control @error('nama_objek') is-invalid @enderror"
                    value="{{ old('nama_objek', $objek->nama_objek ?? '') }}" required>
                @error('nama_objek')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('objek.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>
    </div>
@endsection
