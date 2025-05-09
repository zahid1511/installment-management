@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Guarantors</h2>
    <a href="{{ route('guarantors.create') }}" class="btn btn-primary mb-3">Add Guarantor</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Name</th>
                <th>Father's Name</th>
                <th>NIC</th>
                <th>Phone</th>
                <th>Relation</th>
                <th>Guarantor No</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guarantors as $guarantor)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $guarantor->customer->name }}</td>
                <td>{{ $guarantor->name }}</td>
                <td>{{ $guarantor->father_name }}</td>
                <td>{{ $guarantor->nic }}</td>
                <td>{{ $guarantor->phone }}</td>
                <td>{{ $guarantor->relation }}</td>
                <td>{{ $guarantor->guarantor_no }}</td>
                <td>
                    <a href="{{ route('guarantors.edit', $guarantor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('guarantors.destroy', $guarantor->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this guarantor?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
