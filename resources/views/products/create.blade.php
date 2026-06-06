@extends('adminlte::page')

@section('title', 'Tambah Produk')

@section('content_header')
    <h1>Tambah Produk Baru</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Produk</h3>
            </div>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Produk</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: P001" required>
                        @error('code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Kopi Hitam" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required min="0" step="0.01">
                        @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" required min="0">
                        @error('stock')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-default">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
