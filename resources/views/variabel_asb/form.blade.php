@extends('layouts.app')

@section('subtitle', 'Tambah Variabel')
@section('content_header_title', 'Tambah Variabel untuk: ' . $struktur_asb->nama)

@section('content_body')
    <div class="container">
        <form action="{{ route('asb.variabel.store', $struktur_asb->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="kode">Kode Variabel (misal: X1)</label>
                <input type="text" name="kode" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama / Keterangan</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="koefisien_b">Koefisien (b)</label>
                <input type="number" step="0.001" name="koefisien_b" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('asb.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
