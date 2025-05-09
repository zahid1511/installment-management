@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Customer</h1>

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <label for="account_no">Account No <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="account_no" name="account_no" value="{{ $customer->account_no }}" required>
            </div>

            <div class="col-md-6">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="father_name">Father Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" value="{{ $customer->father_name }}">
            </div>

            <div class="col-md-6">
                <label for="residential_type">Residential Type</label>
                <input type="text" class="form-control" id="residential_type" name="residential_type" value="{{ $customer->residential_type }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="occupation">Occupation</label>
                <input type="text" class="form-control" id="occupation" name="occupation" value="{{ $customer->occupation }}">
            </div>

            <div class="col-md-6">
                <label for="residence">Residence</label>
                <input type="text" class="form-control" id="residence" name="residence" value="{{ $customer->residence }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="office_address">Office Address</label>
                <input type="text" class="form-control" id="office_address" name="office_address" value="{{ $customer->office_address }}">
            </div>

            <div class="col-md-6">
                <label for="mobile_1">Mobile 1 <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="mobile_1" name="mobile_1" value="{{ $customer->mobile_1 }}" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="mobile_2">Mobile 2</label>
                <input type="text" class="form-control" id="mobile_2" name="mobile_2" value="{{ $customer->mobile_2 }}">
            </div>

            <div class="col-md-6">
                <label for="nic">NIC</label>
                <input type="text" class="form-control" id="nic" name="nic" value="{{ $customer->nic }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Select</option>
                    <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="total_price">Total Price</label>
                <input type="number" class="form-control" id="total_price" name="total_price" value="{{ $customer->total_price }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="installment_amount">Installment Amount</label>
                <input type="number" class="form-control" id="installment_amount" name="installment_amount" value="{{ $customer->installment_amount }}">
            </div>

            <div class="col-md-6">
                <label for="installments">Installments</label>
                <input type="number" class="form-control" id="installments" name="installments" value="{{ $customer->installments }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="advance">Advance</label>
                <input type="number" class="form-control" id="advance" name="advance" value="{{ $customer->advance }}">
            </div>

            <div class="col-md-6">
                <label for="balance">Balance</label>
                <input type="number" class="form-control" id="balance" name="balance" value="{{ $customer->balance }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="is_defaulter">Is Defaulter</label>
                <select class="form-control" id="is_defaulter" name="is_defaulter">
                    <option value="0" {{ $customer->is_defaulter == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $customer->is_defaulter == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Update</button>
        </div>

    </form>
</div>
@endsection
