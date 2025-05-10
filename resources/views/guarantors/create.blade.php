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
                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
            @error('customer_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Guarantor Number</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="guarantor_no" id="guarantor1" value="1" {{ old('guarantor_no') == '1' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="guarantor1">
                        Primary Guarantor (1)
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="guarantor_no" id="guarantor2" value="2" {{ old('guarantor_no') == '2' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="guarantor2">
                        Secondary Guarantor (2)
                    </label>
                </div>
            </div>
            @error('guarantor_no')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Father's Name</label>
                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" required>
                    @error('father_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label>NIC</label>
                    <input type="text" name="nic" class="form-control" value="{{ old('nic') }}" required>
                    @error('nic')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label>Relation</label>
                    <input type="text" name="relation" class="form-control" value="{{ old('relation') }}" required>
                    @error('relation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Residence Address</label>
                    <textarea name="residence_address" class="form-control" rows="3" required>{{ old('residence_address') }}</textarea>
                    @error('residence_address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Office Address</label>
                    <textarea name="office_address" class="form-control" rows="3">{{ old('office_address') }}</textarea>
                    @error('office_address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label>Occupation</label>
            <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}">
            @error('occupation')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('guarantors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection