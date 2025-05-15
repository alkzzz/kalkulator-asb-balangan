@extends('layouts.app')

@section('subtitle', 'Kalkulator ASB')
@section('content_header_title', 'Kalkulator ASB')

@section('content_body')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        Analisis Standar Belanja (ASB)
                    </div>
                    <div class="card-body">
                        <select id="asb_id" class="form-control mb-3">
                            <option value="">-- Pilih ASB --</option>
                            @foreach ($asbList as $asb)
                                <option value="{{ $asb->id }}" data-fixed="{{ $asb->fixed_cost }}"
                                    data-variable="{{ $asb->variable_cost }}" data-drivers='@json($asb->costDrivers)'
                                    data-edit="{{ route('asb.edit', $asb->id) }}">
                                    {{ $asb->kode }} - {{ $asb->nama }}
                                </option>
                            @endforeach
                        </select>

                        <div id="cost-summary" class="my-3"></div>
                        <div id="cost-driver-inputs"></div>

                        <button class="btn btn-danger btn-lg w-100 mt-3 d-none" id="btn-hitung"><i
                                class="fas fa-calculator"></i>
                            Hitung ASB
                        </button>
                    </div>
                </div>

                <div id="spinner" class="text-center my-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Menghitung …</p>
                </div>

                <div id="hasil-hitung"></div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            const coll = new Intl.Collator('id', {
                numeric: true,
                sensitivity: 'base'
            });
            const $select = $('#asb_id').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih ASB --',
                width: '100%',
                sorter: d => d.sort((a, b) => coll.compare(a.text, b.text))
            });

            const $summary = $('#cost-summary');
            const $inputs = $('#cost-driver-inputs');
            const $btn = $('#btn-hitung');
            const $hasil = $('#hasil-hitung');
            const $spinner = $('#spinner');

            let fixedCost = 0,
                variableCost = 0;

            $select.on('change', function() {
                $summary.empty();
                $inputs.empty();
                $hasil.empty();
                $spinner.addClass('d-none');
                $btn.addClass('d-none');

                const opt = this.options[this.selectedIndex];
                if (!opt.value) return;

                fixedCost = parseFloat(opt.dataset.fixed || 0);
                variableCost = parseFloat(opt.dataset.variable || 0);
                const drivers = JSON.parse(opt.dataset.drivers || '[]');

                $summary.html(`
                <div class="border rounded p-3 bg-light">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Fixed Cost</span><strong>Rp${rupiah(fixedCost)}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Variable Cost</span><strong>Rp${rupiah(variableCost)}</strong>
                    </div>
                </div>`);

                if (variableCost === 0 || !drivers.length) {
                    $inputs.html(`
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <span>ASB ini belum memiliki variable cost atau cost driver.</span>
                    @can('admin')
                    <a href="${opt.dataset.edit}" class="btn btn-sm btn-primary" style="text-decoration: none;">
                        <i class="fas fa-edit"></i> Edit ASB
                    </a>
                    @endcan
                </div>`);
                    return;
                }

                $inputs.html(drivers.map(d => `
            <div class="form-group">
                <label>${d.label}</label>
                <input type="number" class="form-control driver-input" min="1" step="1" required>
            </div>`).join(''));

                $btn.removeClass('d-none');
            });

            $btn.on('click', function() {
                const vals = $('.driver-input').map(function() {
                    return parseFloat(this.value);
                }).get();
                if (vals.some(v => isNaN(v) || v < 1)) {
                    $('.driver-input').each(function() {
                        $(this).toggleClass('is-invalid', isNaN(this.value) || this.value < 1);
                    });
                    return;
                }

                const multiplier = vals.reduce((p, c) => p * c, 1);
                $spinner.removeClass('d-none');
                $hasil.empty();

                setTimeout(() => {
                    $spinner.addClass('d-none');
                    const total = fixedCost + variableCost * multiplier;

                    $hasil.html(`
            <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white text-center rounded-0 font-weight-bold">Hasil Perhitungan</div>
            </div>
        `);
                    const asbId = $select.val();
                    const baseUrl = "{{ url('struktur-asb') }}";

                    $.getJSON(`${baseUrl}/${asbId}/breakdown`, function(breakdownData) {
                        const rows = breakdownData.map((b, i) => {
                            const amt = Math.round(total * b.avg_pct / 100);
                            const amtUp = Math.ceil(total * b.limit_pct / 100);
                            return `
                <tr>
                <td class="text-center">${i+1}</td>
                <td>${b.objek}</td>
                <td class="text-center">${b.avg_pct.toFixed(2)}%</td>
                <td class="text-center">Rp${amt.toLocaleString('id-ID')}</td>
                <td class="text-center">${b.limit_pct.toFixed(2)}%</td>
                <td class="text-center">Rp${amtUp.toLocaleString('id-ID')}</td>
                </tr>`;
                        }).join('');

                        const rawPct = breakdownData.reduce((sum, b) => sum + b.avg_pct, 0);
                        const totalPct = Math.round(rawPct); // bulatkan ke angka utuh

                        const footer = `
                <tr class="bg-warning font-weight-bold">
                    <td colspan="2" class="text-right">Jumlah :</td>
                    <td class="text-center">${totalPct}%</td>
                    <td class="text-center">Rp${total.toLocaleString('id-ID')}</td>
                    <td></td><td></td>
                </tr>`;

                        $hasil.append(`
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-sm mb-0">
                <thead class="bg-warning text-center">
                    <tr>
                    <th>No</th>
                    <th>Objek Belanja</th>
                    <th style="width:15%; white-space: nowrap">Rata-rata (%)</th>
                    <th style="width:15%; white-space: nowrap">Jumlah (Rp)</th>
                    <th style="width:15%; white-space: nowrap">Batas Atas (%)</th>
                    <th style="width:15%; white-space: nowrap">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
                <tfoot>
                    ${footer}
                </tfoot>
                </table>
            </div>
            `);
                    });

                }, 500);

            });
        });

        function rupiah(n) {
            return parseInt(n).toLocaleString('id-ID');
        }
    </script>
@endpush
