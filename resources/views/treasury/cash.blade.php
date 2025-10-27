@extends('layouts.app')

@section('content')
    <h3 class="mb-4">Cash Records</h3>
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

            <div class="mb-3 mt-5">
                <div class="d-flex align-items-center justify-content-start">
                    <button class="btn btn-sm btn-outline-dark">Add Cash</button>
                    <button class="btn btn-sm btn-outline-danger" style="margin-left: 10px;">Export</button>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="row justify-content-end">
                <div class="col-sm-5">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" placeholder="search detail, expense, or income here..."
                            aria-label="search detail, expense, or income here..." aria-describedby="button-addon2">
                        <button class="btn btn-dark" type="button" id="button-addon2">Search</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped-columns table-bordered table-hover fs-6">
                    <thead class="table-dark">
                        <th>Detail</th>
                        <th>Month</th>
                        <th>Income</th>
                        <th>Expense</th>
                        <th>Check</th>
                        <th>Balance</th>
                        <th>Act Balance</th>
                    </thead>
                    <tbody id="tbody-treasury-cash">
                        <tr>
                            <td>asasdsa</td>
                            <td>adsasd</td>
                            <td>100000</td>
                            <td>sacsacs</td>
                            <td>scasacs</td>
                            <td>cacasasc</td>
                            <td>sdadsdwjj</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
