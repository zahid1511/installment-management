<!-- resources/views/products/create.blade.php -->
@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Product</h1>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" class="form-control" id="company" name="company" required>
        </div>

        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" class="form-control" id="model" name="model" required>
        </div>

        <div class="form-group">
            <label for="serial_no">Serial No</label>
            <input type="text" class="form-control" id="serial_no" name="serial_no" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
