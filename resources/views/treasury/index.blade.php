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

    <div class="row mt-5">
        <div class="col-sm-12 mt-5">
            <h4 class="fw-bold mb-3">Cash Records</h4>
            <div class="row justify-content-end">
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" placeholder="search here . . ."
                            aria-label="search here . . ." aria-describedby="button-addon2">
                        <button class="btn btn-dark" type="button" id="button-addon2">Search</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped-columns table-bordered table-hover fs-6">
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
@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/pages/treasuries/chart-treasury.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/index.js') }}"></script>
@endsection
