@extends('layouts.master')

@section('content')
    {{--<div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h5>Welcome {{ Auth::user()->name }}</h5>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Layout Vertical Navbar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>--}}
    @php
        $breadcrumbs = [
            ['title' => 'Users', 'url' => route('admin.users'), 'active' => false]
        ];
        $pageTitle = 'Users';
        $buttons = [
            ['title' => 'Add User', 'url' => route('admin.users-list'), 'class' => 'btn-primary', 'icon' => 'bi bi-plus-circle'],
            ['title' => 'Launch Modal', 'modal' => '#inlineForm', 'class' => 'btn-outline-success', 'icon' => 'bi bi-window']
        ];
    @endphp
    @include('backend.partials.breadcrumb', ['pageTitle' => $pageTitle, 'breadcrumbs' => $breadcrumbs, 'buttons' => $buttons ])
@endsection

@push('styles')
    <style>
        
    </style>
@endpush

@push('scripts')
    <script>
        
    </script>
@endpush
