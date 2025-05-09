@extends('layouts.master')
@push('styles')
    <style>

    </style>
@endpush
@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Settings', 'url' => route('admin.settings'), 'active' => false]
        ];
        $pageTitle = 'General Settings';
    @endphp
    @include('backend.partials.breadcrumb', ['pageTitle' => $pageTitle, 'breadcrumbs' => $breadcrumbs])
    {{-- <section class="list-group-navigation">
            <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                <div class="col-lg-12">
                    <div class="card">
                        <!-- <div class="card-header">
                            <h4 class="card-title">List group navigation</h4>
                        </div> -->
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-4">
                                        <div class="list-group" role="tablist">
                                            <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-selected="false" tabindex="-1">General Settings</a>
                                            <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-selected="false" tabindex="-1">Socails Settings</a>
                                            <!-- <a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-selected="false" tabindex="-1">Profile</a> -->
                                            <!-- <a class="list-group-item list-group-item-action " id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-selected="true">Other</a> -->
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-8 mt-1">
                                        <div class="tab-content text-justify" id="nav-tabContent">

                                            <div class="tab-pane active show" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                                                <form class="form form-vertical" action="{{ route('store.settings') }}" method="POST" enctype='multipart/form-data' >
                                                    @csrf
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="project-name">Project Name</label>
                                                                    <input
                                                                        type="text"
                                                                        id="project-name"
                                                                        class="form-control"
                                                                        name="settings[project_name]"
                                                                        placeholder="Add project name"
                                                                        value="{{ old('settings.project_name', $settings['project_name'] ?? '') }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="project-tagline">Project TagLine</label>
                                                                    <input
                                                                        type="text"
                                                                        id="project-tagline"
                                                                        class="form-control"
                                                                        name="settings[project_tagline]"
                                                                        placeholder="Add project tagline"
                                                                        value="{{ old('settings.project_tagline', $settings['project_tagline'] ?? '') }}">
                                                                </div>
                                                            </div>


                                                            <div class="col-12 d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="tab-pane" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                                                <form class="form form-vertical" action="{{ route('store.settings') }}" method="POST" enctype='multipart/form-data' >
                                                    @csrf
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="facebook">Facebook</label>
                                                                    <input
                                                                        type="url"
                                                                        id="facebook"
                                                                        class="form-control"
                                                                        name="settings[facebook]"
                                                                        placeholder="Add facebook url"
                                                                        value="{{ old('settings.facebook', $settings['facebook'] ?? '') }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="Instagram">Instagram</label>
                                                                    <input
                                                                        type="url"
                                                                        id="Instagram"
                                                                        class="form-control"
                                                                        name="settings[instagram]"
                                                                        placeholder="Add instagram url"
                                                                        value="{{ old('settings.instagram', $settings['instagram'] ?? '') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="whatsapp">WhatsApp</label>
                                                                    <input
                                                                        type="text"
                                                                        id="whatsapp"
                                                                        class="form-control"
                                                                        name="settings[whatsapp]"
                                                                        placeholder="Add whatsapp number"
                                                                        value="{{ old('settings.whatsapp', $settings['whatsapp'] ?? '') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="youtube">Youtube</label>
                                                                    <input
                                                                        type="url"
                                                                        id="youtube"
                                                                        class="form-control"
                                                                        name="settings[youtube]"
                                                                        placeholder="Add youtube tagline"
                                                                        value="{{ old('settings.youtube', $settings['youtube'] ?? '') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="tiktok">TikTok</label>
                                                                    <input
                                                                        type="url"
                                                                        id="tiktok"
                                                                        class="form-control"
                                                                        name="settings[tiktok]"
                                                                        placeholder="Add tiktok url"
                                                                        value="{{ old('settings.tiktok', $settings['tiktok'] ?? '') }}">
                                                                </div>
                                                            </div>


                                                            <div class="col-12 d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- <div class="tab-pane" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">Ut ut
                                                do pariatur aliquip aliqua aliquip exercitation do nostrud commodo
                                                reprehenderit
                                                aute ipsum
                                                voluptate.
                                                Irure Lorem et laboris nostrud amet cupidatat cupidatat anim do ut velit
                                                mollit
                                                consequat enim
                                                tempor.
                                                Consectetur est minim nostrud nostrud consectetur irure labore voluptate
                                                irure.
                                                Ipsum id Lorem sit
                                                sint voluptate est pariatur eu ad cupidatat et deserunt culpa sit eiusmod
                                                deserunt. Consectetur et
                                                fugiat anim do eiusmod aliquip nulla laborum elit adipisicing pariatur
                                                cillum.
                                            </div> -->

                                            <!-- <div class="tab-pane" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">Irure
                                                enim occaecat labore sit qui aliquip reprehenderit amet velit. Deserunt
                                                ullamco
                                                ex elit nostrud ut
                                                dolore nisi officia magna sit occaecat laboris sunt dolor. Nisi eu minim
                                                cillum
                                                occaecat aute est
                                                cupidatat aliqua labore aute occaecat ea aliquip sunt amet. Aute mollit
                                                dolor ut
                                                exercitation irure
                                                commodo non amet consectetur quis amet culpa. Quis ullamco nisi amet qui
                                                aute
                                                irure eu. Magna labore
                                                dolor quis ex labore id nostrud deserunt dolor eiusmod eu pariatur culpa
                                                mollit
                                                in irure
                                            </div> -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section> --}}
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> General Settings</a></li>
                        <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Socails Settings</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <form action="{{ route('store.settings') }}" method="POST" enctype='multipart/form-data' class="form-horizontal">
                                    @csrf
                                    <div class="form-group"><label class="col-sm-2 control-label">Project Name</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="settings[project_name]" placeholder="Add project name"
                                                value="{{ old('settings.project_name', $settings['project_name'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Project TagLine</label>

                                        <div class="col-sm-10">
                                            <input type="text" id="project-tagline"
                                                class="form-control"
                                                name="settings[project_tagline]"
                                                placeholder="Add project tagline"
                                                value="{{ old('settings.project_tagline', $settings['project_tagline'] ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-white" type="submit">Cancel</button>
                                            <button class="btn btn-primary" type="submit">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <form action="{{ route('store.settings') }}" method="POST" enctype='multipart/form-data' class="form-horizontal">
                                    @csrf
                                    <div class="form-group"><label class="col-sm-2 control-label">Facebook</label>

                                        <div class="col-sm-10">
                                            <input type="url"  id="facebook"  class="form-control" placeholder="Add facebook url"
                                                value="{{ old('settings.facebook', $settings['facebook'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Instagram</label>

                                        <div class="col-sm-10">
                                            <input type="url" id="Instagram"  class="form-control" name="settings[instagram]" placeholder="Add instagram url"
                                                value="{{ old('settings.instagram', $settings['instagram'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">WhatsApp</label>

                                        <div class="col-sm-10">
                                            <input type="text" id="whatsapp"  class="form-control"   name="settings[whatsapp]" placeholder="Add whatsapp number"
                                                value="{{ old('settings.whatsapp', $settings['whatsapp'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Youtube</label>

                                        <div class="col-sm-10">
                                            <input type="url"  id="youtube" class="form-control" name="settings[youtube]" placeholder="Add youtube tagline"
                                                value="{{ old('settings.youtube', $settings['youtube'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">TikTok</label>

                                        <div class="col-sm-10">
                                            <input  type="url"  id="tiktok" class="form-control" name="settings[tiktok]" placeholder="Add tiktok url"
                                                value="{{ old('settings.tiktok', $settings['tiktok'] ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-white" type="submit">Cancel</button>
                                            <button class="btn btn-primary" type="submit">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>

    </script>
@endpush
