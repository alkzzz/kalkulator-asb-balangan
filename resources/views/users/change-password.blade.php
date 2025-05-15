@extends('layouts.app')

@section('subtitle', 'Ubah Password User')
@section('content_header_title', 'Ubah Password: ' . $user->name)

@section('content_body')
    <div class="container">
        <form action="{{ route('users.update.password') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="current_password">Password Lama</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
                @error('current_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
                @error('new_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Ubah Password</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
