@extends('layouts.app')

@section('content')
    <div id="url-api" data-api_get_detail_treasury="{{ $apiGetDetailTreasury }}" data-api_update_checked="{{ $apiUpdateCheckedTreasuryDetail }}"></div>
    <div id="treasury-no" data-treasury_no="{{ $treasuryNo }}"></div>
    <div class="mb-4">
        <h3>Cash Detail</h3>
        <span class="badge text-bg-dark text-white">#{{ $treasuryNo }}</span>
    </div>
    <div class="row mt-5">
        <div class="col-sm-3">
            <div class="mb-3">
                <label for="month">Month</label>
                <select id="month" name="month" class="form-select form-select-sm" aria-label="Select month">
                    @foreach (config('services.months_en') as $month)
                        <option value="{{ $month }}" {{ $month === 'Select Month' ? 'selected disabled' : '' }}>
                            {{ $month }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="month">Year</label>
                <select id="month" name="month" class="form-select form-select-sm" aria-label="Select month">
                    <option value="" disabled>Select Year</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="col-sm-12 mt-5">
            <div class="row justify-content-between align-items-center">
                <div class="col-sm-7">
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-start">
                            <button class="btn btn-sm btn-outline-dark">Add Cash</button>
                            <button class="btn btn-sm btn-outline-danger" style="margin-left: 10px;">Export</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm"
                            placeholder="search here..."
                            aria-label="search here..." aria-describedby="button-addon2">
                        <button class="btn btn-dark" type="button" id="button-addon2">Search</button>
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
@endsection

@section('scripts')
    {{-- <script type="module" src="{{ asset('js/pages/treasuries/delete.js') }}"></script>
    <script type="module" src="{{ asset('js/pages/treasuries/mainTable.js') }}"></script> --}}
    <script type="module" src="{{ asset('js/pages/treasuries/cash.js') }}"></script>
@endsection
