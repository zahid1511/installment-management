@extends('layouts.master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Total Customers</h5>
                    <div class="ibox-tools">
                        <span class="label label-primary pull-right">{{ $data['customers_count'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $data['customers_count'] ?? 0 }}</h1>
                    <div class="stat-percent font-bold text-navy">{{ $data['new_customers_this_month'] ?? 0 }} <i class="fa fa-level-up"></i></div>
                    <small>New customers this month</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Active Purchases</h5>
                    <div class="ibox-tools">
                        <span class="label label-info pull-right">{{ $data['active_purchases'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $data['active_purchases'] ?? 0 }}</h1>
                    <div class="stat-percent font-bold text-info">{{ $data['completed_purchases'] ?? 0 }} <i class="fa fa-check"></i></div>
                    <small>Completed purchases</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Total Revenue</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">Rs. {{ number_format($data['total_revenue'] ?? 0, 2) }}</h1>
                    <div class="stat-percent font-bold text-success">{{ number_format($data['collected_this_month'] ?? 0, 2) }} <i class="fa fa-level-up"></i></div>
                    <small>Collected this month</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Defaulters</h5>
                    <div class="ibox-tools">
                        <span class="label label-danger pull-right">{{ $data['defaulters_count'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $data['defaulters_count'] ?? 0 }}</h1>
                    <div class="stat-percent font-bold text-danger">{{ number_format($data['defaulters_amount'] ?? 0, 2) }} <i class="fa fa-level-down"></i></div>
                    <small>Total amount due</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Collection Chart -->
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Monthly Collection Trend</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Payments -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Recent Payments</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('installments.index') }}">
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data['recent_payments']) && $data['recent_payments']->count() > 0)
                                    @foreach($data['recent_payments'] as $payment)
                                    <tr>
                                        <td>{{ $payment->date ? Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $payment->customer->name ?? '-' }}</td>
                                        <td class="text-right">{{ number_format($payment->installment_amount ?? 0, 2) }}</td>
                                        <td>{{ $payment->receipt_no ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No recent payments</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Due Today</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('installments.index') }}">
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data['due_today']) && $data['due_today']->count() > 0)
                                    @foreach($data['due_today'] as $due)
                                    <tr>
                                        <td>{{ $due->customer->name ?? '-' }}</td>
                                        <td>{{ $due->due_date ? Carbon\Carbon::parse($due->due_date)->format('d/m/Y') : '-' }}</td>
                                        <td class="text-right">{{ number_format($due->installment_amount ?? 0, 2) }}</td>
                                        <td>
                                            <span class="label label-{{ $due->status == 'paid' ? 'primary' : 'warning' }}">
                                                {{ ucfirst($due->status ?? 'pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No payments due today</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Performance -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Top Products</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover no-margins">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Company</th>
                                <th>Sales</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data['top_products']) && $data['top_products']->count() > 0)
                                @foreach($data['top_products'] as $product)
                                <tr>
                                    <td>{{ $product->model ?? '-' }}</td>
                                    <td>{{ $product->company ?? '-' }}</td>
                                    <td>{{ $product->sales_count ?? 0 }}</td>
                                    <td class="text-right">Rs. {{ number_format($product->total_revenue ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No products yet</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Distribution -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Customer Status</h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <canvas id="doughnutChart" height="140"></canvas>
                    </div>
                    <div class="text-center">
                        <div class="row">
                            <div class="col-xs-4">
                                <small class="stats-label">Active</small>
                                <h4>{{ $data['active_customers'] ?? 0 }}</h4>
                            </div>
                            <div class="col-xs-4">
                                <small class="stats-label">Completed</small>
                                <h4>{{ $data['completed_customers'] ?? 0 }}</h4>
                            </div>
                            <div class="col-xs-4">
                                <small class="stats-label">Defaulted</small>
                                <h4>{{ $data['defaulters_count'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Quick Actions</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-user-plus"></i> Add New Customer
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('products.create') }}" class="btn btn-info btn-block">
                                <i class="fa fa-cube"></i> Add New Product
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('purchases.create') }}" class="btn btn-success btn-block">
                                <i class="fa fa-shopping-cart"></i> New Purchase
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('installments.index') }}" class="btn btn-warning btn-block">
                                <i class="fa fa-credit-card"></i> Process Payment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Flot Chart
    var data1 = [
        @if(isset($data['monthly_collections']))
            @foreach($data['monthly_collections'] as $month => $amount)
            ["{{ $month }}", {{ $amount ?? 0 }}],
            @endforeach
        @endif
    ];

    if (data1.length > 0) {
        $.plot($("#flot-line-chart"), [
            {
                data: data1,
                label: "Monthly Collections",
                color: "#1ab394"
            }
        ], {
            series: {
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.0
                        }, {
                            opacity: 0.2
                        }]
                    }
                },
                points: {
                    radius: 4,
                    show: true
                }
            },
            grid: {
                borderWidth: 0,
                borderColor: '#f0f0f0',
                labelMargin: 10,
                hoverable: true,
                clickable: true,
                mouseActiveRadius: 6
            },
            xaxis: {
                ticks: data1.map(function(item, index) { return [index, item[0]]; }),
                color: "transparent"
            },
            yaxis: {
                color: "transparent",
                tickFormatter: function(val) {
                    return "Rs. " + val.toFixed(0);
                }
            },
            legend: {
                show: false
            }
        });
    }

    // Doughnut Chart
    var doughnutData = {
        labels: ["Active", "Completed", "Defaulted"],
        datasets: [{
            data: [{{ $data['active_customers'] ?? 0 }}, {{ $data['completed_customers'] ?? 0 }}, {{ $data['defaulters_count'] ?? 0 }}],
            backgroundColor: ["#1ab394", "#23c6c8", "#f8ac59"]
        }]
    };

    var doughnutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false
        }
    };

    var ctx = document.getElementById("doughnutChart").getContext("2d");
    new Chart(ctx, {
        type: 'doughnut',
        data: doughnutData,
        options: doughnutOptions
    });
});
</script>
@endpush