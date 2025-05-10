@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit Guarantor</h2>
    <form action="{{ route('guarantors.update', $guarantor->id) }}" method="POST" id="editGuarantorForm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $guarantor->customer_id == $customer->id ? 'selected' : '' }}>
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
                    <input class="form-check-input" type="radio" name="guarantor_no" id="guarantor1" value="1" {{ $guarantor->guarantor_no == 1 ? 'checked' : '' }} required>
                    <label class="form-check-label" for="guarantor1">
                        Primary Guarantor (1)
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="guarantor_no" id="guarantor2" value="2" {{ $guarantor->guarantor_no == 2 ? 'checked' : '' }} required>
                    <label class="form-check-label" for="guarantor2">
                        Secondary Guarantor (2)
                    </label>
                </div>
            </div>
            @error('guarantor_no')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div id="guarantor_no_error" class="text-danger" style="display: none;"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $guarantor->name }}" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Father's Name</label>
                    <input type="text" name="father_name" class="form-control" value="{{ $guarantor->father_name }}" required>
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
                    <input type="text" name="nic" class="form-control" value="{{ $guarantor->nic }}" required>
                    @error('nic')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $guarantor->phone }}" required>
                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label>Relation</label>
                    <input type="text" name="relation" class="form-control" value="{{ $guarantor->relation }}" required>
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
                    <textarea name="residence_address" class="form-control" rows="3" required>{{ $guarantor->residence_address }}</textarea>
                    @error('residence_address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Office Address</label>
                    <textarea name="office_address" class="form-control" rows="3">{{ $guarantor->office_address }}</textarea>
                    @error('office_address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label>Occupation</label>
            <input type="text" name="occupation" class="form-control" value="{{ $guarantor->occupation }}">
            @error('occupation')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3 d-flex justify-content-between">
            <div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('guarantors.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
            <div>
                <a href="{{ route('guarantors.show', $guarantor->id) }}" class="btn btn-info">View Details</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Track original customer and guarantor number
    var originalCustomerId = {{ $guarantor->customer_id }};
    var originalGuarantorNo = {{ $guarantor->guarantor_no }};
    
    // Check if guarantor number exists when customer or guarantor number changes
    function checkGuarantorExists() {
        var customerId = $('#customer_id').val();
        var guarantorNo = $('input[name="guarantor_no"]:checked').val();
        
        // Skip check if it's the original customer and guarantor number
        if (customerId == originalCustomerId && guarantorNo == originalGuarantorNo) {
            $('#guarantor_no_error').hide();
            return;
        }
        
        if (customerId && guarantorNo) {
            $.ajax({
                url: '{{ route("guarantors.check") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_id: customerId,
                    guarantor_no: guarantorNo,
                    exclude_id: {{ $guarantor->id }}
                },
                success: function(response) {
                    if (response.exists) {
                        $('#guarantor_no_error').text('Guarantor number ' + guarantorNo + ' already exists for this customer.').show();
                        $('button[type="submit"]').prop('disabled', true);
                    } else {
                        $('#guarantor_no_error').hide();
                        $('button[type="submit"]').prop('disabled', false);
                    }
                }
            });
        }
    }
    
    // Bind change events
    $('#customer_id').change(checkGuarantorExists);
    $('input[name="guarantor_no"]').change(checkGuarantorExists);
    
    // Form validation
    $('#editGuarantorForm').submit(function(e) {
        // Additional client-side validation can go here
        
        // Check if guarantor number error is visible
        if ($('#guarantor_no_error').is(':visible')) {
            e.preventDefault();
            return false;
        }
    });
    
    // NIC formatting
    $('input[name="nic"]').on('input', function() {
        this.value = this.value.replace(/[^0-9V-]/g, '').toUpperCase();
    });
    
    // Phone number formatting
    $('input[name="phone"]').on('input', function() {
        this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
    });
    
    // Auto-capitalize name fields
    $('input[name="name"], input[name="father_name"]').on('input', function() {
        this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
    });
    
    // Add tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush