<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|alpha_num|unique:products,code',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ], [
            'code.alpha_num' => 'Kode produk hanya boleh berisi huruf dan angka tanpa spasi/karakter spesial.',
            'code.unique' => 'Kode produk ini sudah digunakan, silakan masukkan kode lain.'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|alpha_num|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ], [
            'code.alpha_num' => 'Kode produk hanya boleh berisi huruf dan angka tanpa spasi/karakter spesial.',
            'code.unique' => 'Kode produk ini sudah digunakan.'
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('products.index')->with('error', 'Produk tidak bisa dihapus karena sudah ada di dalam transaksi.');
            }
            return redirect()->route('products.index')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
