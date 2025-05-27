@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Guarantors</h2>
            <a href="{{ route('guarantors.create') }}" class="btn btn-primary">Add Guarantor</a>
        </div>

        @if (session('success'))
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
                            <th>Image</th>
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
                                    @if ($guarantor->image)
                                        <img src="{{ asset($guarantor->image) }}" alt="Guarantor Image" 
                                             width="50" height="50" class="rounded-circle object-fit-cover">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; font-size: 14px;">
                                            {{ strtoupper(substr($guarantor->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $guarantor->customer->name }}</strong><br>
                                    <small class="text-muted">{{ $guarantor->customer->account_no }}</small>
                                </td>
                                <td>{{ $guarantor->name }}</td>
                                <td>{{ $guarantor->father_name }}</td>
                                <td><code>{{ $guarantor->nic }}</code></td>
                                <td>{{ $guarantor->phone }}</td>
                                <td>{{ $guarantor->relation }}</td>
                                <td>
                                    @php
                                        switch($guarantor->guarantor_no) {
                                            case 1:
                                                $label = 'Primary';
                                                $color = 'primary';
                                                break;
                                            case 2:
                                                $label = 'Secondary';
                                                $color = 'secondary';
                                                break;
                                            case 3:
                                                $label = 'Third';
                                                $color = 'info';
                                                break;
                                            case 4:
                                                $label = 'Reserve';
                                                $color = 'dark';
                                                break;
                                            default:
                                                $label = 'Unknown';
                                                $color = 'light';
                                        }
                                    @endphp

                                    <span class="badge bg-{{ $color }}">{{ $label }} Guarantor</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('guarantors.show', $guarantor->id) }}"
                                            class="btn btn-sm btn-info" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('guarantors.edit', $guarantor->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('guarantors.destroy', $guarantor->id) }}" method="POST"
                                            class="" style="display: grid;"
                                            onsubmit="return confirm('Are you sure you want to delete this guarantor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <p class="text-muted">No guarantors found.</p>
                                    <a href="{{ route('guarantors.create') }}" class="btn btn-primary btn-sm">Add First Guarantor</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Pagination -->
                @if ($guarantors->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $guarantors->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.object-fit-cover {
    object-fit: cover;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.9em;
}
</style>
@endpush

@push('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            paging: false,
            info: false,
            ordering: true,
            searching: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [1, -1] } // Disable sorting on image and actions columns
            ]
        });
    });
</script>
@endpush