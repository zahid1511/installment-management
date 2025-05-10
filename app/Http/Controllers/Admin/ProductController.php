<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();  // Removed 'with('customer')'
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // No need to pass customers anymore
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Removed 'customer_id' validation
            'company' => 'required',
            'model' => 'required',
            'serial_no' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    public function show(Product $product)
    {
        // Optional: Show product details with purchase history
        $product->load('purchases.customer');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // No need to pass customers anymore
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            // Removed 'customer_id' validation
            'company' => 'required',
            'model' => 'required',
            'serial_no' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // Check if product has been purchased before deleting
        if ($product->purchases()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product. It has purchase history.');
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}