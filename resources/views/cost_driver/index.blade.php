@extends('layouts.app')

@section('subtitle', 'Cost Driver')
@section('content_header_title', 'Cost Driver - ' . $struktur_asb->nama)

@section('content_body')
    <div class="container">
        <a href="{{ route('asb.cost-driver.create', $struktur_asb->id) }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Cost Driver
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Jumlah Input</th>
                    <th>Koefisien</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                    <tr>
                        <td>{{ $driver->label }}</td>
                        <td>{{ $driver->jumlah_input }}</td>
                        <td>Rp{{ number_format($driver->koefisien, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('asb.cost-driver.edit', [$struktur_asb->id, $driver->id]) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('asb.cost-driver.destroy', [$struktur_asb->id, $driver->id]) }}"
                                method="POST" style="display:inline-block">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
