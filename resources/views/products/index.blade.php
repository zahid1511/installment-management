<!-- resources/views/products/index.blade.php -->
@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Products</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add New Product</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Company</th>
                <th>Model</th>
                <th>Serial No</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->company }}</td>
                    <td>{{ $product->model }}</td>
                    <td>{{ $product->serial_no }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection