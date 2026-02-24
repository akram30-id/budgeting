@extends('layouts.app')

@section('content')
    <style>
        th {
            color: #4f4f4f !important;
        }

        .table-wrapper {
            border-radius: 20px;
            overflow: hidden;
            /* WAJIB */
        }

        .table {
            --bs-table-hover-bg: rgba(25, 0, 255, 0.02);
        }
    </style>

    <div id="url-settings" data-api_update_password="{{ $api_update_password }}"></div>

    <div class="container">
        <div class="row justify-content-center align-items-center mt-5">
            <div class="col-sm-6">
                <div class="card text-center p-3">
                    <div class="card-body">
                        <h4>Ganti Password</h4>
                        <small class="text-secondary">Jangan sampe ada yang ngintip ya</small>

                        <form id="form-change-password">
                            <div class="mb-3 mt-5 row align-items-center fs-6">
                                <div class="col-sm-3">
                                    <label for="">Password Baru</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="password" id="inputNewPassword" class="form-control form-control-sm"
                                            placeholder="********" aria-label="********"
                                            aria-describedby="btn-new-password-visible">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="btn-new-password-visible">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start">
                                <div id="newpwd-invalid-feedback">
                                </div>
                            </div>

                            <div class="mb-3 mt-3 row align-items-center fs-6">
                                <div class="col-sm-3">
                                    <label for="">Konfirmasi Password Baru</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="password" id="inputConfirmPassword"
                                            class="form-control form-control-sm" placeholder="********"
                                            aria-label="********" aria-describedby="btn-confirm-password-visible">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="btn-confirm-password-visible">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start">
                                <div id="cnfpwd-invalid-feedback">
                                </div>
                            </div>

                            <div class="mb-3 mt-3 d-flex justify-content-end align-items-center fs-6">
                                <button type="reset" class="btn btn-sm btn-outline-dark"
                                    style="margin-right: 10px;">Clear</button>
                                <button type="submit" class="btn btn-sm btn-dark">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        {{-- CHANGE PASSWORD --}}


        {{-- MODAL INPUT OLD PASSWORD --}}
        <div class="modal fade" id="modalOldPassword" tabindex="-1" aria-labelledby="modalOldPasswordLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalOldPasswordLabel">Konfirmasi Dulu ya</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Clos e"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 my-2">
                            <label for="inputOldPassword" class="form-label">Password Lama</label>
                            <input type="password" id="inputOldPassword" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="btn-update-password" class="btn btn-dark">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- END OF MODAL INPUT OLD PASSWORD --}}

    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('js/pages/debts/index.js') }}"></script> --}}

     <script type="module" src="{{ asset('js/pages/settings/index.js') }}"></script>
@endsection
