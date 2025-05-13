@extends('layouts.app')

@section('subtitle', 'Daftar SKPD')
@section('content_header_title', 'Daftar SKPD')

@section('content_body')
    <div class="container-fluid">
        <a href="{{ route('data-skpd.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah SKPD
        </a>

        <table id="skpd-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Singkatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($skpd as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->singkatan }}</td>
                        <td>
                            <a href="{{ route('data-skpd.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <form action="{{ route('data-skpd.destroy', $item->id) }}" method="POST"
                                class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
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
        #skpd-table_wrapper {
            padding-bottom: 1rem;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('#skpd-table').DataTable();
        });

        $('#skpd-table').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

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
                    form.submit();
                }
            });
        });
    </script>
@endpush
