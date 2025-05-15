@extends('layouts.app')

@section('subtitle', 'Daftar User')
@section('content_header_title', 'Daftar User')

@section('content_body')
    <div class="container-fluid">
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah User
        </a>

        <table id="user-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width:55%">SKPD</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->skpd->nama ?? '' }}</td>
                        <td>{{ strtolower($user->username) }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>

                            <a href="{{ route('users.resetPassword', $user->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-redo-alt"></i> Reset Password
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('css')
    <style>
        #user-table_wrapper {
            padding-bottom: 1rem;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('#user-table').DataTable();
        });

        $('#user-table').on('click', '.btn-delete', function(e) {
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
