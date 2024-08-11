@extends('layouts.master')

@section('content')
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

        .grey {
            background-color: darkgrey;

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

        .card-custom {
            height: 430px;
            /* Adjust the height as needed */
            width: 100%;
            /* Adjust the width as needed */
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-custom .card-header {
            flex-shrink: 0;
            /* Prevent shrinking to fit the content */
        }

        .card-custom .card-body {
            flex-grow: 1;
            display: flex;
            padding: 0;
            overflow: hidden;
        }

        .chart-container {
            margin: 0 auto;
            /* Center the chart BOLEH DI-DELETE just in case*/
            width: 80%;
            height: 100%;
        }

        .chart-custom {
            width: 100% !important;
            height: 100% !important;
            /* Let the canvas take the full height of the container */
        }

        body {
            transform: scale(0.7);
            transform-origin: top left;
            width: 142.857%;
            /* 100 / 70 */
        }

        .nav-fixed #layoutSidenav #layoutSidenav_nav {
            width: 15rem;
            height: 250vh;
            z-index: 1038;
        }

        .settings-card {
            cursor: pointer;
        }
    </style>

    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-fluid px-4">
                <div class="page-header-content pt-1">
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
                                <h3 class="card-title">Welding KPI Monitoring ({{ $monthName }} {{ $currentYear }})
                                    (Status: {{ $startDate ?? $previousDay->format('Y-m-d') }} to
                                    {{ $endDate ?? $previousDay->format('Y-m-d') }})</h3>
                            </div>
                            <div class="card-body pt-2">
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
                                                                            $statusText = 'N';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart-container">
                                                                <canvas id="barChartHpu-{{ $shop->id }}"
                                                                    class="chart-custom"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div class="card-header pt-2">
                                                            <!--{$kpiData[$shop->shop_name]['ftt'][0]->FTT_Plan}-->
                                                            @php
                                                                $fttPlan = '';
                                                                switch ($shop->id) {
                                                                    case 25:
                                                                        $fttPlan = '98.5';
                                                                        break;
                                                                    case 26:
                                                                        $fttPlan = '98.5';
                                                                        break;
                                                                    case 27:
                                                                        $fttPlan = '98.5';
                                                                        break;
                                                                    case 28:
                                                                        $fttPlan = '98.5';
                                                                        break;
                                                                    case 29:
                                                                        $fttPlan = '98.5';
                                                                        break;
                                                                    case 30:
                                                                        $fttPlan = '98.5';
                                                                        break;
                                                                    case 31:
                                                                        $fttPlan = '98.5';
                                                                        break;
                                                                }
                                                            @endphp
                                                            <h3>FTT (Green if: ≥ {{ $fttPlan }})
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
                                                                            $statusText = 'N';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart-container">
                                                                <canvas id="barChartFtt-{{ $shop->id }}"
                                                                    class="chart-custom"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div class="card-header pt-2">
                                                            <!--ideally should be {$kpiData[$shop->shop_name]['ftt'][0]->Downtime_Plan}, but if no downtime data then will error so hard code it for now-->
                                                            <h3>Downtime (Green if: ≥ 0.81)
                                                                @php
                                                                    $statusClass = '';
                                                                    $statusText = '';
                                                                    switch (
                                                                        $kpiStatuses[$shop->shop_name]['downtime']
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
                                                                            $statusText = 'N';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart-container">
                                                                <canvas id="barChartDowntime-{{ $shop->id }}"
                                                                    class="chart-custom"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Loop through the models for each shop to create multiple OTDP cards -->
                                                @foreach ($stations->where('shop_id', $shop->id) as $station)
                                                    @foreach ($models->where('station_id', $station->id) as $model)
                                                        <div class="col-md-6 mb-4">
                                                            <div class="card card-custom">
                                                                <div class="card-header pt-2">
                                                                    <!--{ $model->model_name }}) (Green if: ≥ {$kpiData[$shop->shop_name]['otdp'][$model->model_name]->first()->OTDP_Plan}-->
                                                                    @php
                                                                        $otdpPlan = '';
                                                                        switch ($shop->id) {
                                                                            case 25:
                                                                                $otdpPlan = '98.5';
                                                                                break;
                                                                            case 26:
                                                                                $otdpPlan = '98.5';
                                                                                break;
                                                                            case 27:
                                                                                $otdpPlan = '98.5';
                                                                                break;
                                                                            case 28:
                                                                                $otdpPlan = '99.5';
                                                                                break;
                                                                            case 29:
                                                                                $otdpPlan = '98.5';
                                                                                break;
                                                                            case 30:
                                                                                $otdpPlan = '98.5';
                                                                                break;
                                                                            case 31:
                                                                                $otdpPlan = '98.5';
                                                                                break;
                                                                        }
                                                                    @endphp
                                                                    <h3>OTDP ({{ $model->model_name }}) (Green if: ≥
                                                                        {{ $otdpPlan }})
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
                                                                                    $statusText = 'N';
                                                                                    break;
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                    </h3>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="chart-container">
                                                                        <canvas
                                                                            id="barChartOtdp-{{ $shop->id }}-{{ $model->id }}"
                                                                            class="chart-custom"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
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
                                                                                                        alt="Shop Detail Image"
                                                                                                        onclick="this.requestFullscreen()">
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
                                                    <div class="card">
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
                                                                                        @php
                                                                                            $images = json_decode(
                                                                                                $detail->photo_ng,
                                                                                                true,
                                                                                            );
                                                                                        @endphp
                                                                                        <button
                                                                                            class="btn btn-primary btn-sm show-ng-image-btn"
                                                                                            data-images="{{ htmlspecialchars(json_encode($images), ENT_QUOTES, 'UTF-8') }}">Detail</button>
                                                                                    </td>
                                                                                    <!-- Modal for Not Goods Images -->
                                                                                    <div class="modal fade"
                                                                                        id="ngImageModal" tabindex="-1"
                                                                                        aria-labelledby="ngImageModalLabel"
                                                                                        aria-hidden="true">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-centered modal-lg">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title"
                                                                                                        id="ngImageModalLabel">
                                                                                                        Not Goods Image
                                                                                                        Preview</h5>
                                                                                                    <button type="button"
                                                                                                        class="btn-close"
                                                                                                        data-bs-dismiss="modal"
                                                                                                        aria-label="Close"></button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <div class="row mb-3">
                                                                                                        <div
                                                                                                            class="col-md-12 text-center">
                                                                                                            <div id="ngCarousel"
                                                                                                                class="carousel slide"
                                                                                                                data-bs-ride="carousel">
                                                                                                                <div class="carousel-inner"
                                                                                                                    id="ngCarouselInner">
                                                                                                                    <!-- Images will be injected here by JavaScript -->
                                                                                                                </div>
                                                                                                                <button
                                                                                                                    class="carousel-control-prev"
                                                                                                                    type="button"
                                                                                                                    data-bs-target="#ngCarousel"
                                                                                                                    data-bs-slide="prev">
                                                                                                                    <span
                                                                                                                        class="carousel-control-prev-icon"
                                                                                                                        aria-hidden="true"></span>
                                                                                                                    <span
                                                                                                                        class="visually-hidden">Previous</span>
                                                                                                                </button>
                                                                                                                <button
                                                                                                                    class="carousel-control-next"
                                                                                                                    type="button"
                                                                                                                    data-bs-target="#ngCarousel"
                                                                                                                    data-bs-slide="next">
                                                                                                                    <span
                                                                                                                        class="carousel-control-next-icon"
                                                                                                                        aria-hidden="true"></span>
                                                                                                                    <span
                                                                                                                        class="visually-hidden">Next</span>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <hr>
                                                                                                        <div
                                                                                                            class="row mb-3">
                                                                                                            <div
                                                                                                                class="col-md-6">
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
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button"
                                                                                                        class="btn btn-secondary"
                                                                                                        data-bs-dismiss="modal">Close</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title">Downtime Details</h3>
                                                            <button class="btn btn-link" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#downtimedetailsCardContent-{{ $shop->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="downtimedetailsCardContent-{{ $shop->id }}">
                                                                <i style="color: black;" class="fas fa-chevron-down"></i>
                                                            </button>
                                                        </div>
                                                        <div class="collapse"
                                                            id="downtimedetailsCardContent-{{ $shop->id }}">
                                                            <div class="card-body">
                                                                <!-- Table for The Shop Details -->
                                                                <div class="table-responsive">
                                                                    <table id="tableDowntimeDetails"
                                                                        class="table table-bordered table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Date</th>
                                                                                <th>Shift</th>
                                                                                <th>Machine</th>
                                                                                <th>Category</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $no = 1;
                                                                            @endphp
                                                                            @foreach ($downtimeDetails->where('shop_name', $shop->shop_name) as $detail)
                                                                                <tr>
                                                                                    <td>{{ $no++ }}</td>
                                                                                    <td>{{ $detail->date }}</td>
                                                                                    <td>{{ $detail->shift }}</td>
                                                                                    <td>{{ $detail->machine_name }}</td>
                                                                                    <td>{{ $detail->category }}</td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-sm btn-primary"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#modal-detail-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}-{{ $detail->id }}">Detail</button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    @foreach ($downtimeDetails->where('shop_name', $shop->shop_name) as $detail)
                                                                        <!-- Modal for showing spare parts used in historical problem -->
                                                                        <div class="modal fade"
                                                                            id="modal-detail-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}-{{ $detail->id }}"
                                                                            tabindex="-1"
                                                                            aria-labelledby="modal-detail-label-{{ $detail->date }}-{{ $detail->shift }}-{{ $shop->id }}-{{ $detail->id }}"
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

                                                                                        @if ($detail->photo)
                                                                                            <div class="row mb-3">
                                                                                                <div
                                                                                                    class="col-md-12 text-center">
                                                                                                    <img src="{{ asset($detail->photo) }}"
                                                                                                        class="img-fluid"
                                                                                                        alt="Downtime Image"
                                                                                                        onclick="this.requestFullscreen()">
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                        <hr>
                                                                                        <div class="row mb-3">
                                                                                            <div class="col-md-6">
                                                                                                <h6><strong>Problem:</strong>
                                                                                                    {{ $detail->problem }}
                                                                                                </h6>
                                                                                                <h6><strong>Cause:</strong>
                                                                                                    {{ $detail->cause ?? "Cause haven't been found yet." }}
                                                                                                </h6>
                                                                                                <h6><strong>Action:</strong>
                                                                                                    {{ $detail->action ?? 'No remarks' }}
                                                                                                </h6>
                                                                                                <h6><strong>Judgement:</strong>
                                                                                                    {{ $detail->judgement ?? 'Judgement is not reported yet.' }}
                                                                                                </h6>
                                                                                                <h6><strong>Start
                                                                                                        Time:</strong>
                                                                                                    {{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }}
                                                                                                </h6>
                                                                                                <h6><strong>End
                                                                                                        Time:</strong>
                                                                                                    {{ $detail->end_time ? \Carbon\Carbon::parse($detail->end_time)->format('H:i') : 'End time is not reported yet.' }}
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
                                                    value="{{ $startDate ?? $previousDay->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date"> Status End Date</label>
                                                <input type="date" name="end_date" id="end_date"
                                                    class="form-control"
                                                    value="{{ $endDate ?? $previousDay->format('Y-m-d') }}">
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
                            document.addEventListener('DOMContentLoaded', function() {
                                // Handle click event on show not goods image button
                                document.querySelectorAll('.show-ng-image-btn').forEach(function(button) {
                                    button.addEventListener('click', function(event) {
                                        event.preventDefault();
                                        var images = JSON.parse(this.getAttribute('data-images').replace(/&quot;/g,
                                            '"'));
                                        var ngCarouselInner = document.getElementById('ngCarouselInner');
                                        ngCarouselInner.innerHTML = ''; // Clear existing images
                                        var isActive = 'active';
                                        images.forEach(function(image) {
                                            ngCarouselInner.innerHTML += `
                                                <div class="carousel-item ${isActive}">
                                                    <img src="{{ asset('${image}') }}" class="d-block w-100 img-fluid" alt="Image Preview" onclick="this.requestFullscreen()">
                                                </div>
                                            `;
                                            isActive = ''; // Only the first item should be active
                                        });
                                        var ngImageModal = new bootstrap.Modal(document.getElementById('ngImageModal'));
                                        ngImageModal.show();
                                    });
                                });

                                document.getElementById('settingsCardHeader').addEventListener('click', function() {
                                    var cardBody = document.getElementById('settingsCardBody');
                                    cardBody.classList.toggle('d-none');
                                });

                                $('#tableShopDetails').DataTable({
                                    "responsive": true,
                                    "lengthChange": false,
                                    "autoWidth": false,
                                });

                                $('#tableNGDetails').DataTable({
                                    "responsive": true,
                                    "lengthChange": false,
                                    "autoWidth": false,
                                });
                                $('#tableDowntimeDetails').DataTable({
                                    "responsive": true,
                                    "lengthChange": false,
                                    "autoWidth": false,
                                });

                                @foreach ($shops as $shop)
                                    console.log("Data for Shop: {{ $shop->shop_name }}");

                                    var hpuPlanData = @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU_Plan'));
                                    var hpuActualData = @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU'));
                                    console.log("HPU Plan Data: ", hpuPlanData);
                                    console.log("HPU Actual Data: ", hpuActualData);

                                    hpuPlanData = hpuPlanData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ? 0 : parseFloat(
                                        val));
                                    hpuActualData = hpuActualData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ? 0 :
                                        parseFloat(val));
                                    console.log("Filtered HPU Plan Data: ", hpuPlanData);
                                    console.log("Filtered HPU Actual Data: ", hpuActualData);

                                    new Chart(document.getElementById('barChartHpu-{{ $shop->id }}').getContext('2d'), {
                                        type: 'bar',
                                        data: {
                                            labels: Array.from({
                                                length: 31
                                            }, (_, i) => i + 1),
                                            datasets: [{
                                                label: 'Plan',
                                                data: hpuPlanData,
                                                type: 'line',
                                                backgroundColor: '#004355',
                                                borderColor: '#3A7085',
                                                fill: false,
                                            }, {
                                                label: 'Actual',
                                                data: hpuActualData,
                                                backgroundColor: '#A6CAD8',
                                                borderColor: '#007A93',
                                                borderWidth: 2
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                x: {
                                                    beginAtZero: true,
                                                    ticks: {
                                                        callback: function(value) {
                                                            return [1, 4, 8, 12, 16, 20, 24, 28].includes(value) ? value :
                                                                '';
                                                        }
                                                    }
                                                },
                                                y: {
                                                    beginAtZero: true,
                                                    min: 0,
                                                    max: 2,
                                                    ticks: {
                                                        stepSize: 0.1,
                                                        callback: function(value) {
                                                            return value.toFixed(1);
                                                        }
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'HPU'
                                                    }
                                                }
                                            }
                                        }
                                    });

                                    var fttPlanData = @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT_Plan'));
                                    var fttActualData = @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT'));
                                    console.log("FTT Plan Data: ", fttPlanData);
                                    console.log("FTT Actual Data: ", fttActualData);

                                    fttPlanData = fttPlanData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ? 0 : parseFloat(
                                        val));
                                    fttActualData = fttActualData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ? 0 :
                                        parseFloat(val));
                                    console.log("Filtered FTT Plan Data: ", fttPlanData);
                                    console.log("Filtered FTT Actual Data: ", fttActualData);

                                    new Chart(document.getElementById('barChartFtt-{{ $shop->id }}').getContext('2d'), {
                                        type: 'bar',
                                        data: {
                                            labels: Array.from({
                                                length: 31
                                            }, (_, i) => i + 1),
                                            datasets: [{
                                                label: 'Plan',
                                                data: fttPlanData,
                                                type: 'line',
                                                backgroundColor: '#004355',
                                                borderColor: '#3A7085',
                                                fill: false,
                                            }, {
                                                label: 'Actual',
                                                data: fttActualData,
                                                backgroundColor: '#A6CAD8',
                                                borderColor: '#007A93',
                                                borderWidth: 2
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    min: 0,
                                                    max: 100,
                                                    ticks: {
                                                        stepSize: 10
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
                                                            return context.dataset.label + ': ' + context.raw.toFixed(2);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });

                                    var downtimePlanData = @json($kpiData[$shop->shop_name]['ftt']->pluck('Downtime_Plan'));
                                    var downtimeActualData = @json($kpiData[$shop->shop_name]['downtime']->pluck('Downtime'));
                                    console.log("Downtime Plan Data: ", downtimePlanData);
                                    console.log("Downtime Actual Data: ", downtimeActualData);

                                    downtimePlanData = downtimePlanData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ? 0 :
                                        parseFloat(val));
                                    downtimeActualData = downtimeActualData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ?
                                        0 : parseFloat(val));
                                    console.log("Filtered Downtime Plan Data: ", downtimePlanData);
                                    console.log("Filtered Downtime Actual Data: ", downtimeActualData);

                                    new Chart(document.getElementById('barChartDowntime-{{ $shop->id }}').getContext('2d'), {
                                        type: 'bar',
                                        data: {
                                            labels: Array.from({
                                                length: 31
                                            }, (_, i) => i + 1),
                                            datasets: [{
                                                label: 'Plan',
                                                data: downtimePlanData,
                                                type: 'line',
                                                backgroundColor: '#004355',
                                                borderColor: '#3A7085',
                                                fill: false,
                                            }, {
                                                label: 'Actual',
                                                data: downtimeActualData,
                                                backgroundColor: '#A6CAD8',
                                                borderColor: '#007A93',
                                                borderWidth: 2
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    min: 0,
                                                    max: 100,
                                                    ticks: {
                                                        stepSize: 10
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Downtime'
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
                                                            return context.dataset.label + ': ' + context.raw.toFixed(2);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });

                                    @foreach ($stations->where('shop_id', $shop->id) as $station)
                                        @foreach ($models->where('station_id', $station->id) as $model)
                                            var otdpPlanData = @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]->pluck('OTDP_Plan'));
                                            var otdpActualData = @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]->pluck('OTDP'));
                                            console.log("OTDP Plan Data for Model {{ $model->model_name }}: ", otdpPlanData);
                                            console.log("OTDP Actual Data for Model {{ $model->model_name }}: ", otdpActualData);

                                            otdpPlanData = otdpPlanData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ? 0 :
                                                parseFloat(val));
                                            otdpActualData = otdpActualData.map(val => parseFloat(val) < 0 || isNaN(parseFloat(val)) ?
                                                0 : parseFloat(val));
                                            console.log("Filtered OTDP Plan Data for Model {{ $model->model_name }}: ", otdpPlanData);
                                            console.log("Filtered OTDP Actual Data for Model {{ $model->model_name }}: ",
                                                otdpActualData);

                                            new Chart(document.getElementById(
                                                'barChartOtdp-{{ $shop->id }}-{{ $model->id }}').getContext('2d'), {
                                                type: 'bar',
                                                data: {
                                                    labels: Array.from({
                                                        length: 31
                                                    }, (_, i) => i + 1),
                                                    datasets: [{
                                                        label: 'Plan',
                                                        data: otdpPlanData,
                                                        type: 'line',
                                                        backgroundColor: '#004355',
                                                        borderColor: '#3A7085',
                                                        fill: false,
                                                    }, {
                                                        label: 'Actual',
                                                        data: otdpActualData,
                                                        backgroundColor: '#A6CAD8',
                                                        borderColor: '#007A93',
                                                        borderWidth: 2
                                                    }]
                                                },
                                                options: {
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true,
                                                            min: 0
                                                        }
                                                    }
                                                }
                                            });
                                        @endforeach
                                    @endforeach
                                @endforeach
                            });
                        </script>



                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
