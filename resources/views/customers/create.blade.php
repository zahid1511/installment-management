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
                <select class="form-control" name="residential_type" id="residential_type">
                    <option value="">Select Type</option>
                    <option value="Personal">Personal</option>
                    <option value="Rental">Rental</option>
                    <option value="Family">Family</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="occupation">Occupation</label>
                <input type="text" class="form-control" id="occupation" name="occupation">
            </div>

            <div class="col-md-6">
                <label for="residence">Residence Address</label>
                <textarea class="form-control" id="residence" name="residence" rows="2"></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="office_address">Office Address</label>
                <textarea class="form-control" id="office_address" name="office_address" rows="2"></textarea>
            </div>

            <div class="col-md-6">
                <label for="mobile_1">Primary Mobile <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="mobile_1" name="mobile_1" required pattern="[0-9]{11}" placeholder="03xxxxxxxxx">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="mobile_2">Secondary Mobile</label>
                <input type="tel" class="form-control" id="mobile_2" name="mobile_2" pattern="[0-9]{11}" placeholder="03xxxxxxxxx">
            </div>

            <div class="col-md-6">
                <label for="nic">NIC <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nic" name="nic" required pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" placeholder="35404-1234567-1">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" id="gender">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="image">Customer Photo</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <small class="form-text text-muted">Upload customer photo (optional)</small>
            </div>
        </div>

        <!-- ✅ REMOVED: All financial fields (total_price, installment_amount, installments, advance, balance) -->
        <!-- These will be calculated from purchases and installments -->

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>Note:</strong> Financial information (purchases, installments, balances) will be managed through the Purchase section after creating this customer.
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Customer</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

@push('script')
<script>
// Format NIC input
$('#nic').on('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length >= 5) {
        value = value.substr(0, 5) + '-' + value.substr(5);
    }
    if (value.length >= 13) {
        value = value.substr(0, 13) + '-' + value.substr(13, 1);
    }
    this.value = value;
});

// Format mobile input
$('#mobile_1, #mobile_2').on('input', function() {
    this.value = this.value.replace(/\D/g, '').substr(0, 11);
});
</script>
@endpush
@endsection