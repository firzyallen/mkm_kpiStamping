@extends('layouts.master')

@section('content')
    <style>
        #lblGreetings {
            font-size: 1rem;
        }

        @media only screen and (max-width: 600px) {
            #lblGreetings {
                font-size: 1rem;
            }
        }

        .page-header .page-header-content {
            padding-top: 0rem;
            padding-bottom: 1rem;
        }

        .chart-container {
            margin-top: 20px;
        }

        .settings-card {
            cursor: pointer;
        }
    </style>

    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-fluid px-4">
                <div class="page-header-content pt-4">
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid px-4 mt-n10">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                </section>
                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Welding KPI Monitoring ({{ $monthName }} {{ $currentYear }})</h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    @foreach ($shops as $shop)
                                        <li class="nav-item">
                                            <a style="color: black;" class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                id="nav-{{ $shop->id }}-tab" data-bs-toggle="tab"
                                                href="#nav-{{ $shop->id }}" role="tab"
                                                aria-controls="nav-{{ $shop->id }}"
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $shop->shop_name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    @foreach ($shops as $shop)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="nav-{{ $shop->id }}" role="tabpanel"
                                            aria-labelledby="nav-{{ $shop->id }}-tab">
                                            <div class="row pt-3">
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div class="card-header pt-2">
                                                            <h3>HPU (Green if: ≤ STD)
                                                                @php
                                                                    $statusClass = '';
                                                                    $statusText = '';
                                                                    switch ($kpiStatuses[$shop->shop_name]['hpu']) {
                                                                        case 'green':
                                                                            $statusClass = 'signal green';
                                                                            $statusText = 'G';
                                                                            break;
                                                                        case 'red':
                                                                            $statusClass = 'signal red';
                                                                            $statusText = 'R';
                                                                            break;
                                                                        case 'grey':
                                                                            $statusClass = 'signal grey';
                                                                            $statusText = 'N/A';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <canvas id="barChartHpu-{{ $shop->id }}"></canvas>
                                                                <script>
                                                                    var ctxHpu = document.getElementById('barChartHpu-{{ $shop->id }}').getContext('2d');
                                                                    var hpuChart = new Chart(ctxHpu, {
                                                                        type: 'bar',
                                                                        data: {
                                                                            labels: @json($kpiData[$shop->shop_name]['hpu']->pluck('formatted_date')),
                                                                            datasets: [{
                                                                                    label: 'Actual',
                                                                                    data: @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU')),
                                                                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                                                    borderWidth: 1
                                                                                },
                                                                                {
                                                                                    label: 'Plan',
                                                                                    data: @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU_Plan')),
                                                                                    type: 'line',
                                                                                    borderColor: 'rgba(255, 99, 132, 1)',
                                                                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                                                    fill: false,
                                                                                }
                                                                            ]
                                                                        },
                                                                        options: {
                                                                            scales: {
                                                                                y: {
                                                                                    beginAtZero: true
                                                                                }
                                                                            }
                                                                        }
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div class="card-header pt-2">
                                                            <h3>FTT (Green if: ≥
                                                                {{ $kpiData[$shop->shop_name]['ftt'][0]->FTT_Plan }})
                                                                @php
                                                                    $statusClass = '';
                                                                    $statusText = '';
                                                                    switch ($kpiStatuses[$shop->shop_name]['ftt']) {
                                                                        case 'green':
                                                                            $statusClass = 'signal green';
                                                                            $statusText = 'G';
                                                                            break;
                                                                        case 'red':
                                                                            $statusClass = 'signal red';
                                                                            $statusText = 'R';
                                                                            break;
                                                                        case 'grey':
                                                                            $statusClass = 'signal grey';
                                                                            $statusText = 'N/A';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <canvas id="barChartFtt-{{ $shop->id }}"></canvas>
                                                            <script>
                                                                var ctxFtt = document.getElementById('barChartFtt-{{ $shop->id }}').getContext('2d');
                                                                var fttChart = new Chart(ctxFtt, {
                                                                    type: 'bar',
                                                                    data: {
                                                                        labels: @json($kpiData[$shop->shop_name]['ftt']->pluck('formatted_date')),
                                                                        datasets: [{
                                                                                label: 'Actual',
                                                                                data: @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT')),
                                                                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                                                borderWidth: 1
                                                                            },
                                                                            {
                                                                                label: 'Plan',
                                                                                data: @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT_Plan')),
                                                                                type: 'line',
                                                                                borderColor: 'rgba(255, 99, 132, 1)',
                                                                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                                                fill: false,
                                                                            }
                                                                        ]
                                                                    },
                                                                    options: {
                                                                        scales: {
                                                                            y: {

                                                                                ticks: {
                                                                                    beginAtZero: true,
                                                                                    steps: 10,
                                                                                    stepSize: 10,
                                                                                    max: 100
                                                                                },
                                                                                title: {
                                                                                    display: true,
                                                                                    text: 'FTT'
                                                                                }
                                                                            }
                                                                        },
                                                                        plugins: {
                                                                            tooltip: {
                                                                                callbacks: {
                                                                                    title: function(tooltipItem) {
                                                                                        return tooltipItem[0].label;
                                                                                    },
                                                                                    label: function(context) {
                                                                                        if (context.dataset.label === 'Actual') {
                                                                                            return context.dataset.label + ': ' + context.raw.toFixed(2);
                                                                                        } else {
                                                                                            return context.dataset.label + ': ' + context.raw.toFixed(2);
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div class="card-header pt-2">
                                                            <h3>Downtime</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <!-- Downtime chart placeholder -->
                                                            <p>Downtime data will be displayed here once available.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Loop through the models for each shop to create multiple OTDP cards -->
                                                @foreach ($stations->where('shop_id', $shop->id) as $station)
                                                    @foreach ($models->where('station_id', $station->id) as $model)
                                                        <div class="col-md-6 mb-4">
                                                            <div class="card card-custom">
                                                                <div class="card-header pt-2">
                                                                    <h3>OTDP ({{ $model->model_name }}) (Green if: ≥
                                                                        {{ $kpiData[$shop->shop_name]['otdp'][$model->model_name]->first()->OTDP_Plan }})
                                                                        @php
                                                                            $statusClass = '';
                                                                            $statusText = '';
                                                                            switch (
                                                                                $kpiStatuses[$shop->shop_name]['otdp'][
                                                                                    $model->model_name
                                                                                ]
                                                                            ) {
                                                                                case 'green':
                                                                                    $statusClass = 'signal green';
                                                                                    $statusText = 'G';
                                                                                    break;
                                                                                case 'red':
                                                                                    $statusClass = 'signal red';
                                                                                    $statusText = 'R';
                                                                                    break;
                                                                                case 'grey':
                                                                                    $statusClass = 'signal grey';
                                                                                    $statusText = 'N/A';
                                                                                    break;
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                    </h3>
                                                                </div>
                                                                <div class="card-body">
                                                                    <canvas
                                                                        id="barChartOtdp-{{ $shop->id }}-{{ $model->id }}"></canvas>
                                                                    <script>
                                                                        var ctxOtdp = document.getElementById('barChartOtdp-{{ $shop->id }}-{{ $model->id }}').getContext('2d');
                                                                        var otdpChart = new Chart(ctxOtdp, {
                                                                            type: 'bar',
                                                                            data: {
                                                                                labels: @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]->pluck('formatted_date')),
                                                                                datasets: [{
                                                                                        label: 'Actual',
                                                                                        data: @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]->pluck('OTDP')),
                                                                                        backgroundColor: '#A6CAD8',
                                                                                        borderColor: 'rgba(75, 192, 192, 1)',
                                                                                        borderWidth: 1
                                                                                    },
                                                                                    {
                                                                                        label: 'Plan',
                                                                                        data: @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]->pluck('OTDP_Plan')),
                                                                                        type: 'line',
                                                                                        borderColor: 'rgba(255, 99, 132, 1)',
                                                                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                                                        fill: false,
                                                                                    }
                                                                                ]
                                                                            },
                                                                            options: {
                                                                                scales: {
                                                                                    y: {
                                                                                        beginAtZero: true
                                                                                    }
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title">Shop Details</h3>
                                                            <button class="btn btn-link" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#shopdetailsCardContent-{{ $shop->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="shopdetailsCardContent-{{ $shop->id }}">
                                                                <i style="color: black;" class="fas fa-chevron-down"></i>
                                                            </button>
                                                        </div>
                                                        <div class="collapse"
                                                            id="shopdetailsCardContent-{{ $shop->id }}">
                                                            <div class="card-body">
                                                                <!-- Table for The Shop Details -->
                                                                <div class="table-responsive">
                                                                    <table id="tableShopDetails"
                                                                        class="table table-bordered table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Date</th>
                                                                                <th>Shift</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $no = 1;
                                                                            @endphp
                                                                            @foreach ($shopDetails->where('shop_id', $shop->id) as $detail)
                                                                                <tr>
                                                                                    <td>{{ $no++ }}</td>
                                                                                    <td>{{ $detail->date }}</td>
                                                                                    <td>{{ $detail->shift }}</td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-sm btn-primary"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#modal-detail-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}">Detail</button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    @foreach ($shopDetails->where('shop_id', $shop->id) as $detail)
                                                                        <!-- Modal for showing spare parts used in historical problem -->
                                                                        <div class="modal fade"
                                                                            id="modal-detail-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}"
                                                                            tabindex="-1"
                                                                            aria-labelledby="modal-detail-label-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="modal-detail-label-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}">
                                                                                            Detail of Report</h5>
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">

                                                                                        @if ($detail->photo_shop)
                                                                                            <div class="row mb-3">
                                                                                                <div
                                                                                                    class="col-md-12 text-center">
                                                                                                    <img src="{{ asset($detail->photo_shop) }}"
                                                                                                        class="img-fluid"
                                                                                                        alt="Shop Detail Image">
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                        <hr>
                                                                                        <div class="row mb-3">
                                                                                            <div class="col-md-6">
                                                                                                <h6><strong>Notes:</strong>
                                                                                                    {{ $detail->notes ?? 'No notes' }}
                                                                                                </h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title">NG Details</h3>
                                                            <button class="btn btn-link" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#ngdetailsCardContent-{{ $shop->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="ngdetailsCardContent-{{ $shop->id }}">
                                                                <i style="color: black;" class="fas fa-chevron-down"></i>
                                                            </button>
                                                        </div>
                                                        <div class="collapse"
                                                            id="ngdetailsCardContent-{{ $shop->id }}">
                                                            <div class="card-body">
                                                                <!-- Table for The Shop Details -->
                                                                <div class="table-responsive">
                                                                    <table id="tableNGDetails"
                                                                        class="table table-bordered table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Date</th>
                                                                                <th>Shift</th>
                                                                                <th>Model Name</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $no = 1;
                                                                            @endphp
                                                                            @foreach ($ngDetails->where('shop_id', $shop->id) as $detail)
                                                                                <tr>
                                                                                    <td>{{ $no++ }}</td>
                                                                                    <td>{{ $detail->date }}</td>
                                                                                    <td>{{ $detail->shift }}</td>
                                                                                    <td>{{ $detail->model_name }}</td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-sm btn-primary"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#modal-detail-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}-{{ $detail->model_id }}">Detail</button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    @foreach ($ngDetails->where('shop_id', $shop->id) as $detail)
                                                                        <!-- Modal for showing spare parts used in historical problem -->
                                                                        <div class="modal fade"
                                                                            id="modal-detail-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}-{{ $detail->model_id }}"
                                                                            tabindex="-1"
                                                                            aria-labelledby="modal-detail-label-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}-{{ $detail->model_id }}"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="modal-detail-label-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}">
                                                                                            Detail of Report</h5>
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">

                                                                                        @if ($detail->photo_ng)
                                                                                            <div class="row mb-3">
                                                                                                <div
                                                                                                    class="col-md-12 text-center">
                                                                                                    <img src="{{ asset($detail->photo_ng) }}"
                                                                                                        class="img-fluid"
                                                                                                        alt="NG Image">
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                        <hr>
                                                                                        <div class="row mb-3">
                                                                                            <div class="col-md-6">
                                                                                                <h6><strong>Reject:</strong>
                                                                                                    {{ $detail->reject }}
                                                                                                </h6>
                                                                                                <h6><strong>Rework:</strong>
                                                                                                    {{ $detail->rework }}
                                                                                                </h6>
                                                                                                <h6><strong>Remarks:</strong>
                                                                                                    {{ $detail->remarks ?? 'No remarks' }}
                                                                                                </h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card mt-4 settings-card">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                id="settingsCardHeader" style="cursor: pointer;">
                                <h3 class="card-title">Settings</h3>
                                <i style="color: black; margin-right: 10px;" class="fas fa-chevron-down"></i>
                            </div>
                            <div class="card-body d-none" id="settingsCardBody">
                                <form action="{{ url('kpi-monitoring/welding') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="month">Month</label>
                                                <select name="month" id="month" class="form-control">
                                                    @foreach (range(1, 12) as $month)
                                                        <option value="{{ $month }}"
                                                            {{ $currentMonth == $month ? 'selected' : '' }}>
                                                            {{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="year">Year</label>
                                                <select name="year" id="year" class="form-control">
                                                    @foreach (range(date('Y'), date('Y') - 5) as $year)
                                                        <option value="{{ $year }}"
                                                            {{ $currentYear == $year ? 'selected' : '' }}>
                                                            {{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date">Status Start Date</label>
                                                <input type="date" name="start_date" id="start_date"
                                                    class="form-control"
                                                    value="{{ $startDate ?? now()->startOfMonth()->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date"> Status End Date</label>
                                                <input type="date" name="end_date" id="end_date"
                                                    class="form-control"
                                                    value="{{ $endDate ?? now()->endOfMonth()->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <button type="submit" class="btn btn-primary">Apply</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <script>
                            document.getElementById('settingsCardHeader').addEventListener('click', function() {
                                var cardBody = document.getElementById('settingsCardBody');
                                cardBody.classList.toggle('d-none');
                            });

                            $(document).ready(function() {
                                var table = $("#tableShopDetails").DataTable({
                                    "responsive": true,
                                    "lengthChange": false,
                                    "autoWidth": false,
                                });
                            });

                            $(document).ready(function() {
                                var table = $("#tableNGDetails").DataTable({
                                    "responsive": true,
                                    "lengthChange": false,
                                    "autoWidth": false,
                                });
                            });
                        </script>

                        <style>
                            #settingsCardHeader {
                                cursor: pointer;
                            }

                            h3 {
                                font-size: 20px;
                            }

                            .signal {
                                display: inline-block;
                                width: 25px;
                                /* Reduced width */
                                height: 25px;
                                /* Reduced height */
                                border-radius: 70%;
                                line-height: 25px;
                                /* Match line-height to height */
                                text-align: center;
                                color: white;
                                font-size-adjust: 0.45;
                                font-weight: bold;
                            }

                            .green {
                                background-color: green;
                            }

                            .yellow {
                                background-color: yellow;
                                color: black;
                            }

                            .red {
                                background-color: red;
                            }

                            .indicator-table {
                                width: auto;
                                /* Adjusted width to auto */
                                border-collapse: collapse;
                                margin: 0 auto;
                                font-size: 0.9rem;
                                /* Adjusted font size */
                            }

                            .indicator-table th,
                            .indicator-table td {
                                border: 1px solid #dee2e6;
                                padding: 4px;
                                /* Reduced padding */
                                text-align: center;
                            }

                            .indicator-table th {
                                background-color: #f8f9fa;
                            }
                        </style>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
