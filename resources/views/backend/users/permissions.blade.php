@extends('layouts.master')
@push('styles')
    <style>

    </style>
@endpush
@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Permissions', 'url' => route('permissions'), 'active' => false]
        ];
        $pageTitle = 'Permissions';
        $buttons = [
            ['title' => 'Add Permission', 'modal' => '#addPermission', 'class' => 'btn-primary', 'icon' => 'bi bi-plus-circle']
        ];
    @endphp
    @include('backend.partials.breadcrumb', ['pageTitle' => $pageTitle, 'breadcrumbs' => $breadcrumbs, 'buttons' => $buttons ])
    {{-- <section class="section">
        <div class="card">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            <form action="{{ route('permissions.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @foreach($permissions as $permission)
                                    <div class="form-check">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            id="permission-{{ $role->id }}-{{ $permission->id }}"
                                            name="permissions[]"
                                            value="{{ $permission->id }}"
                                            {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                                        >
                                        <label
                                            class="form-check-label"
                                            for="permission-{{ $role->id }}-{{ $permission->id }}"
                                        >
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary mt-2">Update Permissions</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

    </section> --}}

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table id="table1" class="table table-striped table-bordered table-hover dataTables-example" >
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                <form action="{{ route('permissions.update', $role->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    @foreach($permissions as $permission)
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input"
                                                                id="permission-{{ $role->id }}-{{ $permission->id }}"
                                                                name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                                                            >
                                                            <label
                                                                class="form-check-label"
                                                                for="permission-{{ $role->id }}-{{ $permission->id }}"
                                                            >
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                    <button type="submit" class="btn btn-primary mt-2">Update Permissions</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade text-left" id="addPermission" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Permission</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="name">Permission: </label>
                        <div class="form-group">
                            <input id="name" type="text" name="name" placeholder="add permission"
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

    <div class="modal inmodal" id="addPermission" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Permission</h4>
                </div>
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="name">Permission: </label>
                        <div class="form-group">
                            <input id="name" type="text" name="name" placeholder="Add permission" class="form-control" required>
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


@endsection

@push('script')

@endpush

