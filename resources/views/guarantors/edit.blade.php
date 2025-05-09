@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit Guarantor</h2>
    <form action="{{ route('guarantors.update', $guarantor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="customer_id">Customer</label>
            <select name="customer_id" class="form-control" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $guarantor->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $guarantor->name }}" required>
        </div>

        <div class="mb-3">
            <label>Father's Name</label>
            <input type="text" name="father_name" class="form-control" value="{{ $guarantor->father_name }}" required>
        </div>

        <div class="mb-3">
            <label>NIC</label>
            <input type="text" name="nic" class="form-control" value="{{ $guarantor->nic }}" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $guarantor->phone }}" required>
        </div>

        <div class="mb-3">
            <label>Relation</label>
            <input type="text" name="relation" class="form-control" value="{{ $guarantor->relation }}" required>
        </div>

        <div class="mb-3">
            <label>Guarantor No</label>
            <input type="number" name="guarantor_no" class="form-control" value="{{ $guarantor->guarantor_no }}" required>
        </div>

        <div class="mb-3">
            <label>Residence Address</label>
            <textarea name="residence_address" class="form-control" required>{{ $guarantor->residence_address }}</textarea>
        </div>

        <div class="mb-3">
            <label>Office Address</label>
            <textarea name="office_address" class="form-control">{{ $guarantor->office_address }}</textarea>
        </div>

        <div class="mb-3">
            <label>Occupation</label>
            <input type="text" name="occupation" class="form-control" value="{{ $guarantor->occupation }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('guarantors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
