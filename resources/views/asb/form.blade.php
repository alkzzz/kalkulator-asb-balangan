@extends('layouts.app')

@section('subtitle', $title)
@section('content_header_title', $title)

@section('content_body')
    <div class="container pb-4">
        <form id="form-asb" action="{{ $action }}" method="POST">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="kode">Kode ASB</label>
                <input type="text" name="kode" id="kode" class="form-control" required
                    value="{{ old('kode', $asb->kode ?? '') }}">
                @error('kode')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama">Nama ASB</label>
                <input type="text" name="nama" id="nama" class="form-control" required
                    value="{{ old('nama', $asb->nama ?? '') }}">
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="definisi">Definisi</label>
                <textarea name="definisi" id="definisi" class="form-control">{{ old('definisi', $asb->definisi ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="fixed_cost">Satuan Pengendali Belanja Tetap&nbsp;(Fixed&nbsp;Cost)</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                    <input type="text" name="fixed_cost" id="fixed_cost" class="form-control rupiah-input"
                        value="{{ number_format(old('fixed_cost', $asb->fixed_cost ?? 0), 0, ',', '.') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="variable_cost">Satuan Pengendali Belanja Variabel&nbsp;(Variable&nbsp;Cost)</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                    <input type="text" name="variable_cost" id="variable_cost" class="form-control rupiah-input"
                        value="{{ number_format(old('variable_cost', $asb->variable_cost ?? 0), 0, ',', '.') }}">
                </div>
            </div>
        </form>

        <button type="submit" form="form-asb" class="btn btn-success">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('asb.index') }}" class="btn btn-danger">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        @if (isset($asb))
            @if ($asb->costDrivers->count())
                <hr>
                <div class="card mt-4 shadow-sm">
                    <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                        <p class="mb-0 text-white">Cost Driver</p>
                    </div>

                    <div class="list-group list-group-flush">
                        @foreach ($asb->costDrivers as $driver)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $driver->label }}</span>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-warning btn-edit-driver mr-1"
                                        data-label="{{ $driver->label }}"
                                        data-action="{{ route('asb.cost-driver.update', [$asb->id, $driver->id]) }}"
                                        data-method="PUT">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('asb.cost-driver.destroy', [$asb->id, $driver->id]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus cost driver ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-muted">Belum ada cost driver.</p>
            @endif

            <button type="button" class="btn btn-sm btn-primary mt-2" data-toggle="modal" data-target="#addDriverModal">
                <i class="fas fa-plus"></i> Tambah Cost Driver
            </button>
        @endif

        <hr>
        <div style="display: none">
            @if (isset($asb))
                <div class="card mt-4 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="bg-warning text-center">
                                <tr>
                                    <th style="width:40px">No</th>
                                    <th>Objek Belanja</th>
                                    <th style="width:15%">Rata-rata&nbsp;(%)</th>
                                    <th style="width:15%">Batas Atas&nbsp;(%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary as $i => $row)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $row['objek'] }}</td>
                                        <td class="text-end">{{ number_format($row['avg_pct'], 2, '.', '.') }}%</td>
                                        <td class="text-end">{{ number_format($row['limit_pct'], 2, '.', '.') }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Belum ada data riwayat objek belanja
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <button class="btn btn-sm btn-warning mb-2 mt-2" data-toggle="modal" data-target="#modalRiwayat">
                    <i class="fas fa-plus"></i> Tambah Riwayat Belanja
                </button>
            @endif
        </div>

        @if ($riwayat->count())
            <div id="accordionRiwayat" class="mt-3">
                @foreach ($riwayat as $tahun => $rows)
                    @php
                        $id = 'th' . $tahun;
                        $tot = number_format($rows->sum('persentase'), 2, '.', '.');
                    @endphp

                    <div class="card mb-1">
                        <div class="card-header d-flex justify-content-between align-items-center px-2 py-1"
                            id="h{{ $id }}">
                            <button class="btn btn-link text-left flex-grow-1 pl-1" data-toggle="collapse"
                                data-target="#c{{ $id }}">
                                Riwayat Tahun {{ $tahun }}
                            </button>
                            <form action="{{ route('asb.riwayat.destroy.tahun', [$asb->id, $tahun]) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus SEMUA riwayat tahun {{ $tahun }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger me-2">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>

                        <div id="c{{ $id }}" class="collapse" data-parent="#accordionRiwayat">
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th style="width:50px">No</th>
                                            <th>Objek Belanja</th>
                                            <th style="width:120px">Persen&nbsp;(%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $i => $r)
                                            <tr>
                                                <td class="text-center">{{ $i + 1 }}</td>
                                                <td>{{ $r->objekBelanja->nama_objek }}</td>
                                                <td class="text-end">
                                                    {{ number_format($r->persentase, 2, '.', '.') }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    @if (isset($asb))
        <div class="modal fade" id="addDriverModal" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('asb.cost-driver.store', $asb->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Cost Driver</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="new_driver_label">Nama Variabel</label>
                                <input type="text" name="label" id="new_driver_label" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="editDriverModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="editDriverForm" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Cost Driver</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="driver_label">Label</label>
                                <input type="text" name="label" id="driver_label" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @php
            $tahunSekarang = now()->year;
            $tahunTerpakai = isset($asb) ? $asb->objekTahunan->pluck('tahun')->unique()->toArray() : [];

            // ambil old input jika validasi gagal
            $oldTahun = old('tahun');
            $oldObjs = old('objek_belanja_id', []);
            $oldPcts = old('persentase', []);
        @endphp

        <div class="modal fade" id="modalRiwayat">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('asb.riwayat.store', $asb->id) }}" method="POST" id="riwayatForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Riwayat Belanja</h5>
                        </div>

                        <div class="modal-body">
                            {{-- error messages --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $err)
                                        <div>{{ $err }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Tahun</label>
                                <select name="tahun" class="form-control" required>
                                    <option value="" disabled {{ $oldTahun ? '' : 'selected' }}>Pilih tahun…
                                    </option>
                                    @for ($th = 2020; $th < $tahunSekarang; $th++)
                                        @continue(in_array($th, $tahunTerpakai) && $th != $oldTahun)
                                        <option value="{{ $th }}" {{ $oldTahun == $th ? 'selected' : '' }}>
                                            {{ $th }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <table class="table table-sm table-bordered" id="tblInput">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th style="width:40px">No</th>
                                        <th>Objek Belanja</th>
                                        <th style="width:140px">Persentase (%)</th>
                                        <th style="width:60px">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                id="addRow">+</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @push('js')
            <script>
                (function() {
                    const objekOptions = @json($objekBelanja->map(fn($o) => ['id' => $o->id, 'nama' => $o->nama_objek]));
                    const tbody = document.getElementById('tblInput').querySelector('tbody');
                    const addRowBtn = document.getElementById('addRow');

                    function refreshObjekSelects() {
                        const used = Array.from(
                            document.querySelectorAll('select[name="objek_belanja_id[]"]')
                        ).map(s => s.value).filter(v => v);

                        document.querySelectorAll('select[name="objek_belanja_id[]"]').forEach(sel => {
                            const current = sel.value;
                            sel.innerHTML = objekOptions
                                .filter(o => o.id == current || !used.includes(o.id.toString()))
                                .map(o =>
                                    `<option value="${o.id}" ${o.id==current?'selected':''}>${o.nama}</option>`
                                ).join('');
                        });
                    }

                    function addRow(selObj = '', valPct = '') {
                        const idx = tbody.children.length + 1;
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
              <td class="text-center row-no">${idx}</td>
              <td>
                <select name="objek_belanja_id[]" class="form-control" required></select>
              </td>
              <td>
                <input type="number" name="persentase[]" step="0.01"
                       class="form-control" placeholder="0.00"
                       value="${valPct}" required>
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger btn-del">–</button>
              </td>`;
                        tbody.appendChild(tr);

                        refreshObjekSelects();
                        if (selObj) {
                            // pilihkan setelah opsi ter-refresh
                            tr.querySelector('select').value = selObj;
                            refreshObjekSelects();
                        }
                    }

                    // tambahkan baris baru
                    addRowBtn.addEventListener('click', () => addRow());

                    // hapus baris
                    tbody.addEventListener('click', e => {
                        if (e.target.matches('.btn-del')) {
                            e.target.closest('tr').remove();
                            Array.from(tbody.children).forEach((r, i) => r.querySelector('.row-no').textContent = i +
                                1);
                            refreshObjekSelects();
                        }
                    });

                    // ketika salah satu select berubah
                    tbody.addEventListener('change', e => {
                        if (e.target.matches('select[name="objek_belanja_id[]"]')) {
                            refreshObjekSelects();
                        }
                    });

                    // saat modal muncul, bangun ulang dari old() atau satu baris default
                    $('#modalRiwayat').on('shown.bs.modal', () => {
                        tbody.innerHTML = '';
                        const oldObjs = @json(old('objek_belanja_id', []));
                        const oldPcts = @json(old('persentase', []));
                        if (oldObjs.length) {
                            oldObjs.forEach((o, i) => addRow(o, oldPcts[i] ?? ''));
                        } else {
                            addRow();
                        }
                    });

                    // otomatis buka modal jika ada error
                    @if ($errors->any())
                        $('#modalRiwayat').modal('show');
                    @endif

                })();
            </script>
        @endpush

    @endif
@endsection

@push('js')
    <script>
        document.querySelectorAll('.rupiah-input').forEach(i => {
            i.addEventListener('input', e => {
                const v = e.target.value.replace(/\D/g, '');
                e.target.value = v ? new Intl.NumberFormat('id-ID').format(v) : '';
            });
            i.closest('form').addEventListener('submit', () => {
                document.querySelectorAll('.rupiah-input').forEach(inp => {
                    inp.value = inp.value.replace(/\./g, '').replace(/,/g, '');
                });
            });
        });

        function openDriverModal(label = '', action = '#', method = 'POST') {
            const form = document.getElementById('editDriverForm');
            form.action = action;
            let spoof = form.querySelector('input[name="_method"]');
            if (spoof) spoof.remove();
            if (method.toUpperCase() !== 'POST') {
                spoof = document.createElement('input');
                spoof.type = 'hidden';
                spoof.name = '_method';
                spoof.value = method.toUpperCase();
                form.appendChild(spoof);
            }
            document.getElementById('driver_label').value = label;
            $('#editDriverModal').modal('show');
        }

        document.querySelectorAll('.btn-edit-driver').forEach(btn => {
            btn.addEventListener('click', () => openDriverModal(
                btn.dataset.label,
                btn.dataset.action,
                btn.dataset.method
            ));
        });
    </script>
@endpush
