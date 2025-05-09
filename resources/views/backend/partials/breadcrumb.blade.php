<!-- resources/views/backend/partials/breadcrumb.blade.php -->

{{--<div class="page-title">
    <div class="row align-items-center">
        <!-- Page Title and Breadcrumb -->
        <div class="col-12 col-md-6">
            <h5>{{ $pageTitle }}</h5>
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <!-- Dashboard Breadcrumb Link -->
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>

                    <!-- Loop through provided breadcrumbs -->
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item @if($breadcrumb['active']) active @endif"
                            aria-current="{{ $breadcrumb['active'] ? 'page' : '' }}">
                            @if($breadcrumb['active'])
                                {{ $breadcrumb['title'] }}
                            @else
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>

        <!-- Action Buttons -->
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            @if (!empty($buttons))
                @foreach ($buttons as $button)
                    @if (!empty($button['modal']))
                        <!-- Modal Trigger Button -->
                        <button type="button"
                                class="btn {{ $button['class'] ?? 'btn-primary' }} me-2"
                                data-bs-toggle="modal"
                                data-bs-target="{{ $button['modal'] }}">
                            @if (!empty($button['icon']))
                                <i class="{{ $button['icon'] }}"></i>
                            @endif
                            {{ $button['title'] }}
                        </button>
                    @else
                        <!-- Regular Button -->
                        <a href="{{ $button['url'] }}" class="btn {{ $button['class'] ?? 'btn-primary' }} me-2">
                            @if (!empty($button['icon']))
                                <i class="{{ $button['icon'] }}"></i>
                            @endif
                            {{ $button['title'] }}
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div> --}}
<div class="row wrapper border-bottom white-bg page-heading" style="display: flex; align-items: center;">
    <div class="col-lg-6">
        <h2>{{ $pageTitle }}</h2>
        <ol class="breadcrumb">
            <!-- Dashboard Breadcrumb Link -->
            <li>
                <a href="{{ route('dashboard') }}">Home</a>
            </li>

            <!-- Loop through provided breadcrumbs -->
            @foreach($breadcrumbs as $breadcrumb)
                <li class="@if($breadcrumb['active']) active @endif">
                    @if($breadcrumb['active'])
                        <strong>{{ $breadcrumb['title'] }}</strong>
                    @else
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
    <div class="col-lg-6 text-right">
        <!-- Action Buttons -->
        @if (!empty($buttons))
            @foreach ($buttons as $button)
                @if (!empty($button['modal']))
                    <!-- Modal Trigger Button -->
                    <button type="button"
                            class="btn {{ $button['class'] ?? 'btn-primary' }} me-2"
                            data-toggle="modal"
                            data-target="{{ $button['modal'] }}">
                        @if (!empty($button['icon']))
                            <i class="{{ $button['icon'] }}"></i>
                        @endif
                        {{ $button['title'] }}
                    </button>
                @else
                    <!-- Regular Button -->
                    <a href="{{ $button['url'] }}" class="btn {{ $button['class'] ?? 'btn-primary' }} me-2">
                        @if (!empty($button['icon']))
                            <i class="{{ $button['icon'] }}"></i>
                        @endif
                        {{ $button['title'] }}
                    </a>
                @endif
            @endforeach
        @endif
    </div>
</div>




