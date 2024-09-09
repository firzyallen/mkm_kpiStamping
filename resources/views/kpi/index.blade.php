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
            height: 25px;
            border-radius: 70%;
            line-height: 25px;
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
            border-collapse: collapse;
            margin: 0 auto;
            font-size: 0.9rem;
        }

        .indicator-table th,
        .indicator-table td {
            border: 1px solid #dee2e6;
            padding: 4px;
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
            width: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-custom .card-header {
            flex-shrink: 0;
        }

        .card-custom .card-body {
            flex-grow: 1;
            display: flex;
            padding: 0;
            overflow: hidden;
        }


        .chart-container {
            margin: 0 auto;
            /* width: 80%;
                    height: 100%; */
        }

        .chart-custom {
            width: 100% !important;
            height: 100% !important;
        }

        /* body {
                        transform: scale(0.7);
                        transform-origin: top left;
                        width: 142.857%;
                    } */

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
                                <h3 class="card-title">Factory B KPI Monitoring ({{ $monthName }} {{ $currentYear }})
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
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div class="card-header pt-2">
                                                            @php
                                                                $otdpPlan = '';
                                                                switch ($shop->id) {
                                                                    case 6:
                                                                        $otdpPlan = '97.0';
                                                                        break;
                                                                    case 7:
                                                                        $otdpPlan = '98.0';
                                                                        break;
                                                                    case 8:
                                                                        $otdpPlan = '98.0';
                                                                        break;
                                                                    case 9:
                                                                        $otdpPlan = '98.0';
                                                                        break;
                                                                }
                                                            @endphp
                                                            <h3>OTDP (Green if: ≥ {{ $otdpPlan }})
                                                                @php
                                                                    $statusClass = '';
                                                                    $statusText = '';
                                                                    switch ($kpiStatuses[$shop->shop_name]['otdp']) {
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
                                                                <canvas id="barChartOtdp-{{ $shop->id }}"
                                                                    class="chart-custom"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-custom">
                                                        <div class="card-header pt-2">
                                                            @php
                                                                $fttPlan = '';
                                                                switch ($shop->id) {
                                                                    case 6:
                                                                        $fttPlan = '97.5';
                                                                        break;
                                                                    case 7:
                                                                        $fttPlan = '95.0';
                                                                        break;
                                                                    case 8:
                                                                        $fttPlan = '95.0';
                                                                        break;
                                                                    case 9:
                                                                        $fttPlan = '95.0';
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
                                <form action="{{ url('kpi-monitoring/factoryb') }}" method="GET">
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
                            // Trigger chart creation when the tab becomes visible
document.addEventListener('DOMContentLoaded', function() {
    // Function to create charts
    function createChartOnVisible(tabId, chartId, planData, actualData, formattedDates) {
        var chartInitialized = false; // Track whether chart is initialized

        document.getElementById(tabId).addEventListener('shown.bs.tab', function (event) {
            if (!chartInitialized) { // Create chart only once
                createChart(chartId, planData, actualData, formattedDates);
                chartInitialized = true; // Mark chart as initialized
            }
        });
    }

    @foreach ($shops as $shop)
        // Create HPU Chart for the first active tab (load on page load)
        @if ($loop->first)
            createChart('barChartHpu-{{ $shop->id }}',
                @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU_Plan')),
                @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU')),
                @json($kpiData[$shop->shop_name]['hpu']->pluck('formatted_date'))
            );
            createChart('barChartFtt-{{ $shop->id }}',
                @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT_Plan')),
                @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT')),
                @json($kpiData[$shop->shop_name]['ftt']->pluck('formatted_date'))
            );
            createChart('barChartDowntime-{{ $shop->id }}',
                @json($kpiData[$shop->shop_name]['downtime']->pluck('Downtime_Plan')),
                @json($kpiData[$shop->shop_name]['downtime']->pluck('Downtime')),
                @json($kpiData[$shop->shop_name]['downtime']->pluck('formatted_date'))
            );
            createChart('barChartOtdp-{{ $shop->id }}',
                @json($kpiData[$shop->shop_name]['otdp']->pluck('OTDP_Plan')),
                @json($kpiData[$shop->shop_name]['otdp']->pluck('OTDP')),
                @json($kpiData[$shop->shop_name]['otdp']->pluck('formatted_date'))
            );
        @endif

        // For other tabs, load charts on tab click
        createChartOnVisible(
            'nav-{{ $shop->id }}-tab',
            'barChartHpu-{{ $shop->id }}',
            @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU_Plan')),
            @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU')),
            @json($kpiData[$shop->shop_name]['hpu']->pluck('formatted_date'))
        );

        createChartOnVisible(
            'nav-{{ $shop->id }}-tab',
            'barChartFtt-{{ $shop->id }}',
            @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT_Plan')),
            @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT')),
            @json($kpiData[$shop->shop_name]['ftt']->pluck('formatted_date'))
        );

        createChartOnVisible(
            'nav-{{ $shop->id }}-tab',
            'barChartDowntime-{{ $shop->id }}',
            @json($kpiData[$shop->shop_name]['downtime']->pluck('Downtime_Plan')),
            @json($kpiData[$shop->shop_name]['downtime']->pluck('Downtime')),
            @json($kpiData[$shop->shop_name]['downtime']->pluck('formatted_date'))
        );

        createChartOnVisible(
            'nav-{{ $shop->id }}-tab',
            'barChartOtdp-{{ $shop->id }}',
            @json($kpiData[$shop->shop_name]['otdp']->pluck('OTDP_Plan')),
            @json($kpiData[$shop->shop_name]['otdp']->pluck('OTDP')),
            @json($kpiData[$shop->shop_name]['otdp']->pluck('formatted_date'))
        );
    @endforeach
});

// Create chart function
function createChart(canvasId, planData, actualData, formattedDates) {
    if (!planData || !actualData || planData.length === 0 || actualData.length === 0) {
        console.warn(`No data available for chart with ID: ${canvasId}`);
        return;
    }

    // Ensure all missing values are replaced with 0
    planData = Array.from({ length: 31 }, (_, i) => planData[i] != null ? planData[i] : 0);
    actualData = Array.from({ length: 31 }, (_, i) => actualData[i] != null ? actualData[i] : 0);

    var ctx = document.getElementById(canvasId).getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: formattedDates,
            datasets: [{
                label: 'Plan',
                data: planData,
                type: 'line',
                backgroundColor: '#004355',
                borderColor: '#3A7085',
                fill: false,
            }, {
                label: 'Actual',
                data: actualData,
                backgroundColor: '#A6CAD8',
                borderColor: '#007A93',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0 // Ensure y-axis starts at 0
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
}

                        </script>



                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
