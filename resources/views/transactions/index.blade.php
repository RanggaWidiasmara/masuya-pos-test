@extends('adminlte::page')

@section('title', 'Riwayat Transaksi')

@section('content_header')
    <h1>Riwayat Transaksi</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Transaksi</h3>
        <div class="card-tools">
            <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Transaksi Baru
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
                    <th width="15%">No Invoice</th>
                    <th width="15%">Tanggal</th>
                    <th width="25%">Customer</th>
                    <th width="20%">Total (Rp)</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $trx->invoice_no }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('d-m-Y') }}</td>
                    <td>{{ $trx->customer->name }}</td>
                    <td>{{ number_format($trx->total_amount, 2, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('transactions.show', $trx->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-print"></i> Detail & Cetak
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
