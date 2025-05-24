@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Add New Customer</h1>

    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <label for="account_no">Account No <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="account_no" name="account_no" required>
            </div>

            <div class="col-md-6">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="father_name">Father Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name">
            </div>

            <div class="col-md-6">
                <label for="residential_type">Residential Type</label>
                <input type="text" class="form-control" id="residential_type" name="residential_type">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="occupation">Occupation</label>
                <input type="text" class="form-control" id="occupation" name="occupation">
            </div>

            <div class="col-md-6">
                <label for="residence">Residence</label>
                <input type="text" class="form-control" id="residence" name="residence">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="office_address">Office Address</label>
                <input type="text" class="form-control" id="office_address" name="office_address">
            </div>

            <div class="col-md-6">
                <label for="mobile_1">Mobile 1 <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="mobile_1" name="mobile_1" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="mobile_2">Mobile 2</label>
                <input type="text" class="form-control" id="mobile_2" name="mobile_2">
            </div>

            <div class="col-md-6">
                <label for="nic">NIC</label>
                <input type="text" class="form-control" id="nic" name="nic">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" id="gender">
                    <option value="">Select</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

                <div class="col-md-3">
                    <label for="image">Customer Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>


            <div class="col-md-3">
                <label for="total_price">Total Price</label>
                <input type="number" class="form-control" id="total_price" name="total_price">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="installment_amount">Installment Amount</label>
                <input type="number" class="form-control" id="installment_amount" name="installment_amount">
            </div>

            <div class="col-md-6">
                <label for="installments">Installments</label>
                <input type="number" class="form-control" id="installments" name="installments">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="advance">Advance</label>
                <input type="number" class="form-control" id="advance" name="advance">
            </div>

            <div class="col-md-6">
                <label for="balance">Balance</label>
                <input type="number" class="form-control" id="balance" name="balance">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="is_defaulter">Is Defaulter</label>
                <select class="form-control" id="is_defaulter" name="is_defaulter">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>

    </form>
</div>
@endsection
