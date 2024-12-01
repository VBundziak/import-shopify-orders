@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('page-class', 'order-page')

@section('content')
    <!-- Page Header -->
    <div class="page-layout-header">
        <h1 class="page-layout-title">Orders</h1>
    </div>

    <!-- Main Section -->
    <div class="page-layout-section">
        <div class="card">
            <!-- Card Header -->
            <div class="card-header">
                <div class="card-heading d-flex justify-content-between align-items-center">
                    <h2 class="card-title">Order List</h2>
                    <button id="import-data" class="btn btn-primary">Import Data</button>
                </div>
            </div>

            <!-- Card Section -->
            <div class="card-section">
                <h3 class="card-subtitle">View orders</h3>
                <p>Below is a list of orders. Use the search and sort features to find specific records.</p>
            </div>

            <!-- Table Section -->
            <div class="card-section">

                <div class="row">
                    <div class="col-lg-4">
                        <div class="filter-section">
                            <label for="financial-status-filter" class="form-label">Filter by Financial Status</label>
                            <select id="financial-status-filter" class="form-select">
                                <option value="">All</option>
                                @foreach($financialStatusOptions as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4 search-block">
                        <div class="form-input">
                            <input id="customSearch" type="search" class="input-icon icon-search" placeholder="Search">
                        </div>
                    </div>
                </div>

                <table
                    id="orders-table"
                    class="table-list"
                    data-toggle="table"
                    data-url="{{ route('api.orders') }}"
                    data-pagination="true"
                    data-page-size="5"
                    data-page-list="[10, 25, 50]"
                    data-side-pagination="server"
                    data-search="true"
                    data-search-selector="#customSearch"
                    data-query-params="queryParams"
                    data-locale="en-US"
                >
                    <thead>
                    <tr>
                        <th data-field="customer_name" data-sortable="true">Customer Name</th>
                        <th data-field="customer_email" data-sortable="true">Customer Email</th>
                        <th data-field="total_price" data-sortable="true" data-formatter="priceFormatter">Total Price</th>
                        <th data-field="financial_status" data-sortable="true">Financial Status</th>
                        <th data-field="fulfillment_status" data-sortable="true">Fulfillment Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {

            $('#import-data').on('click', function () {
                $.ajax({
                    url: '{{ route('import.data') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        alert(data.message);
                        location.reload(); // Reload the page after success
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('An error occurred while importing data.');
                    }
                });
            });

            const $table = $('#orders-table');
            const $filterDropdown = $('#financial-status-filter');

            // Initialize table
            $table.bootstrapTable();

            // Handle dropdown change event
            $filterDropdown.on('change', function () {
                $table.bootstrapTable('refresh'); // Refresh the table when the dropdown value changes
            });
        });

        function priceFormatter(value, row) {
            const currencySymbols = {
                USD: '$',
                EUR: '€',
                GBP: '£',
            };
            const symbol = currencySymbols[row.currency] || row.currency;
            return `${symbol} ${value}`;
        }

        function queryParams(params) {
            const selectedStatus = $('#financial-status-filter').val();

            return {
                ...params,
                financial_status: selectedStatus || null,
            };
        }
    </script>
@endpush
