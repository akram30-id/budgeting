<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finance Hub - {{ $pageTitle }}</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> --}}

    <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>

    <link rel="stylesheet" href={{ asset('css/style.css') }}>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>

    <!-- LOGIN CARD -->

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-sm-4 col-md-6 col-lg-4">
                <img class="img-fluid mx-auto d-block" src="{{ asset('assets/img/login_bubududu.gif') }}"
                    style="height: 128px;" alt="">
                <p class="chewy-regular text-center">you can't keep buying snacks, bubu...</p>
                <div id="alert-container"></div>
                <div class="card p-3 fs-6">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3>Sign In</h3>
                            <small>We need to know who you are</small>
                        </div>

                        <form id="form-sign-in">

                            <input type="hidden" id="url" data-api_login="{{ $api_login }}"
                                data-save_token="{{ $save_token_url }}">

                            <div class="mb-3">
                                <label for="">Email</label>
                                <input type="email" id="inputEmail" class="form-control form-control-sm"
                                    placeholder="example@mail.com">
                            </div>

                            <div class="mb-3">
                                <label for="">Password</label>
                                <div class="input-group mb-3">
                                    <input type="password" id="inputPassword" class="form-control form-control-sm" placeholder="********"
                                        aria-label="********" aria-describedby="btn-password-visible">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-password-visible">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                                    <label class="form-check-label fs-6" for="checkDefault">
                                        Remember Me
                                    </label>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="d-flex justify-content-end align-items-end">
                                    <button type="submit" id="btn-sign-in" class="btn btn-sm btn-outline-dark">Sign
                                        In</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-center align-items-center">
                    <small>Don't have an account? <a href="/register" class="ms-1">Sign Up</a></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="module" src="{{ asset('js/login.js') }}"></script>
</body>

</html>
