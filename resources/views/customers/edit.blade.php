@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Edit Customer</h1>

        <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @if ($customer->image)
                <div class="mb-5" style="text-align: center;">
                    <label>Current Image</label><br>
                    <img src="{{ asset('backend/img/customers/' . $customer->image) }}" alt="Customer Image" width="120"
                        height="120" style="object-fit: cover; border-radius: 8px;">
                </div>
            @endif
            <div class="row mx-5 field">
                <div class="col-md-4">
                    <label for="account_no">Account No <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="account_no" name="account_no"
                        value="{{ $customer->account_no }}" required>
                </div>

                <div class="col-md-4">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label for="father_name">Father Name</label>
                    <input type="text" class="form-control" id="father_name" name="father_name"
                        value="{{ $customer->father_name }}">
                </div>
            </div>

            <div class="row mt-3 field">

                <div class="col-md-4">
                    <label for="residential_type">Residential Type</label>
                    <input type="text" class="form-control" id="residential_type" name="residential_type"
                        value="{{ $customer->residential_type }}">
                </div>

                <div class="col-md-4">
                    <label for="occupation">Occupation</label>
                    <input type="text" class="form-control" id="occupation" name="occupation"
                        value="{{ $customer->occupation }}">
                </div>

                <div class="col-md-4">
                    <label for="residence">Residence</label>
                    <input type="text" class="form-control" id="residence" name="residence"
                        value="{{ $customer->residence }}">
                </div>


            </div>

            <div class="row mt-3 field">
                <div class="col-md-4">
                    <label for="office_address">Office Address</label>
                    <input type="text" class="form-control" id="office_address" name="office_address"
                        value="{{ $customer->office_address }}">
                </div>
                <div class="col-md-4">
                    <label for="mobile_1">Mobile 1 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mobile_1" name="mobile_1"
                        value="{{ $customer->mobile_1 }}" required>
                </div>
                <div class="col-md-4">
                    <label for="mobile_2">Mobile 2</label>
                    <input type="text" class="form-control" id="mobile_2" name="mobile_2"
                        value="{{ $customer->mobile_2 }}">
                </div>

            </div>

            <div class="row mt-3 field">
                <div class="col-md-4">
                    <label for="nic">NIC</label>
                    <input type="text" class="form-control" id="nic" name="nic" value="{{ $customer->nic }}">
                </div>

                <div class="col-md-4">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="">Select</option>
                        <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label for="image">Upload New Image</label>
                        <input type="file" class="form-control" name="image" id="image" accept="image/*">
                    </div>
                </div>

            </div>

            <div class="row mt-3 field">
                <div class="col-md-12">
                    <label for="is_defaulter">Is Defaulter</label>
                    <select class="form-control" id="is_defaulter" name="is_defaulter">
                        <option value="0" {{ $customer->is_defaulter == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $customer->is_defaulter == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 button-update">
                <button style="padding: 10px 20px 10px 20px !important;" type="submit" class="btn btn-success button-design">Update</button>
            </div>

        </form>
    </div>
@endsection

<style>
.container-fluid{
    height: 100%;
}
.field{
    margin: 10px;
}

.button-update{
    text-align: center;
}
</style>