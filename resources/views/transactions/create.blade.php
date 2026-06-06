@extends('adminlte::page')

@section('title', 'Buat Transaksi Baru')

@section('content_header')
    <h1>Buat Transaksi Baru</h1>
@stop

@section('content')
<form action="{{ route('transactions.store') }}" method="POST" id="formTransaction">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Informasi Pelanggan</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control" required>
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Transaksi</label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-success">
                <div class="card-header"><h3 class="card-title">Detail Produk</h3></div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-sm" id="detailTable">
                        <thead>
                            <tr>
                                <th width="25%">Produk</th>
                                <th width="12%">Harga</th>
                                <th width="8%">Qty</th>
                                <th width="8%">Disc 1(%)</th>
                                <th width="8%">Disc 2(%)</th>
                                <th width="8%">Disc 3(%)</th>
                                <th width="12%">Net Price</th>
                                <th width="14%">Subtotal</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detailTbody">
                            </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-right">Total Keseluruhan</th>
                                <th>
                                    <input type="text" id="grandTotalDisplay" class="form-control form-control-sm" readonly value="0">
                                    <input type="hidden" name="total_amount" id="grandTotalValue" value="0">
                                </th>
                                <th>
                                    <button type="button" class="btn btn-sm btn-primary" id="addRow"><i class="fas fa-plus"></i></button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('transactions.index') }}" class="btn btn-default">Batal</a>
                    <button type="submit" class="btn btn-success" id="btnSubmit">Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
</form>

<select id="productTemplate" style="display:none;">
    <option value="">- Pilih -</option>
    @foreach($products as $p)
        <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock }}">{{ $p->code }} - {{ $p->name }}</option>
    @endforeach
</select>
@stop

@section('js')
<script>
$(document).ready(function() {
    let rowIdx = 0;

    // Fungsi menambah baris baru
    $('#addRow').click(function() {
        let productOptions = $('#productTemplate').html();
        let tr = `
            <tr id="row_${rowIdx}">
                <td>
                    <select name="details[${rowIdx}][product_id]" class="form-control form-control-sm product-select" required>
                        ${productOptions}
                    </select>
                    <small class="text-danger stock-info" style="display:none;"></small>
                </td>
                <td><input type="number" name="details[${rowIdx}][price]" class="form-control form-control-sm input-price" required step="0.01"></td>
                <td><input type="number" name="details[${rowIdx}][qty]" class="form-control form-control-sm input-qty" required min="1"></td>
                <td><input type="number" name="details[${rowIdx}][disc_1]" class="form-control form-control-sm input-disc" value="0" step="0.01" min="0" max="100"></td>
                <td><input type="number" name="details[${rowIdx}][disc_2]" class="form-control form-control-sm input-disc" value="0" step="0.01" min="0" max="100"></td>
                <td><input type="number" name="details[${rowIdx}][disc_3]" class="form-control form-control-sm input-disc" value="0" step="0.01" min="0" max="100"></td>
                <td><input type="number" name="details[${rowIdx}][net_price]" class="form-control form-control-sm input-net" readonly></td>
                <td><input type="number" name="details[${rowIdx}][subtotal]" class="form-control form-control-sm input-subtotal" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger btn-remove"><i class="fas fa-trash"></i></button></td>
            </tr>
        `;
        $('#detailTbody').append(tr);
        rowIdx++;
    });

    // Event ketika produk dipilih (mengisi harga otomatis & info stok)
    $(document).on('change', '.product-select', function() {
        let selectedOption = $(this).find('option:selected');
        let price = selectedOption.data('price') || 0;
        let stock = selectedOption.data('stock') || 0;
        let row = $(this).closest('tr');

        row.find('.input-price').val(price);
        row.find('.input-qty').val(1).attr('max', stock);

        let stockInfo = row.find('.stock-info');
        if($(this).val() !== "") {
            stockInfo.text('Stok: ' + stock).show();
        } else {
            stockInfo.hide();
        }

        calculateRow(row);
    });

    // Event ketika harga, qty, atau diskon diubah
    $(document).on('input', '.input-price, .input-qty, .input-disc', function() {
        let row = $(this).closest('tr');

        // Validasi Qty vs Stok di sisi Frontend
        let qtyInput = row.find('.input-qty');
        let maxStock = parseInt(qtyInput.attr('max'));
        if (parseInt(qtyInput.val()) > maxStock) {
            alert('Qty tidak boleh melebihi sisa stok (' + maxStock + ')!');
            qtyInput.val(maxStock);
        }

        calculateRow(row);
    });

    // Hapus baris
    $(document).on('click', '.btn-remove', function() {
        $(this).closest('tr').remove();
        calculateGrandTotal();
    });

    // Fungsi kalkulasi per baris (Diskon Bertingkat)
    function calculateRow(row) {
        let price = parseFloat(row.find('.input-price').val()) || 0;
        let qty = parseFloat(row.find('.input-qty').val()) || 0;
        let d1 = parseFloat(row.find('input[name*="[disc_1]"]').val()) || 0;
        let d2 = parseFloat(row.find('input[name*="[disc_2]"]').val()) || 0;
        let d3 = parseFloat(row.find('input[name*="[disc_3]"]').val()) || 0;

        // Logika Diskon Bertingkat
        let net = price;
        net = net - (net * (d1 / 100));
        net = net - (net * (d2 / 100));
        net = net - (net * (d3 / 100));

        let subtotal = net * qty;

        row.find('.input-net').val(net.toFixed(2));
        row.find('.input-subtotal').val(subtotal.toFixed(2));

        calculateGrandTotal();
    }

    // Fungsi kalkulasi total keseluruhan
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.input-subtotal').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });

        $('#grandTotalValue').val(grandTotal.toFixed(2));
        $('#grandTotalDisplay').val(grandTotal.toLocaleString('id-ID'));
    }

    // Tambah 1 baris kosong otomatis saat halaman dimuat
    $('#addRow').click();
});
</script>
@stop
