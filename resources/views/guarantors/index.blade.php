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
                                <td>
                                    @if ($guarantor->image)
                                        <img src="{{ asset($guarantor->image) }}" alt="Guarantor Image" width="100">
                                    @endif
                                </td>
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
                                        <a href="{{ route('guarantors.show', $guarantor->id) }}"
                                            class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('guarantors.edit', $guarantor->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('guarantors.destroy', $guarantor->id) }}" method="POST"
                                            class="d-inline"
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
                                    <a href="{{ route('guarantors.create') }}" class="btn btn-primary btn-sm">Add First
                                        Guarantor</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="wrapper">
                    @if ($guarantors->hasPages())
                        <ul id="pagination">
                            @if ($guarantors->onFirstPage())
                                <li><span class="disabled">«</span></li>
                            @else
                                <li><a href="{{ $guarantors->previousPageUrl() }}" rel="prev">«</a></li>
                            @endif

                            @foreach ($guarantors->links()->elements[0] as $page => $url)
                                @if ($page == $guarantors->currentPage())
                                    <li><a class="active" href="#">{{ $page }}</a></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if ($guarantors->hasMorePages())
                                <li><a href="{{ $guarantors->nextPageUrl() }}" rel="next">»</a></li>
                            @else
                                <li><span class="disabled">»</span></li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    #wrapper {
        margin: 0 auto;
        display: block;
        width: 960px;
    }

    .page-header {
        text-align: center;
        font-size: 1.5em;
        font-weight: normal;
        border-bottom: 1px solid #ddd;
        margin: 30px 0
    }

    #pagination {
        margin: 0;
        padding: 0;
        text-align: center
    }

    #pagination li {
        display: inline
    }

    #pagination li a {
        display: inline-block;
        text-decoration: none;
        padding: 5px 10px;
        color: #000
    }

    /* Active and Hoverable Pagination */
    #pagination li a {
        border-radius: 5px;
        -webkit-transition: background-color 0.3s;
        transition: background-color 0.3s
    }

    #pagination li a.active {
        background-color: #4caf50;
        color: #fff
    }

    #pagination li a:hover:not(.active) {
        background-color: #ddd;
    }

    /* border-pagination */
    .b-pagination-outer {
        width: 100%;
        margin: 0 auto;
        text-align: center;
        overflow: hidden;
        display: flex
    }

    #border-pagination {
        margin: 0 auto;
        padding: 0;
        text-align: center
    }

    #border-pagination li {
        display: inline;

    }

    #border-pagination li a {
        display: block;
        text-decoration: none;
        color: #000;
        padding: 5px 10px;
        border: 1px solid #ddd;
        float: left;

    }

    #border-pagination li a {
        -webkit-transition: background-color 0.4s;
        transition: background-color 0.4s
    }

    #border-pagination li a.active {
        background-color: #4caf50;
        color: #fff;
    }

    #border-pagination li a:hover:not(.active) {
        background: #ddd;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            paging: false,
            info: false,
            ordering: true,
            searching: true,
            responsive: true
        });
    });
</script>
