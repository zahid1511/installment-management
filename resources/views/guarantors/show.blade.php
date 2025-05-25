@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Guarantor Details</h2>
        <div>
            <a href="{{ route('guarantors.edit', $guarantor->id) }}" class="btn btn-warning">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('guarantors.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Image Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Guarantor Photo</h5>
                </div>
                <div class="card-body text-center">
                    @if ($guarantor->image)
                        <img src="{{ asset($guarantor->image) }}" alt="Guarantor Image" 
                             class="img-fluid rounded" style="max-width: 250px; max-height: 300px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 150px; height: 150px; font-size: 3em;">
                            {{ strtoupper(substr($guarantor->name, 0, 2)) }}
                        </div>
                        <p class="text-muted mt-2">No image uploaded</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $guarantor->name }} - 
                        <span class="badge bg-{{ $guarantor->guarantor_no == 1 ? 'primary' : 'secondary' }}">
                            {{ $guarantor->guarantor_no == 1 ? 'Primary' : 'Secondary' }} Guarantor
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Personal Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Customer:</th>
                                    <td>
                                        <strong>{{ $guarantor->customer->name }}</strong><br>
                                        <small class="text-muted">Account: {{ $guarantor->customer->account_no }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $guarantor->name }}</td>
                                </tr>
                                <tr>
                                    <th>Father's Name:</th>
                                    <td>{{ $guarantor->father_name }}</td>
                                </tr>
                                <tr>
                                    <th>NIC:</th>
                                    <td><code>{{ $guarantor->nic }}</code></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>
                                        <a href="tel:{{ $guarantor->phone }}" class="text-decoration-none">
                                            <i class="fa fa-phone text-success"></i> {{ $guarantor->phone }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Relation:</th>
                                    <td>
                                        <span class="badge bg-info">{{ $guarantor->relation }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Professional & Address</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Guarantor Type:</th>
                                    <td>
                                        <span class="badge bg-{{ $guarantor->guarantor_no == 1 ? 'primary' : 'secondary' }}">
                                            {{ $guarantor->guarantor_no == 1 ? 'Primary' : 'Secondary' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Occupation:</th>
                                    <td>{{ $guarantor->occupation ?: 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Residence:</th>
                                    <td>
                                        <div class="small">{{ $guarantor->residence_address }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Office:</th>
                                    <td>
                                        <div class="small">{{ $guarantor->office_address ?: 'Not specified' }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-primary">Related Customer Information</h6>
                            <div class="alert alert-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Customer Details:</strong><br>
                                        Name: {{ $guarantor->customer->name }}<br>
                                        Account: {{ $guarantor->customer->account_no }}<br>
                                        Mobile: {{ $guarantor->customer->mobile_1 }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Actions:</strong><br>
                                        <a href="{{ route('customers.statement', $guarantor->customer->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-file-text"></i> View Customer Statement
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        <i class="fa fa-calendar"></i> Created: {{ $guarantor->created_at->format('d M, Y h:i A') }}
                        @if($guarantor->updated_at != $guarantor->created_at)
                            | <i class="fa fa-edit"></i> Last updated: {{ $guarantor->updated_at->format('d M, Y h:i A') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.table th {
    color: #495057;
    font-weight: 600;
    border: none;
    padding: 8px 0;
}

.table td {
    border: none;
    padding: 8px 0;
}

.badge {
    font-size: 0.8em;
}

code {
    background-color: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.9em;
}

.alert-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
</style>
@endpush