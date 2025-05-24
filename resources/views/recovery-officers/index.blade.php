@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Recovery Officers</h1>
        <a href="{{ route('recovery-officers.create') }}" class="btn btn-primary">Add New Officer</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Total Collected</th>
                    <th>Collections</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($officers as $officer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $officer->employee_id }}</td>
                    <td>{{ $officer->name }}</td>
                    <td>{{ $officer->phone ?? '-' }}</td>
                    <td>{{ $officer->email ?? '-' }}</td>
                    <td>Rs. {{ number_format($officer->getTotalCollected(), 2) }}</td>
                    <td>{{ $officer->getInstallmentsCount() }}</td>
                    <td>
                        <span class="label label-{{ $officer->is_active ? 'success' : 'danger' }}">
                            {{ $officer->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('recovery-officers.show', $officer) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('recovery-officers.edit', $officer) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('recovery-officers.destroy', $officer) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection