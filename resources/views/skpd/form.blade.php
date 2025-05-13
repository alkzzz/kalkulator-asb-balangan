@extends('layouts.app')

@section('subtitle', $title)
@section('content_header_title', $title)

@section('content_body')
    <div class="container-fluid">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="nama">Nama SKPD</label>
                <input type="text" name="nama" id="nama" class="form-control"
                    value="{{ old('nama', $skpd->nama ?? '') }}" required>
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="singkatan">Singkatan</label>
                <input type="text" name="singkatan" id="singkatan" class="form-control"
                    value="{{ old('singkatan', $skpd->singkatan ?? '') }}" required>
                @error('singkatan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('data-skpd.index') }}" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </form>
    </div>
@endsection
