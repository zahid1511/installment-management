@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Guarantor Details</h2>
        <div>
            <a href="{{ route('guarantors.edit', $guarantor->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('guarantors.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>{{ $guarantor->name }} - Guarantor #{{ $guarantor->guarantor_no }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Customer:</th>
                            <td>{{ $guarantor->customer->name }}</td>
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
                            <td>{{ $guarantor->nic }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $guarantor->phone }}</td>
                        </tr>
                        <tr>
                            <th>Relation:</th>
                            <td>{{ $guarantor->relation }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Guarantor No:</th>
                            <td>
                                <span class="badge bg-{{ $guarantor->guarantor_no == 1 ? 'primary' : 'secondary' }}">
                                    {{ $guarantor->guarantor_no == 1 ? 'Primary' : 'Secondary' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Occupation:</th>
                            <td>{{ $guarantor->occupation ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Residence Address:</th>
                            <td>{{ $guarantor->residence_address }}</td>
                        </tr>
                        <tr>
                            <th>Office Address:</th>
                            <td>{{ $guarantor->office_address ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                Created on: {{ $guarantor->created_at->format('d M, Y h:i A') }}
                @if($guarantor->updated_at != $guarantor->created_at)
                    | Last updated: {{ $guarantor->updated_at->format('d M, Y h:i A') }}
                @endif
            </small>
        </div>
    </div>
</div>
@endsection