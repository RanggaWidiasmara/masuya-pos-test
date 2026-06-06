<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('customer')->orderBy('id', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('stock', '>', 0)->get();

        return view('transactions.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'transaction_date' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        try {
            // Memulai Database Transaction
            DB::transaction(function () use ($request) {

                // 2. Generate Nomor Invoice (Logika dari Sesi 3)
                $invoiceNo = Transaction::generateInvoiceNo();
                $grandTotal = 0; // Inisialisasi total keseluruhan

                // 3. Simpan Header Transaksi (Total amount sementara diisi 0)
                $transaction = Transaction::create([
                    'invoice_no' => $invoiceNo,
                    'customer_id' => $request->customer_id,
                    'transaction_date' => $request->transaction_date,
                    'total_amount' => 0,
                ]);

                // 4. Looping Detail Produk
                foreach ($request->details as $detail) {
                    // Lock produk untuk mencegah race condition pada stok
                    $product = Product::lockForUpdate()->find($detail['product_id']);

                    // Validasi Stok di Backend
                    if ($product->stock < $detail['qty']) {
                        throw new \Exception("Stok produk {$product->code} ({$product->name}) tidak mencukupi. Sisa stok: {$product->stock}");
                    }

                    // Kalkulasi Ulang Diskon & Subtotal di Backend (Keamanan)
                    $price = $detail['price'];
                    $qty = $detail['qty'];
                    $d1 = $detail['disc_1'] ?? 0;
                    $d2 = $detail['disc_2'] ?? 0;
                    $d3 = $detail['disc_3'] ?? 0;

                    // Logika Diskon Bertingkat
                    $net = $price;
                    $net = $net - ($net * ($d1 / 100));
                    $net = $net - ($net * ($d2 / 100));
                    $net = $net - ($net * ($d3 / 100));

                    $subtotal = $net * $qty;
                    $grandTotal += $subtotal;

                    // Kurangi Stok Produk
                    $product->stock -= $qty;
                    $product->save();

                    // Simpan Detail Transaksi
                    $transaction->transactionDetails()->create([
                        'product_id' => $product->id,
                        'qty' => $qty,
                        'price' => $price,
                        'disc_1' => $d1,
                        'disc_2' => $d2,
                        'disc_3' => $d3,
                        'net_price' => $net,
                        'subtotal' => $subtotal,
                    ]);
                }

                // 5. Update Total Keseluruhan di Header
                $transaction->update(['total_amount' => $grandTotal]);
            });

            // Jika sukses, kembali ke halaman index dengan pesan sukses
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan dan stok telah diperbarui!');

            //return "TRANSAKSI SUKSES! Silakan cek database.";

        } catch (\Exception $e) {
            // Jika terjadi error (stok minus atau database gagal), semuanya otomatis di-rollback
            return back()->with('error', 'Transaksi gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        // Load relasi detail dan produk untuk cetak invoice
        $transaction->load(['customer', 'transactionDetails.product']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        // Opsional: Jika transaksi diizinkan untuk diedit
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Opsional: Update transaksi
    }

    public function destroy(Transaction $transaction)
    {
        // Opsional: Hapus atau batalkan transaksi
    }
}
