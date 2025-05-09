<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('customer')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('products.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'company' => 'required',
            'model' => 'required',
            'serial_no' => 'required',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    public function edit(Product $product)
    {
        $customers = Customer::all();
        return view('products.edit', compact('product', 'customers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'company' => 'required',
            'model' => 'required',
            'serial_no' => 'required',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}