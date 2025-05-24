@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Customer List</h1>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($customers->count())
            <table id="customers-table" class="table table-bordered table-responsive">

                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Account No</th>
                        <th>Name</th>
                        <th>Mobile 1</th>
                        <th>NIC</th>
                        <th>Gender</th>
                        <th>Total Price</th>
                        <th>Installment</th>
                        <th>Advance</th>
                        <th>Balance</th>
                        <th>Defaulter</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>
                                @if ($customer->image)
                                    <img src="{{ asset('backend/img/customers/' . $customer->image) }}" alt="Customer Image"
                                        width="60" height="60" style="object-fit: cover;border-radius:10px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $customer->account_no }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->mobile_1 }}</td>
                            <td>{{ $customer->nic }}</td>
                            <td>{{ ucfirst($customer->gender) }}</td>
                            <td>{{ $customer->total_price }}</td>
                            <td>{{ $customer->installment_amount }}</td>
                            <td>{{ $customer->advance }}</td>
                            <td>{{ $customer->balance }}</td>
                            <td>{{ $customer->is_defaulter ? 'Yes' : 'No' }}</td>
                            <td>
                                <a href="{{ route('customers.statement', $customer->id) }}"
                                    class="btn btn-sm btn-info">Statement</a>
                                <a href="{{ route('customers.edit', $customer->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure?')"
                                        class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No customers found.</p>
        @endif
        <div id="wrapper">
            @if ($customers->hasPages())
                <ul id="pagination">
                    @if ($customers->onFirstPage())
                        <li><span class="disabled">«</span></li>
                    @else
                        <li><a href="{{ $customers->previousPageUrl() }}" rel="prev">«</a></li>
                    @endif

                    @foreach ($customers->links()->elements[0] as $page => $url)
                        @if ($page == $customers->currentPage())
                            <li><a class="active" href="#">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    @if ($customers->hasMorePages())
                        <li><a href="{{ $customers->nextPageUrl() }}" rel="next">»</a></li>
                    @else
                        <li><span class="disabled">»</span></li>
                    @endif
                </ul>
            @endif
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
