<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finance Hub Dashboard')</title>

    {{-- Bootstrap & Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CHEWY FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href={{ asset('css/style.css') }}>
</head>

<body>
    <div id="url" data-api_logout="{{ config('services.app_url') . '/api/logout' }}"
        data-url_logout="{{ config('services.app_url') . '/logout' }}"></div>

    <div id="token" data-access_token="{{ session('access_token') }}"></div>

    <div id="module" data-module_name="{{ $module ?? '' }}"></div>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main Content --}}
            <main class="col-md-9 col-lg-10 p-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold">{{ $pageTitle }}</h3>

                    @yield('optional-navbar')
                </div>

                <hr>

                @yield('content')

                {{-- Include scripts --}}
                @include('layouts.notification')
            </main>
        </div>
    </div>

    {{-- Include scripts --}}
    @include('layouts.scripts')
</body>

@yield('modals')

</html>
