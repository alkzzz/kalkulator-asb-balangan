@extends('layouts.app')

@section('subtitle', $title)
@section('content_header_title', $title)

@section('content_body')
    <div class="container-fluid">
        <form action="{{ $action }}" method="POST" id="riwayat-form">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="form-group">
                <label>ASB</label>
                @php $selectedAsb = $asbList->firstWhere('id', $riwayat->asb_id); @endphp
                <input type="text" class="form-control" value="{{ $selectedAsb->kode }} - {{ $selectedAsb->nama }}"
                    disabled>
                <input type="hidden" name="asb_id" value="{{ $selectedAsb->id }}">
            </div>

            <div class="form-group">
                <label for="tahun">Tahun</label>
                <select name="tahun" class="form-control" required>
                    @foreach ($tahunTersedia as $tahun)
                        <option value="{{ $tahun }}"
                            {{ old('tahun', $riwayat->tahun ?? '') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
                @error('tahun')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <table class="table table-bordered table-sm" id="riwayat-table">
                <thead class="bg-light text-center">
                    <tr>
                        <th style="width: 40px">No</th>
                        <th>Objek Belanja</th>
                        <th style="width: 140px">Persentase (%)</th>
                        <th style="width: 180px">Nilai Rupiah (Rp)</th>
                        <th style="width: 40px">
                            <button type="button" class="btn btn-sm btn-primary" id="add-row">+</button>
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <button type="submit" class="btn btn-success mt-2"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('riwayat-belanja.index') }}" class="btn btn-danger mt-2"><i class="fas fa-arrow-left"></i>
                Kembali</a>
        </form>
    </div>
@endsection

@push('js')
    <script>
        (function() {
            const objekOptions = @json($objekBelanja->map(fn($o) => ['id' => $o->id, 'nama' => $o->nama_objek]));
            const tbody = document.querySelector('#riwayat-table tbody');
            const addRowBtn = document.getElementById('add-row');

            function refreshSelectOptions() {
                const used = Array.from(document.querySelectorAll('select[name="objek_belanja_id[]"]'))
                    .map(el => el.value).filter(v => v);

                document.querySelectorAll('select[name="objek_belanja_id[]"]').forEach(select => {
                    const current = select.value;
                    select.innerHTML = objekOptions
                        .filter(opt => opt.id == current || !used.includes(opt.id.toString()))
                        .map(opt =>
                            `<option value="${opt.id}" ${opt.id == current ? 'selected' : ''}>${opt.nama}</option>`
                        ).join('');
                });
            }

            function addRow(objekId = '', persen = '', rupiah = '') {
                const rowCount = tbody.children.length + 1;
                const tr = document.createElement('tr');

                tr.innerHTML = `
                <td class="text-center">${rowCount}</td>
                <td>
                    <select name="objek_belanja_id[]" class="form-control" required></select>
                </td>
                <td>
                    <input type="number" name="persentase[]" step="0.01" class="form-control" required value="${persen}">
                </td>
                <td>
                    <input type="text" name="nilai_rupiah[]" class="form-control rupiah-input" required value="${rupiah}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-remove">–</button>
                </td>
            `;

                tbody.appendChild(tr);
                refreshSelectOptions();

                if (objekId) {
                    tr.querySelector('select').value = objekId;
                    refreshSelectOptions();
                }
            }

            function formatRupiahInput(el) {
                el.addEventListener('input', function(e) {
                    let value = el.value.replace(/\D/g, '');
                    if (!value) return el.value = '';

                    el.value = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value).replace(/^Rp/, 'Rp');
                });
            }

            function applyFormatRupiahToAll() {
                document.querySelectorAll('input[name="nilai_rupiah[]"]').forEach(el => {
                    formatRupiahInput(el);
                });
            }

            addRowBtn.addEventListener('click', () => {
                addRow()
                applyFormatRupiahToAll()
            });

            tbody.addEventListener('click', e => {
                if (e.target.classList.contains('btn-remove')) {
                    e.target.closest('tr').remove();
                    Array.from(tbody.children).forEach((row, i) => {
                        row.querySelector('td').textContent = i + 1;
                    });
                    refreshSelectOptions();
                }
            });

            tbody.addEventListener('change', e => {
                if (e.target.name === 'objek_belanja_id[]') {
                    refreshSelectOptions();
                }
            });

            // initial row
            @if (old('objek_belanja_id'))
                @foreach (old('objek_belanja_id') as $i => $id)
                    addRow("{{ $id }}", "{{ old('persentase')[$i] ?? '' }}",
                        "{{ old('nilai_rupiah')[$i] ?? '' }}");
                @endforeach
            @else
                addRow();
                applyFormatRupiahToAll();
            @endif
        })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('riwayat-form');
            const persenInputs = () => document.querySelectorAll('input[name="persentase[]"]');

            form.addEventListener('submit', function(e) {
                // Bersihkan format rupiah sebelum form dikirim
                document.querySelectorAll('input[name="nilai_rupiah[]"]').forEach(el => {
                    el.value = el.value.replace(/\D/g, '');
                });
            });
        });
    </script>
@endpush
