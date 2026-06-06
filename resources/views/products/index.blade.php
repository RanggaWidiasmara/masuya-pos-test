@extends('adminlte::page')

@section('title', 'Data Produk')

@section('content_header')
    <h1>Master Produk</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Produk</h3>
        <div class="card-tools">
            <a href="{{ route('products.create') }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode</th>
                    <th width="30%">Nama Produk</th>
                    <th width="20%">Harga (Rp)</th>
                    <th width="10%">Stok</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->code }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ number_format($p->price, 2, ',', '.') }}</td>
                    <td>{{ $p->stock }}</td>
                    <td class="text-center">
                        <form action="{{ route('products.destroy', $p->id) }}" method="POST">
                            <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
