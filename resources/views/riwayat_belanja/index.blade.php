@extends('layouts.app')

@section('subtitle', 'Riwayat Belanja')
@section('content_header_title', 'Riwayat Tahunan per ASB')

@section('content_body')
    <div class="container-fluid">
        <a href="{{ route('riwayat-belanja.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Riwayat
        </a>

        <table class="table table-bordered table-striped">
            <thead class="bg-warning text-center">
                <tr>
                    <th>Tahun</th>
                    <th>ASB</th>
                    <th>Total Persentase</th>
                    <th>Total Nilai (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td class="text-center">{{ $row->tahun }}</td>
                        <td>{{ $row->asb->kode }} - {{ $row->asb->nama }}</td>
                        <td class="text-center">{{ number_format($row->total_persen, 2) }}%</td>
                        <td class="text-right">Rp{{ number_format($row->total_nilai, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('riwayat-belanja.show', ['riwayat' => $row->asb_id, 'tahun' => $row->tahun]) }}"
                                class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
