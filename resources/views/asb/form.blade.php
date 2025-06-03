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

                                    <form class="form-delete-driver d-inline"
                                        data-action="{{ route('asb.cost-driver.destroy', [$asb->id, $driver->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-driver">
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
                                <input type="text" name="label" id="new_driver_label" class="form-control" required>
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
    @endif
@endsection

@push('js')
    <script>
        function openDriverModal(label = '', action = '#', method = 'POST') {
            const form = document.getElementById('editDriverForm');
            form.action = action;

            // Hapus spoof _method sebelumnya jika ada
            let spoof = form.querySelector('input[name="_method"]');
            if (spoof) spoof.remove();

            // Tambahkan spoof _method jika bukan POST
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

        document.addEventListener('DOMContentLoaded', () => {
            // Handle tombol edit
            document.querySelectorAll('.btn-edit-driver').forEach(btn => {
                btn.addEventListener('click', function() {
                    openDriverModal(
                        this.dataset.label,
                        this.dataset.action,
                        this.dataset.method
                    );
                });
            });

            // Handle tombol delete + SweetAlert
            document.querySelectorAll('.btn-delete-driver').forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('.form-delete-driver');
                    const action = form.getAttribute('data-action');
                    const csrfToken = form.querySelector('input[name="_token"]').value;

                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const tempForm = document.createElement('form');
                            tempForm.method = 'POST';
                            tempForm.action = action;

                            tempForm.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;

                            document.body.appendChild(tempForm);
                            tempForm.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
