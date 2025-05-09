@extends('layouts.master')
@push('styles')
    <style>

    </style>
@endpush
@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Users', 'url' => route('admin.users'), 'active' => false]
        ];
        $pageTitle = 'Users';
        $buttons = [
            ['title' => 'Add', 'modal' => '#addUser', 'class' => 'btn-primary', 'icon' => 'bi bi-plus-circle']
        ];
    @endphp
    @include('backend.partials.breadcrumb', ['pageTitle' => $pageTitle, 'breadcrumbs' => $breadcrumbs, 'buttons' => $buttons ])

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    {{-- <div class="ibox-title">
                        <h5>Basic Data Tables example with responsive plugin</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div> --}}
                    <div class="ibox-content">

                        <div class="table-responsive">
                            <table id="table1" class="table table-striped table-bordered table-hover dataTables-example" >
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Create At</th>
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
                    <h4 class="modal-title" id="myModalLabel33">Add User</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="name">Name: </label>
                        <div class="form-group">
                            <input id="name" type="text" name="name" placeholder="UserName"
                                class="form-control" required>
                        </div>
                        <label for="email">Email: </label>
                        <div class="form-group">
                            <input id="email" type="text" name="email" placeholder="Email Address"
                                class="form-control" required>
                        </div>

                        <fieldset class="form-group">
                            <label for="basicSelect">Select Role</label>
                            <select class="form-select" id="basicSelect" name="role">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <label for="password">Password: </label>
                        <div class="form-group">
                            <input id="password" type="password" name="password" placeholder="Password"
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

    <div class="modal inmodal" id="addUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add User</h4>
                </div>
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-body">
                        <label for="name">Name: </label>
                        <div class="form-group">
                            <input id="name" type="text" name="name" placeholder="UserName" class="form-control" required>
                        </div>
                        <label for="email">Email: </label>
                        <div class="form-group">
                            <input id="email" type="text" name="email" placeholder="Email Address" class="form-control" required>
                        </div>
                        <fieldset class="form-group">
                            <label for="basicSelect">Select Role</label>
                            <select class="form-control" id="basicSelect" name="role">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                        <label for="password">Password: </label>
                        <div class="form-group">
                            <input id="password" type="password" name="password" placeholder="Password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary ms-1">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- <div class="modal inmodal" id="editUserModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <i class="fa fa-clock-o modal-icon"></i>
                    <h4 class="modal-title">Modal title</h4>
                    <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</small>
                </div>
                <div class="modal-body">
                    <p><strong>Lorem Ipsum is simply dummy</strong> text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
                        printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Edit Modal Structure -->
    <div class="modal inmodal" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ route('user.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-body">
                        <label for="name">Name: </label>
                        <div class="form-group">
                            <input id="name" type="text" name="name" placeholder="UserName" class="form-control" required>
                        </div>
                        <label for="email">Email: </label>
                        <div class="form-group">
                            <input id="email" type="text" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ms-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save changes</span>
                        </button>
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
            ajax: "{{ route('users.list') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        //
        $('#editUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userEmail = button.data('u-email-id');
            var userName = button.data('user-name');
            var userId = button.data('user-id');
            var modal = $(this);
            modal.find('#user_id').val(userId);
            modal.find('#name').val(userName);
            modal.find('#email').val(userEmail);
        });
    });


</script>

<script>
    // $(document).ready(function(){
    //     $('.dataTables-example').DataTable({
    //         pageLength: 10,
    //         responsive: true,
    //         dom: '<"html5buttons"B>lTfgitp',
    //         buttons: [
    //             { extend: 'copy'},
    //             {extend: 'csv'},
    //             {extend: 'excel', title: 'ExampleFile'},
    //             {extend: 'pdf', title: 'ExampleFile'},

    //             {extend: 'print',
    //                 customize: function (win){
    //                     $(win.document.body).addClass('white-bg');
    //                     $(win.document.body).css('font-size', '10px');

    //                     $(win.document.body).find('table')
    //                             .addClass('compact')
    //                             .css('font-size', 'inherit');
    //                 }
    //             }
    //         ]

    //     });

    // });

</script>


@endpush

