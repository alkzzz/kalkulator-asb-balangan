@extends('layouts.app')

@section('subtitle', $driver ? 'Edit Cost Driver' : 'Tambah Cost Driver')
@section('content_header_title', $driver ? 'Edit Cost Driver' : 'Tambah Cost Driver')

@section('content_body')
    <div class="container">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="label">Label</label>
                <input type="text" name="label" class="form-control" value="{{ old('label', $driver->label ?? '') }}"
                    required>
            </div>

            <div class="form-group">
                <label for="jumlah_input">Jumlah Input</label>
                <input type="number" name="jumlah_input" class="form-control" min="1" max="5"
                    value="{{ old('jumlah_input', $driver->jumlah_input ?? 1) }}" required>
            </div>

            <div class="form-group">
                <label for="koefisien">Koefisien (Rp)</label>
                <input type="number" step="0.01" name="koefisien" class="form-control"
                    value="{{ old('koefisien', $driver->koefisien ?? '') }}" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('asb.cost-driver.index', $struktur_asb->id) }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
