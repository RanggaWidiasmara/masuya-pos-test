@extends('adminlte::page')

@section('title', 'Data Customer')

@section('content_header')
    <h1>Master Customer</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Customer</h3>
        <div class="card-tools">
            <a href="{{ route('customers.create') }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Tambah Customer
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
                    <th width="10%">Kode</th>
                    <th width="20%">Nama</th>
                    <th width="35%">Alamat Lengkap</th>
                    <th width="10%">Kode Pos</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $c)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $c->code }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->address }}, Kel. {{ $c->village }}, Kec. {{ $c->district }}, {{ $c->city }}, {{ $c->province }}</td>
                    <td>{{ $c->zip_code }}</td>
                    <td class="text-center">
                        <form action="{{ route('customers.destroy', $c->id) }}" method="POST">
                            <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus customer ini?')"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data customer.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
