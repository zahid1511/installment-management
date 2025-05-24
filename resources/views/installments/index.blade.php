<!-- resources/views/installments/index.blade.php -->
@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Installments</h1>

        <a href="{{ route('installments.create') }}" class="btn btn-primary mb-3">Add New Installment</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Receipt No</th>
                    <th>Installment Amount</th>
                    <th>Balance</th>
                    <th>Recovery Officer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($installments as $installment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $installment->customer->name }}</td>
                        <td>{{ $installment->receipt_no }}</td>
                        <td>{{ $installment->installment_amount }}</td>
                        <td>{{ $installment->balance }}</td>
                        <td>{{ $installment->recovery_officer }}</td>
                        <td>
                            <a href="{{ route('installments.edit', $installment->id) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('installments.destroy', $installment->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="wrapper">
            @if ($installments->hasPages())
                <ul id="pagination">
                    @if ($installments->onFirstPage())
                        <li><span class="disabled">«</span></li>
                    @else
                        <li><a href="{{ $installments->previousPageUrl() }}" rel="prev">«</a></li>
                    @endif

                    @foreach ($installments->links()->elements[0] as $page => $url)
                        @if ($page == $installments->currentPage())
                            <li><a class="active" href="#">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    @if ($installments->hasMorePages())
                        <li><a href="{{ $installments->nextPageUrl() }}" rel="next">»</a></li>
                    @else
                        <li><span class="disabled">»</span></li>
                    @endif
                </ul>
            @endif
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
