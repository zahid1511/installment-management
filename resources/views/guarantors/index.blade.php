@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Guarantors</h2>
        <a href="{{ route('guarantors.create') }}" class="btn btn-primary">Add Guarantor</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Name</th>
                        <th>Father's Name</th>
                        <th>NIC</th>
                        <th>Phone</th>
                        <th>Relation</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guarantors as $guarantor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $guarantor->customer->name }}</strong>
                        </td>
                        <td>{{ $guarantor->name }}</td>
                        <td>{{ $guarantor->father_name }}</td>
                        <td>{{ $guarantor->nic }}</td>
                        <td>{{ $guarantor->phone }}</td>
                        <td>{{ $guarantor->relation }}</td>
                        <td>
                            <span class="badge bg-{{ $guarantor->guarantor_no == 1 ? 'primary' : 'secondary' }}">
                                {{ $guarantor->guarantor_no == 1 ? 'Primary' : 'Secondary' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('guarantors.show', $guarantor->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('guarantors.edit', $guarantor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('guarantors.destroy', $guarantor->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this guarantor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <p class="text-muted">No guarantors found.</p>
                            <a href="{{ route('guarantors.create') }}" class="btn btn-primary btn-sm">Add First Guarantor</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection