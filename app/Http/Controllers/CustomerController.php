<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|alpha_num|unique:customers,code',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'zip_code' => 'required|alpha_num|max:10',
        ], [
            'code.alpha_num' => 'Kode customer hanya boleh berisi huruf dan angka tanpa karakter spesial.',
            'code.unique' => 'Kode customer ini sudah digunakan.'
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'code' => 'required|alpha_num|unique:customers,code,' . $customer->id,
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'zip_code' => 'required|alpha_num|max:10',
        ], [
            'code.alpha_num' => 'Kode customer hanya boleh berisi huruf dan angka.',
            'code.unique' => 'Kode customer ini sudah digunakan.'
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('customers.index')->with('error', 'Customer tidak bisa dihapus karena telah memiliki riwayat transaksi.');
            }
            return redirect()->route('customers.index')->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}
