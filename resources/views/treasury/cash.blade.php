@extends('layouts.app')

@section('content')
    <div id="url-api" data-api_get_detail_treasury="{{ $apiGetDetailTreasury }}"
        data-api_update_checked="{{ $apiUpdateCheckedTreasuryDetail }}" data-api_create_cash="{{ $apiCreateCash }}"
        data-api_delete_cash="{{ $apiDeleteCash }}" data-api_detail_cash="{{ $apiDetailCash }}"></div>
    <div id="treasury-no" data-treasury_no="{{ $treasuryNo }}"></div>
    <div class="mb-4">
        <h3>Cash Detail</h3>
        <span class="badge text-bg-dark text-white">#{{ $treasuryNo }}</span>
    </div>
    <div class="row mt-5">
        <div class="col-sm-3">
            <i>
                <span class="fw-semibold" id="periode">Loading . . .</span>
            </i>
        </div>
        <div class="col-sm-12 mt-5">
            <div class="row justify-content-between align-items-center">
                <div class="col-sm-7">
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-start">
                            <!-- Button trigger modal -->
                            <button type="button" id="btn-add-cash" class="btn btn-sm btn-outline-dark"
                                data-bs-toggle="modal" data-bs-target="#addCashModal">
                                Add Cash
                            </button>

                            <button class="btn btn-sm btn-outline-danger" style="margin-left: 10px;">Export</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" id="search-cash"
                            placeholder="search here..." aria-label="search here..." aria-describedby="btn-search-cash">
                        <button class="btn btn-dark" type="button" id="btn-search-cash">Search</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped-columns table-bordered table-hover fs-6">
                    <thead class="table-dark">
                        <th>Detail No.</th>
                        <th>Detail</th>
                        <th>Month</th>
                        <th>Income</th>
                        <th>Expense</th>
                        <th>Check</th>
                        <th>Balance</th>
                        <th>Act Balance</th>
                        <th>###</th>
                    </thead>
                    <tbody id="tbody-treasury-detail">
                        <tr>
                            <td colspan="9">Loading . . .</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav class="d-flex justify-content-end" aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item disabled" id="btn-previous-cash">
                <a class="page-link text-dark" href="#cash-records" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item" id="btn-next-cash">
                <a class="page-link text-dark" href="#cash-records" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection


@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="addCashModal" tabindex="-1" aria-labelledby="addCashModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addCashModalLabel">Add New Cash</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- loader container -->
                    <div id="loader-cash" style="display:none; text-align:center; margin-top:10px;">
                        <img src="{{ asset('assets/img/loading.gif') }}" alt="Loading..." width="32">
                    </div>

                    <form action="#" id="form-add-cash">
                        <div class="mb-3">
                            <label for="input-cash-treasury-no" class="form-label fs-6">Treasury No</label>
                            <input type="text" disabled class="form-control form-control-sm" id="input-cash-treasury-no"
                                placeholder="{{ $treasuryNo }}">
                            <input type="hidden" disabled class="form-control form-control-sm"
                                id="input-treasury-detail-no">
                        </div>
                        <div class="mb-3">
                            <label for="input-cash-detail" class="form-label fs-6">Detail</label>
                            <input type="text" class="form-control form-control-sm" id="input-cash-detail"
                                placeholder="Bayar listrik">
                        </div>
                        <div class="mb-3">
                            <label for="input-income-amount" class="form-label fs-6">Income Amount</label>
                            <input type="text" class="form-control form-control-sm" id="input-income-amount"
                                placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="input-expense-amount" class="form-label fs-6">Expense Amount</label>
                            <input type="text" class="form-control form-control-sm" id="input-expense-amount"
                                placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="input-notes" class="form-label fs-6">Notes</label>
                            <textarea class="form-control form-control-sm" id="input-notes" rows="3"></textarea>
                        </div>
                        <div class="form-check mt-2 mb-3">
                            <label for="input-is-debt" class="form-label fs-6">This is a debt</label>
                            <input class="form-check-input" type="checkbox" value="" id="input-is-debt">
                        </div>

                        <div class="row justify-content-center" id="form-debt-detail">
                            <div class="col-sm-10">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="input-creditor" class="form-label fs-6">Creditor</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="input-creditor" placeholder="Kredit Pintar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" form="form-add-cash" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-add-cash" id="btn-save-cash" class="btn btn-dark">Save</button>
                    <button type="button" form="form-add-cash" id="btn-save-updated-cash" style="display: none;"
                        class="btn btn-dark">Update</button>
                </div>
            </div>
        </div>
    </div>


    {{-- MODAL DELETE --}}
    <div class="modal fade" id="modalDeleteCash" tabindex="-1" aria-labelledby="modalDeleteCashLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDeleteCashLabel">Delete Treasury</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5">
                        <div class="col-sm-12">
                            <h5>Are you sure you want to delete this cash?</h5>
                            <h5 class="text-danger"><span id="cash-detail-delete"></span></h5>
                            <input type="hidden" id="cash-no-delete">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-end">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="margin-right: 10px;">Close</button>

                        <button type="button" class="btn btn-danger" id="btn-delete-cash">Delete</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/pages/treasuries/cash.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/cash-add.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/cash-delete.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/cash-update.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/cash-search.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/cash-pagination.js') }}"></script>
@endsection
