@extends('layouts.app')

@section('subtitle', "Detail Riwayat Tahun $tahun")
@section('content_header_title', "Detail Riwayat Belanja Tahun $tahun - " . auth()->user()->skpd->nama)

@section('content_body')
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success font-weight-bold">
                ASB-{{ $asb->kode }} | {{ $asb->nama }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="text-center text-dark font-weight-bold" style="background-color: #FFD700">
                            <tr>
                                <th style="vertical-align: middle; width: 40px">No</th>
                                <th style="vertical-align: middle;">Obyek Belanja</th>
                                <th style="vertical-align: middle;">Persentase (%)</th>
                                <th style="vertical-align: middle;">Nilai Rupiah (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPersen = 0;
                                $totalRupiah = 0;
                            @endphp
                            @foreach ($riwayatList as $i => $row)
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $i + 1 }}</td>
                                    <td>{{ $row->objekBelanja->nama_objek }}</td>
                                    <td class="text-center">{{ number_format($row->persentase, 2) }}%</td>
                                    <td class="text-center">Rp{{ number_format($row->nilai_rupiah, 0, ',', '.') }}</td>
                                </tr>
                                @php
                                    $totalPersen += $row->persentase;
                                    $totalRupiah += $row->nilai_rupiah;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot style="background-color: #FFD700" class="font-weight-bold text-center">
                            <tr>
                                <td colspan="2" class="text-right">Jumlah :</td>
                                <td>{{ number_format($totalPersen, 2) }}%</td>
                                <td class="text-center">Rp{{ number_format($totalRupiah, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <a href="{{ route('riwayat-belanja.index', ['asb_id' => $asb->id]) }}" class="btn btn-danger">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection
