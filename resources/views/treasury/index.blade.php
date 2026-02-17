@extends('layouts.app')

@section('content')
    <style>
        .select2-results__option {
            font-size: 9pt; /* atau 8pt */
        }

        .table-active {
            --bs-table-bg-state: #cff4fc;
        }

    </style>
    <div id="url-api"
        data-api_list_treasure="{{ $list_treasuries_api }}"
        data-api_create_treasury="{{ $api_create_treasury }}"
        data-api_list_cash="{{ $api_list_cash }}"
        data-api_duplicate_treasury="{{ $api_duplicate_treasury }}"
    ></div>
    <div class="my-5">
        <h4 class="fw-bold mb-3">Cash Trends</h4>
        <div class="card card-custom p-4">
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
                            <select class="form-select form-select-sm" id="input-treasury-month" aria-label="Small select example">
                                @foreach ($months as $index => $value)
                                    <option @if ($index == 0) {{ 'disabled' }} @endif
                                        value="{{ $index }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="input-treasury-year" class="form-label fs-6">Year</label>
                            <select class="form-select form-select-sm" id="input-treasury-year" aria-label="Small select example">
                                <option disabled>Select Year</option>

                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mt-5 px-5">
                            <div class="position-relative my-3 text-center">
                                <hr>
                                <p class="position-absolute top-50 start-50 translate-middle bg-white fw-semibold">DUPPLICATE ALL RECORDS FROM CURRENT TREASURY?</p>
                            </div>
                            <div class="mb-3">
                                <label for="input-current-treasury" class="form-label fs-6">Select Treasury</label>
                                <select class="form-select form-select-sm" id="input-current-treasury" style="width: 100%;">
                                    <option value="">Choose Here</option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" form="form-add-treasury" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-add-treasury" id="btn-save-treasury" class="btn btn-dark">Save</button>
                    <button type="button" form="form-add-treasury" id="btn-save-updated-treasury" style="display: none;"
                        class="btn btn-dark">Update</button>
                </div>
            </div>
        </div>
    </div>


    {{-- MODAL DUPPLICATE TREASURY --}}
    <div class="modal fade" id="modalDupplicateTreasury" tabindex="-1" aria-labelledby="modalDupplicateTreasuryLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalDupplicateTreasuryLabel">Cash Records of <span id="selected-treasury"></span></h1>
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
@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/pages/treasuries/chart-treasury.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/index.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/search.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/mainTable.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/delete.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/pagination.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/treasury-add.js') }}"></script>
@endsection
