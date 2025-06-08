@extends('layouts.app')

@section('content_header_title', 'Trend Riwayat Belanja: ' . auth()->user()->skpd->nama)

@section('content_body')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                {{-- Select ASB --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <b>Pilih ASB untuk Melihat Tren Riwayat Belanja</b>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="formPilihAsb">
                            <select name="asb_id" id="asb_id" class="form-control mb-3 select2">
                                <option value="">-- Pilih ASB --</option>
                                @foreach ($asbList as $asb)
                                    <option value="{{ $asb->id }}" {{ $asb->id == $asbData->id ? 'selected' : '' }}>
                                        {{ $asb->kode }} - {{ $asb->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Chart Panel --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <b>Tren Riwayat Belanja: ASB {{ $asbData->kode }} - {{ $asbData->nama }}</b>
                    </div>
                    <div class="card-body">
                        @if ($riwayatTahunan->count())
                            <div class="mb-5">
                                <h6 class="text-center mb-3">Total Nilai Belanja (Rp)</h6>
                                <canvas id="chartRupiah"></canvas>
                            </div>
                            <div class="mb-5">
                                <h6 class="text-center mb-3">Rata-rata Persentase Belanja (%)</h6>
                                <canvas id="chartPersentase"></canvas>
                            </div>
                            @if ($perObjek->count())
                                <div class="mb-5">
                                    <h6 class="text-center mb-3">Tren Nilai Belanja per Objek</h6>
                                    <canvas id="chartPerObjek"></canvas>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning mt-3">
                                Belum ada data riwayat belanja untuk ditampilkan sebagai tren.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Back --}}
                <div class="mt-3 text-right">
                    <a href="{{ route('riwayat-belanja.index', ['asb_id' => $asbData->id]) }}" class="btn btn-danger">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

@php
    $tahunLabels = $riwayatTahunan->pluck('tahun')->unique()->sort()->values();
@endphp

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih ASB --',
                width: '100%',
            });

            $('#asb_id').on('change', function() {
                const selectedId = $(this).val();
                if (selectedId) {
                    window.location.href = '{{ url('riwayat-belanja/trend') }}/' + selectedId;
                }
            });
        });

        const tahunLabels = {!! json_encode($tahunLabels) !!};

        // === Chart 1: Total Nilai (Rp) ===
        new Chart(document.getElementById('chartRupiah'), {
            type: 'line',
            data: {
                labels: tahunLabels,
                datasets: [{
                    label: 'Total Nilai (Rp)',
                    data: {!! json_encode($riwayatTahunan->pluck('total_rupiah')) !!},
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => 'Rp' + ctx.raw.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: val => 'Rp' + val.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });

        // === Chart 2: Rata-rata Persentase ===
        new Chart(document.getElementById('chartPersentase'), {
            type: 'line',
            data: {
                labels: tahunLabels,
                datasets: [{
                    label: 'Rata-rata Persentase (%)',
                    data: {!! json_encode($riwayatTahunan->pluck('avg_persentase')) !!},
                    borderColor: 'green',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.raw.toFixed(2) + '%'
                        }
                    }
                },
                scales: {
                    y: {
                        max: 100,
                        ticks: {
                            callback: val => val + '%'
                        }
                    }
                }
            }
        });

        // === Chart 3: Per Objek Belanja ===
        @if ($perObjek->count())
            const datasetPerObjek = [
                @foreach ($perObjek as $objekId => $data)
                    {
                        label: '{{ $objekList[$objekId]->nama_objek }}',
                        data: [
                            @foreach ($tahunLabels as $tahun)
                                {{ optional($data->firstWhere('tahun', $tahun))->total_nilai ?? 0 }},
                            @endforeach
                        ],
                        backgroundColor: '{{ sprintf('#%06X', mt_rand(0, 0xffffff)) }}',
                    },
                @endforeach
            ];

            new Chart(document.getElementById('chartPerObjek'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($tahunLabels) !!},
                    datasets: datasetPerObjek
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: ctx => 'Rp' + ctx.raw.toLocaleString('id-ID')
                            }
                        },
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        x: {
                            stacked: false
                        },
                        y: {
                            stacked: false,
                            ticks: {
                                callback: val => 'Rp' + val.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
