<!-- resources/views/products/edit.blade.php -->
@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Product</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $product->company) }}" required>
        </div>

        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" class="form-control" id="model" name="model" value="{{ old('model', $product->model) }}" required>
        </div>

        <div class="form-group">
            <label for="serial_no">Serial No</label>
            <input type="text" class="form-control" id="serial_no" name="serial_no" value="{{ old('serial_no', $product->serial_no) }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection