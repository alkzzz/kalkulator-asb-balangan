@extends('layouts.app')

@section('subtitle', isset($user) ? 'Edit User' : 'Tambah User')
@section('content_header_title', isset($user) ? 'Edit User' : 'Tambah User')

@section('content_body')
    <div class="container-fluid">
        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
            @csrf
            @if (isset($user))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control"
                    value="{{ old('username', $user->username ?? '') }}" required>
                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="skpd_id">SKPD</label>
                <select name="skpd_id" id="skpd_id" class="form-control" required>
                    @foreach ($daftar_skpd as $skpd)
                        <option value="{{ $skpd->id }}"
                            {{ isset($user) && $user->skpd_id == $skpd->id ? 'selected' : '' }}>
                            {{ $skpd->nama }}
                        </option>
                    @endforeach
                </select>
                @error('skpd_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </form>
    </div>
@endsection
