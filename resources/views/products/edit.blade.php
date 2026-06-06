@extends('adminlte::page')

@section('title', 'Edit Produk')

@section('content_header')
    <h1>Edit Produk: {{ $product->code }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit Produk</h3>
            </div>
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Produk</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $product->code) }}" required>
                        @error('code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', floatval($product->price)) }}" required min="0" step="0.01">
                        @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required min="0">
                        @error('stock')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-default">Kembali</a>
                    <button type="submit" class="btn btn-warning">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
