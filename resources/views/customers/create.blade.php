@extends('adminlte::page')

@section('title', 'Tambah Customer')

@section('content_header')
    <h1>Tambah Customer Baru</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Form Customer</h3></div>
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Customer</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: CUX01" required>
                        @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Customer</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat Jalan / Blok</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="4" required>{{ old('address') }}</textarea>
                        @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province') }}" required>
                        @error('province') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kota / Kabupaten</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                        @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="district" class="form-control @error('district') is-invalid @enderror" value="{{ old('district') }}" required>
                        @error('district') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kelurahan</label>
                        <input type="text" name="village" class="form-control @error('village') is-invalid @enderror" value="{{ old('village') }}" required>
                        @error('village') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror" value="{{ old('zip_code') }}" required>
                        @error('zip_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('customers.index') }}" class="btn btn-default">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Customer</button>
        </div>
    </form>
</div>
@stop
