@extends('layouts.app')

@section('content')
    <div id="url-api" data-api_list_treasure="{{ $list_treasuries_api }}"></div>
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
            <h4 class="fw-bold mb-3">Cash Records</h4>
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

    {{-- Toast notification --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <div id="toast-treasury" class="toast align-items-center text-bg-primary border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
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
@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/pages/treasuries/chart-treasury.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/index.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/search.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/mainTable.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/delete.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/pagination.js') }}"></script>
@endsection
