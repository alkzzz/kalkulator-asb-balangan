@extends('layouts.app')

@section('subtitle', 'Objek Belanja')
@section('content_header_title', 'Objek Belanja')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_body')
    <div class="container-fluid table-wrapper">
        <a href="{{ route('objek.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Data
        </a>

        <table id="objek-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width:30px">No</th>
                    <th>Nama Objek Belanja</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $i => $row)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $row->nama_objek }}</td>
                        <td class="text-center">
                            <a href="{{ route('objek.edit', $row->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('objek.destroy', $row->id) }}" method="POST"
                                class="d-inline form-delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('css')
    <style>
        #objek-table_wrapper {
            padding-bottom: 1rem
        }
    </style>
@endpush

@push('js')
    <script>
        $(function() {
            $('#objek-table').DataTable();
            $('#objek-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const f = $(this).closest('form');
                Swal.fire({
                    title: 'Hapus Data?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then(r => {
                    if (r.isConfirmed) f.submit();
                });
            });
        });
    </script>
@endpush
