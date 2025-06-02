@extends('layouts.app')

@section('subtitle', $title)
@section('content_header_title', $title)

@section('content_body')
    <div class="container-fluid pb-4">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="asb_id">ASB</label>
                <select name="asb_id" id="asb_id" class="form-control" required>
                    <option value="">-- Pilih ASB --</option>
                    @foreach ($asbList as $asb)
                        <option value="{{ $asb->id }}"
                            {{ old('asb_id', $riwayat->asb_id ?? '') == $asb->id ? 'selected' : '' }}>
                            {{ $asb->kode }} - {{ $asb->nama }}
                        </option>
                    @endforeach
                </select>
                @error('asb_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="tahun">Tahun</label>
                <input type="number" name="tahun" id="tahun" class="form-control"
                    value="{{ old('tahun', $riwayat->tahun ?? now()->year - 1) }}" required>
                @error('tahun')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="objek_belanja_id">Objek Belanja</label>
                <select name="objek_belanja_id" id="objek_belanja_id" class="form-control" required>
                    <option value="">-- Pilih Objek --</option>
                    @foreach ($objekBelanja as $objek)
                        <option value="{{ $objek->id }}"
                            {{ old('objek_belanja_id', $riwayat->objek_belanja_id ?? '') == $objek->id ? 'selected' : '' }}>
                            {{ $objek->nama_objek }}
                        </option>
                    @endforeach
                </select>
                @error('objek_belanja_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="persentase">Persentase (%)</label>
                <input type="number" step="0.01" name="persentase" id="persentase" class="form-control"
                    value="{{ old('persentase', $riwayat->persentase ?? '') }}" required>
                @error('persentase')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="nilai_rupiah">Nilai (Rp)</label>
                <input type="number" name="nilai_rupiah" id="nilai_rupiah" class="form-control"
                    value="{{ old('nilai_rupiah', $riwayat->nilai_rupiah ?? '') }}" required>
                @error('nilai_rupiah')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="2">{{ old('keterangan', $riwayat->keterangan ?? '') }}</textarea>
                @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('riwayat-belanja.index') }}" class="btn btn-danger">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>
    </div>
@endsection
