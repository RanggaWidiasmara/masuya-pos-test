@extends('adminlte::page')

@section('title', 'Detail Invoice')

@section('content_header')
    <h1>Invoice: {{ $transaction->invoice_no }}</h1>
@stop

@section('content')
<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> PT Masuya (Test IT Development)
                <small class="float-right">Tanggal: {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</small>
            </h4>
        </div>
    </div>

    <div class="row invoice-info mt-4">
        <div class="col-sm-4 invoice-col">
            Kepada Yth:
            <address>
                <strong>{{ $transaction->customer->code }} - {{ $transaction->customer->name }}</strong><br>
                {{ $transaction->customer->address }}<br>
                Kel. {{ $transaction->customer->village }}, Kec. {{ $transaction->customer->district }}<br>
                {{ $transaction->customer->city }}, {{ $transaction->customer->province }} {{ $transaction->customer->zip_code }}
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            </div>
        <div class="col-sm-4 invoice-col">
            <b>Invoice #{{ $transaction->invoice_no }}</b><br>
            <br>
            <b>Status:</b> Lunas (Simulasi)<br>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Qty</th>
                        <th>Diskon 1, 2, 3 (%)</th>
                        <th>Harga Net</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->transactionDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->code }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td>Rp {{ number_format($detail->price, 2, ',', '.') }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>{{ floatval($detail->disc_1) }}%, {{ floatval($detail->disc_2) }}%, {{ floatval($detail->disc_3) }}%</td>
                        <td>Rp {{ number_format($detail->net_price, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                Terima kasih telah berbelanja. Struk ini merupakan bukti transaksi yang sah.
            </p>
        </div>
        <div class="col-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Grand Total:</th>
                        <td><strong>Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row no-print mt-3">
        <div class="col-12">
            <button onclick="window.print()" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-primary float-right" style="margin-right: 5px;">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>
</div>
@stop
