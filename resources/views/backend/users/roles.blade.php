@extends('layouts.master')
@push('styles')
    <style>

    </style>
@endpush
@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Users', 'url' => route('admin.users'), 'active' => false],
            ['title' => 'Roles', 'url' => route('admin.roles'), 'active' => true],
        ];
        $buttons = [
            ['title' => 'Add', 'modal' => '#addRole', 'class' => 'btn-primary', 'icon' => 'bi bi-plus-circle']
        ];
        $pageTitle = 'Roles';

    @endphp
    @include('backend.partials.breadcrumb', ['pageTitle' => $pageTitle, 'breadcrumbs' => $breadcrumbs, 'buttons' => $buttons ])

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table id="table1" class="table table-striped table-bordered table-hover dataTables-example" >
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Created At</th>
                                        <th>Action</th>
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

    {{-- <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Role</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="name">Role: </label>
                        <div class="form-group">
                            <input id="name" type="text" name="name" placeholder="add role"
                                class="form-control" required>
                        </div>

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
                            <span class="d-none d-sm-block">Add</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="modal inmodal" id="addRole" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Add Role</h4>
                </div>
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="name">Role: </label>
                        <div class="form-group">
                            <input id="name" type="text" name="name" placeholder="Add role" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- <div class="modal fade text-left" id="editRoleModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Role</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ route('role.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">
                    <div class="modal-body">
                        <label for="name">Role: </label>
                        <div class="form-group">
                            <input id="role_name" type="text"  name="name" class="form-control" required>
                        </div>
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

    <div class="modal inmodal" id="editRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Role</h4>
                </div>
                <form action="{{ route('role.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">
                    <div class="modal-body">
                        <label for="role_name">Role: </label>
                        <div class="form-group">
                            <input id="role_name" type="text" name="name" class="form-control" required>
                        </div>
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
            ajax: "{{ route('admin.roles-list') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        //
        $('#editRoleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var roleId = button.data('role-id');
            var roleName = button.data('role-name');
            var modal = $(this);
            modal.find('#role_id').val(roleId);
            modal.find('#role_name').val(roleName);
        });
    });
</script>
@endpush

