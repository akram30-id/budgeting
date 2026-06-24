<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finance Hub Dashboard')</title>

    {{-- Bootstrap & Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- CHEWY FONT --}}
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet"> --}}

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href={{ asset('css/style.css') }}>
</head>

<body>
    <div id="url" data-api_logout="{{ config('services.app_url') . '/api/logout' }}"
        data-url_logout="{{ config('services.app_url') . '/logout' }}"
        data-url_go="{{ config('services.app_url_go') }}"
        data-base_url="{{ config('services.app_url') }}"></div>

    <div id="token" data-access_token="{{ session('access_token') }}"></div>

    <div id="user" data-id="{{ session('user_id') }}"></div>

    <div id="module" data-module_name="{{ $module ?? '' }}"></div>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main Content --}}
            <main class="col-md-9 col-lg-10 p-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="z-index: 2 !important;">
                    <h3 class="fw-bold">{{ $pageTitle }}</h3>

                    <div style="margin-right: 32px;" class="position-relative dropdown">
                        <div class="rounded-circle" style="padding: 4px 10px; background: rgba(0, 0, 0, 0.15)" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" id="btn-notification">
                            <i class="bi bi-bell-fill" style="font-size: 1.3rem;"></i>
                        </div>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem; padding: 3px;" id="total-notification">
                        </span>
                        <div class="dropdown-menu p-0" style="width: 400px; background-color: #ffffff;">
                            <div class="row px-3 mt-3 align-items-center">
                                <div class="col-12">
                                    <h4 class="mb-3 fw-bold">Notification</h4>
                                </div>
                            </div>
                            <div class="row px-3 mb-2 align-items-center">
                                <div class="col-3">
                                    <button class="btn btn-sm rounded-pill btn-outline-primary" style="padding-inline: 20px;">Semua</button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-sm rounded-pill btn-outline-secondary" style="border: 0px; padding-inline: 20px;">Belum Dibaca</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end px-3">
                                <a href="/notifications" class="text-decoration-none">Lihat Semua</a>
                            </div>
                            <div class="row justify-content-center p-0 notif-actions" style="z-index: 5 !important;">
                                <div class="col-12 list-notif rounded-2 mb-1" style="height: 128px; width: 380px;">
                                    <div class="row align-items-center">
                                        <div class="col-2 mt-3">
                                            <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                                        </div>
                                        <div class="col-10 placeholder-glow">
                                            <span class="placeholder">Akram Ganzzz mengundang Anda ke Treasury #TRETEST000001</span>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-10 placeholder-glow">
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-primary fs-6 disabled placeholder col-3" style="margin-right: 10px;" aria-disabled="true"></button>
                                                <button class="btn btn-sm btn-danger fs-6 placeholder col-3 disabled" aria-disabled="true"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @yield('optional-navbar')
                </div>

                <hr>

                @yield('content')

                {{-- Include scripts --}}
                @include('layouts.notification')
            </main>
        </div>
    </div>

    {{-- TOASTS --}}
    <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1100">
        <div id="toastPlacement" class="toast-container">
        </div>
    </div>
    {{-- END OF TOASTS --}}

    {{-- Include scripts --}}
    @include('layouts.scripts')
</body>

@yield('modals')

</html>
