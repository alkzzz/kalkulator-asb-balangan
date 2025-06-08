@extends('layouts.app')

@section('content_header_title', 'Data Riwayat Belanja: ' . auth()->user()->skpd->nama)

@section('content_body')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <div class="mb-3 text-muted">
                    Silakan pilih ASB untuk melihat riwayat belanja tahunan.
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <b>Riwayat Belanja Berdasarkan ASB</b>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('riwayat-belanja.index') }}">
                            <select name="asb_id" id="asb_id" class="form-control mb-3 select2"
                                onchange="this.form.submit()">
                                <option value="">-- Pilih ASB --</option>
                                @foreach ($asbList as $asb)
                                    <option value="{{ $asb->id }}" {{ $selectedAsb == $asb->id ? 'selected' : '' }}>
                                        {{ $asb->kode }} - {{ $asb->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                @if ($selectedAsb)
                    <div class="mb-3 d-flex justify-content-between">
                        <a href="{{ route('riwayat-belanja.trend', ['asb' => $selectedAsb]) }}" class="btn btn-info">
                            <i class="fas fa-chart-line"></i> Lihat Tren Riwayat Belanja
                        </a>
                        <a href="{{ route('riwayat-belanja.create', ['asb_id' => $selectedAsb]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Riwayat Belanja
                        </a>
                    </div>
                @endif

                @if ($selectedAsb && $riwayatTahunan->count())
                    <div class="card shadow-sm">
                        @php
                            $selected = $asbList->firstWhere('id', $selectedAsb);
                        @endphp

                        <div class="card-header bg-warning">
                            <b>ASB-{{ $selected->kode }} | {{ $selected->nama }}</b>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm mb-0">
                                    <thead class="text-center bg-light">
                                        <tr>
                                            <th>Tahun</th>
                                            <th>Total Nilai (Rp)</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatTahunan as $item)
                                            <tr class="text-center">
                                                <td>{{ $item->tahun }}</td>
                                                <td>
                                                    Rp{{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('riwayat-belanja.show', ['asb' => $selectedAsb, 'tahun' => $item->tahun]) }}"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif ($selectedAsb)
                    <div class="alert alert-warning mt-3">
                        Belum ada riwayat belanja untuk ASB ini.
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih ASB --',
                width: '100%',
            });
        });

        const params = new URLSearchParams(window.location.search);
        if (!params.has('asb_id')) {
            $('#asb_id').val("1").trigger('change');
        }
    </script>
@endpush
