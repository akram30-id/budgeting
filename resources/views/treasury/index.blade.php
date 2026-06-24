@extends('layouts.app')

@section('content')
    <style>
        .select2-results__option {
            font-size: 9pt;
            /* atau 8pt */
        }

        .table-active {
            --bs-table-bg-state: #cff4fc;
        }

        .list-group-item {
            border: none;
        }

        .list-group-item:hover {
            border: 0.3px solid #00000002;
            background-color: #77777838;
            border-radius: 10px;
        }

        .btn-list-item {
            font-size: 7pt !important;
            padding: 2px !important;
        }

        /* Pastikan container suggestion punya background putih solid */
        #suggestion-list {
            background-color: #ffffff !important;
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); /* Biar lebih "pop out" */
        }

        /* Mengatur baris saat di-hover agar tidak transparan */
        .item-user-row {
            background-color: #ffffff !important;
            opacity: 1 !important;
            transition: background-color 0.2s ease;
        }

        .item-user-row:hover {
            background-color: #f1f3f5 !important; /* Warna abu-abu solid saat hover */
            opacity: 1 !important;
        }

        /* Opsional: Jika teks ikut memudar, paksa tetap hitam/gelap */
        .item-user-row:hover .fw-bold,
        .item-user-row:hover .text-muted {
            opacity: 1 !important;
        }

        /* Menargetkan modal-content agar lebih tinggi */
        #modalTreasuryMember .modal-content {
            height: 50vh; /* Memaksa tinggi tertentu */
            max-height: 75vh;
        }
    </style>
    <div id="url-api"
        data-api_list_treasure="{{ $list_treasuries_api }}"
        data-api_create_treasury="{{ $api_create_treasury }}"
        data-api_list_cash="{{ $api_list_cash }}"
        data-api_duplicate_treasury="{{ $api_duplicate_treasury }}"
        data-api_show_list_members="{{ $api_show_list_members }}"
        data-api_find_users="{{ $api_find_users }}"
    ></div>
    <div class="my-5">
        <h4 class="fw-bold mb-3">Cash Trends</h4>
        <div class="card card-custom p-4" style="z-index: -1;">
            <div class="row jsutify-content-between">
                <div class="col-sm-2">
                    <h3 class="fw-bold">$150,000</h3>
                    <p class="text-success fw-medium mb-0">+12% <span class="text-muted">Last 12 Months</span></p>
                </div>
                <div class="col-sm-12">
                    <canvas id="treasuryChart" style="max-height: 180px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5" id="cash-records">
        <div class="col-sm-12 mt-5">
            <h4 class="fw-bold mb-3">My Treasuries <sup id="load-modal-cash"><i class="bi bi-plus-circle"></i></sup></h4>
            <div class="row justify-content-between">
                <div class="col-lg-1 col-sm-2 col-3">
                    <select class="form-select" id="select-length-treasury" aria-label="Default select example">
                        <option selected value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-lg-4 col-sm-8 col-9">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" id="input-search"
                            placeholder="search here . . ." aria-label="search here . . ." aria-describedby="button-addon2">
                        <button class="btn btn-dark" type="button" id="btn-search">Search</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered fs-6">
                    <thead class="table-dark text-center">
                        <th>Treasury No.</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Total Records</th>
                        <th>Members</th>
                        <th>Owner</th>
                        <th>Created At</th>
                        <th>###</th>
                    </thead>
                    <tbody id="tbody-treasury-cash">
                        <tr class="text-center">
                            <td colspan="8">Loading . . .</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav class="d-flex justify-content-end" aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item disabled" id="btn-previous-treasury">
                <a class="page-link text-dark" href="#cash-records" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item" id="btn-next-treasury">
                <a class="page-link text-dark" href="#cash-records" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('modals')
    {{-- MODAL DELETE --}}
    <!-- Modal -->
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDeleteLabel">Delete Treasury</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5">
                        <div class="col-sm-12">
                            <h5>Are you sure you want to delete this treasury?</h5>
                            <h5 class="text-danger">Treasury No: <span id="treasury-no-delete"></span></h5>
                        </div>
                    </div>

                    <div class="row justify-content-end px-3">
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger" id="btn-delete-treasury">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="treasuryModal" tabindex="-1" aria-labelledby="treasuryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="treasuryModalLabel">Add New Treasury</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- loader container -->
                    <div id="loader-treasury" style="display:none; text-align:center; margin-top:10px;">
                        <img src="{{ asset('assets/img/loading.gif') }}" alt="Loading..." width="32">
                    </div>

                    <form action="#" id="form-add-treasury">
                        <div class="mb-3">
                            <label for="input-treasury-month" class="form-label fs-6">Month</label>
                            <select class="form-select form-select-sm" id="input-treasury-month"
                                aria-label="Small select example">
                                @foreach ($months as $index => $value)
                                    <option @if ($index == 0) {{ 'disabled' }} @endif
                                        value="{{ $index }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="input-treasury-year" class="form-label fs-6">Year</label>
                            <select class="form-select form-select-sm" id="input-treasury-year"
                                aria-label="Small select example">
                                <option disabled>Select Year</option>

                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mt-5 px-5">
                            <div class="position-relative my-3 text-center">
                                <hr style="z-index: -1;">
                                <p class="position-absolute top-50 start-50 translate-middle bg-white fw-semibold">
                                    DUPPLICATE ALL RECORDS FROM CURRENT TREASURY?</p>
                            </div>
                            <div class="mb-3">
                                <label for="input-current-treasury" class="form-label fs-6">Select Treasury</label>
                                <select class="form-select form-select-sm" id="input-current-treasury"
                                    style="width: 100%;">
                                    <option value="">Choose Here</option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" form="form-add-treasury" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-add-treasury" id="btn-save-treasury"
                        class="btn btn-dark">Save</button>
                    <button type="button" form="form-add-treasury" id="btn-save-updated-treasury"
                        style="display: none;" class="btn btn-dark">Update</button>
                </div>
            </div>
        </div>
    </div>


    {{-- MODAL DUPPLICATE TREASURY --}}
    <div class="modal fade" id="modalDupplicateTreasury" tabindex="-1" aria-labelledby="modalDupplicateTreasuryLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDupplicateTreasuryLabel">Cash Records of <span
                            id="selected-treasury"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span class="fw-semibold">Click to select which record(s) do you wanna use.</span>
                    <p id="periode"></p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover fs-6">
                            <thead class="table-dark">
                                <th>#</th>
                                <th>Detail</th>
                                <th>Month</th>
                                <th>Income</th>
                                <th>Expense</th>
                                <th>Is Debt</th>
                            </thead>
                            <tbody id="tbody-cash-records">
                                <tr>
                                    <td colspan="9">Loading . . .</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-duplicate-cash">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END OF MODAL DUPPLICATE TREASURY --}}

    {{-- MODAL TREASURY MEMBERS --}}
    <div class="modal fade" id="modalTreasuryMember" tabindex="-1" aria-labelledby="modalTreasuryMemberLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTreasuryMemberLabel"><span id="members-of-treasury"></span>
                        Members</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        {{-- <input type="text" class="form-control form-control-sm" id="search-people"
                            placeholder="Cari email atau nama disini" aria-label="Cari email atau nama disini"
                            aria-describedby="btn-search"> --}}
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-sm" id="search-people"
                                        placeholder="Cari email atau nama disini" autocomplete="off">

                                <div id="suggestion-list" class="list-group position-absolute w-100 bg-white"
                                    style="z-index: 1000; display: none; max-height: 250px; overflow-y: auto;">
                                </div>
                            </div>
                    </div>
                    <div class="mb-3 px-3" id="member-list" style="color: #777778;">
                        {{-- <p class="fs-6 fst-italic">Gak ada siapa-siapa</p> --}}
                        <div class="list-group-item d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0">A second button item</p>
                            <button class="btn btn-sm btn-outline-danger btn-list-item"
                                style="font-size: 7pt !important; padding: 2px !important;">Delete</button>
                        </div>

                        <div
                            class="list-group-item list-group-item-action d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0">Item 2</p>
                            <button class="btn btn-sm btn-outline-danger btn-list-item">Delete</button>
                        </div>

                        <div
                            class="list-group-item list-group-item-action d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0">Item 3</p>
                            <button class="btn btn-sm btn-outline-danger btn-list-item">Delete</button>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END OF MODAL TREASURY MEMBERS --}}
@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/pages/treasuries/chart-treasury.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/index.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/search.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/mainTable.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/delete.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/pagination.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/treasury-add.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/members.js') }}"></script>
@endsection
