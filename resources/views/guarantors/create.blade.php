@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Create Guarantor</h2>
    <form action="{{ route('guarantors.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="customer_id">Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Father's Name</label>
            <input type="text" name="father_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>NIC</label>
            <input type="text" name="nic" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Relation</label>
            <input type="text" name="relation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Guarantor No</label>
            <input type="number" name="guarantor_no" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Residence Address</label>
            <textarea name="residence_address" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Office Address</label>
            <textarea name="office_address" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Occupation</label>
            <input type="text" name="occupation" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('guarantors.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
