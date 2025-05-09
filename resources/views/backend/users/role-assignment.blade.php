@extends('layouts.master')
@push('styles')
    <style>

    </style>
@endpush
@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Roles', 'url' => route('admin.roles'), 'active' => false],
            ['title' => 'Role Assignment', 'active' => true],
        ];

        $pageTitle = 'Role Assignment';

    @endphp
    @include('backend.partials.breadcrumb', ['pageTitle' => $pageTitle, 'breadcrumbs' => $breadcrumbs ])

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table id="table1" class="table table-striped table-bordered table-hover dataTables-example" >
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Assign Role</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade text-left" id="assignRole" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Assign/Update User Role</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ route('assign-role') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-body">
                        <fieldset class="form-group">
                            <label for="basicSelect">Select Role</label>
                            <select class="form-select" id="basicSelect" name="role">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary"
                            data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ms-1"
                            data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="modal inmodal" id="assignRole" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Assign/Update User Role</h4>
                </div>
                <form action="{{ route('assign-role') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-body">
                        <fieldset class="form-group">
                            <label for="basicSelect">Select Role</label>
                            <select class="form-control" id="basicSelect" name="role" required>
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('script')
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#table1')) {
            $('#table1').DataTable().destroy();
        }
        $('#table1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user-role') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'assign_role', name: 'assign_role', orderable: false, searchable: false }
            ]
        });

        //
        $('#assignRole').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            var roleName = button.data('role-name');
            var modal = $(this);
            modal.find('#user_id').val(userId);
            modal.find('#role_name').val(roleName);
        });

    });
</script>
@endpush

