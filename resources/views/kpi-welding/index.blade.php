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
                                                                <div id="chartdiv-{{ $shop->id }}" class="chart-custom" style="width: 100%; height: 400px;"></div>
                                                                <script>
                                                                    am5.ready(function () {
                                                                        // Create root element
                                                                        var root = am5.Root.new("chartdiv-{{ $shop->id }}");

                                                                        // Set themes
                                                                        root.setThemes([am5themes_Animated.new(root)]);

                                                                        // Create chart
                                                                        var chart = root.container.children.push(
                                                                            am5xy.XYChart.new(root, {
                                                                                panX: false,
                                                                                panY: false,
                                                                                wheelX: "none",
                                                                                wheelY: "none",
                                                                                layout: root.verticalLayout
                                                                            })
                                                                        );

                                                                        // Fetch the HPU and Plan data
                                                                        var hpuData = @json($kpiData[$shop->shop_name]['hpu']);
                                                                        var hpuPlanData = @json($kpiData[$shop->shop_name]['hpu']->pluck('HPU_Plan'));
                                                                        var encryptedHeaderIds = @json($kpiData[$shop->shop_name]['hpu']->pluck('header_id'));

                                                                        // Predefine x-axis categories (1-31)
                                                                        var allDates = Array.from({ length: 31 }, (_, i) => (i + 1).toString());

                                                                        // Initialize data arrays for HPU, Plan, and encrypted Header IDs
                                                                        var fullHpuData = Array(31).fill(null);
                                                                        var fullHpuPlanData = Array(31).fill(0);
                                                                        var fullEncryptedHeaderIds = Array(31).fill(null);

                                                                        // Map actual data and encrypted header IDs to the corresponding day
                                                                        hpuData.forEach((item, index) => {
                                                                            var day = parseInt(item.formatted_date.split(' ')[1]);
                                                                            fullHpuData[day - 1] = item.HPU;
                                                                            fullEncryptedHeaderIds[day - 1] = encryptedHeaderIds[index];
                                                                        });

                                                                        hpuPlanData.forEach((value, index) => {
                                                                            var day = parseInt(hpuData[index].formatted_date.split(' ')[1]);
                                                                            fullHpuPlanData[day - 1] = value;
                                                                        });

                                                                        // Set default y-axis max based on the data
                                                                        var maxHpuValue = Math.max(...fullHpuData.filter(v => v !== null), 0.2);

                                                                        // Create x-axis with tooltip for Date
                                                                        var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
                                                                        var xAxis = chart.xAxes.push(
                                                                            am5xy.CategoryAxis.new(root, {
                                                                                categoryField: "date",
                                                                                renderer: xRenderer,
                                                                                tooltip: am5.Tooltip.new(root, {
                                                                                    labelText: " {date}"
                                                                                })
                                                                            })
                                                                        );
                                                                        xAxis.data.setAll(allDates.map(date => ({ date })));

                                                                        // Create y-axis with tooltip for Percentage
                                                                        var yAxis = chart.yAxes.push(
                                                                            am5xy.ValueAxis.new(root, {
                                                                                min: 0,
                                                                                max: maxHpuValue,
                                                                                renderer: am5xy.AxisRendererY.new(root, {})
                                                                            })
                                                                        );

                                                                        // Add the 'Plan' series with custom tooltip
                                                                        var planSeries = chart.series.push(
                                                                            am5xy.LineSeries.new(root, {
                                                                                name: "Plan",
                                                                                xAxis: xAxis,
                                                                                yAxis: yAxis,
                                                                                valueYField: "plan",
                                                                                categoryXField: "date",
                                                                                stroke: am5.color("#004355"),
                                                                                tooltip: am5.Tooltip.new(root, {
                                                                                    labelText: "Plan: {valueY.formatNumber('#.###')}"
                                                                                })
                                                                            })
                                                                        );
                                                                        planSeries.strokes.template.setAll({ strokeWidth: 6 });
                                                                        planSeries.data.setAll(
                                                                            allDates.map((date, i) => ({ date, plan: fullHpuPlanData[i], header_id: fullEncryptedHeaderIds[i] }))
                                                                        );

                                                                        // Add the 'Actual' series with custom tooltip
                                                                        var actualSeries = chart.series.push(
                                                                            am5xy.ColumnSeries.new(root, {
                                                                                name: "Actual",
                                                                                xAxis: xAxis,
                                                                                yAxis: yAxis,
                                                                                valueYField: "actual",
                                                                                categoryXField: "date",
                                                                                fill: am5.color("#A6CAD8"),
                                                                                stroke: am5.color("#007A93"),
                                                                                clustered: false,
                                                                                tooltip: am5.Tooltip.new(root, {
                                                                                    labelText: "Actual: {valueY.formatNumber('#.###')}"
                                                                                })
                                                                            })
                                                                        );
                                                                        actualSeries.columns.template.setAll({ width: am5.percent(50) });
                                                                        actualSeries.data.setAll(
                                                                            allDates.map((date, i) => ({ date, actual: fullHpuData[i], header_id: fullEncryptedHeaderIds[i] }))
                                                                        );

                                                                        // Make columns (bars) clickable
                                                                        actualSeries.columns.template.events.on("click", function (ev) {
                                                                            var headerId = ev.target.dataItem.dataContext.header_id;

                                                                            console.log("Clicked encrypted header ID:", headerId);

                                                                            if (headerId) {
                                                                                var url = "/daily-report/welding/detail/" + encodeURIComponent(headerId);
                                                                                window.open(url, '_blank');
                                                                            } else {
                                                                                console.error("Header ID not found");
                                                                            }
                                                                        });


                                                                        // Add bullets (dots) for matching dates with both Plan and Actual
                                                                        planSeries.bullets.push(function (root, series, dataItem) {
                                                                            var actualValue = fullHpuData[parseInt(dataItem.get("categoryX")) - 1];
                                                                            var planValue = dataItem.get("valueY");
                                                                            var headerId = fullEncryptedHeaderIds[dataItem.index];

                                                                            if (actualValue !== null && planValue !== null) {
                                                                                var color = actualValue < planValue ? am5.color(0xff0000) : am5.color(0x00ff00);

                                                                                var bullet = am5.Bullet.new(root, {
                                                                                    sprite: am5.Circle.new(root, {
                                                                                        strokeWidth: 2,
                                                                                        stroke: am5.color(0x000000),
                                                                                        radius: 5,
                                                                                        fill: color
                                                                                    })
                                                                                });

                                                                                // Make bullets (dots) clickable
                                                                                bullet.get("sprite").events.on("click", function () {
                                                                                    console.log("Clicked dot encrypted header ID:", headerId);

                                                                                    if (headerId) {
                                                                                        var url = "/daily-report/welding/detail/" + headerId;
                                                                                        window.open(url, '_blank');
                                                                                    } else {
                                                                                        console.error("Header ID not found");
                                                                                    }
                                                                                });

                                                                                return bullet;
                                                                            }
                                                                            return null;
                                                                        });

                                                                        // Add a cursor for interactivity
                                                                        chart.set("cursor", am5xy.XYCursor.new(root, {
                                                                            behavior: "none",
                                                                            xAxis: xAxis,
                                                                            yAxis: yAxis
                                                                        }));

                                                                        // Add legend
                                                                        var legend = chart.children.push(am5.Legend.new(root, { centerX: am5.p50, x: am5.p50 }));
                                                                        legend.data.setAll([planSeries, actualSeries]);

                                                                        // Animate series on appear
                                                                        planSeries.appear(1000);
                                                                        actualSeries.appear(1000);
                                                                        chart.appear(1000, 100);
                                                                    });
                                                                </script>
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
                                                                    switch ($kpiStatuses[$shop->shop_name]['downtime']) {
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
                                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div id="chartdiv-downtime-{{ $shop->id }}" class="chart-custom" style="width: 100%; height: 400px;"></div>
                                                            <script>
                                                                am5.ready(function() {
                                                                    // Create root element
                                                                    var root = am5.Root.new("chartdiv-downtime-{{ $shop->id }}");

                                                                    // Set themes
                                                                    root.setThemes([am5themes_Animated.new(root)]);

                                                                    // Create chart
                                                                    var chart = root.container.children.push(
                                                                        am5xy.XYChart.new(root, {
                                                                            panX: false,
                                                                            panY: false,
                                                                            wheelX: "none",
                                                                            wheelY: "none",
                                                                            layout: root.verticalLayout
                                                                        })
                                                                    );

                                                                    // Fetch the Downtime and Plan data
                                                                    var downtimeData = @json($kpiData[$shop->shop_name]['downtime']);
                                                                    var downtimePlanData = @json($kpiData[$shop->shop_name]['downtime']->pluck('Downtime_Plan'));

                                                                    // Predefine x-axis categories (1-31)
                                                                    var allDates = Array.from({ length: 31 }, (_, i) => (i + 1).toString());

                                                                    // Initialize data arrays for Downtime and Plan
                                                                    var fullDowntimeData = Array(31).fill(null);
                                                                    var fullDowntimePlanData = Array(31).fill(null);

                                                                    // Map actual data to the corresponding day
                                                                    downtimeData.forEach(item => {
                                                                        var day = parseInt(item.formatted_date.split(' ')[1]); // Extract day from 'D j'
                                                                        fullDowntimeData[day - 1] = item.Downtime; // Assign Downtime value to the corresponding day
                                                                    });

                                                                    downtimePlanData.forEach((value, index) => {
                                                                        var day = parseInt(downtimeData[index].formatted_date.split(' ')[1]); // Extract day from 'D j'
                                                                        fullDowntimePlanData[day - 1] = value; // Assign Downtime Plan value to the corresponding day
                                                                    });

                                                                    // Create x-axis with tooltip for Date
                                                                    var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
                                                                    var xAxis = chart.xAxes.push(
                                                                        am5xy.CategoryAxis.new(root, {
                                                                            categoryField: "date",
                                                                            renderer: xRenderer,
                                                                            tooltip: am5.Tooltip.new(root, {
                                                                                labelText: "{date}" // Correctly display the date in the tooltip
                                                                            })
                                                                        })
                                                                    );
                                                                    xAxis.data.setAll(allDates.map(date => ({ date })));

                                                                    // Add label for x-axis (Days)
                                                                    xAxis.children.moveValue(
                                                                        am5.Label.new(root, {
                                                                            x: am5.p50,
                                                                            centerX: am5.p50,
                                                                            y: am5.p100,
                                                                            centerY: am5.p100
                                                                        }), 0
                                                                    );

                                                                    // Create y-axis with max value and label
                                                                    var yAxis = chart.yAxes.push(
                                                                        am5xy.ValueAxis.new(root, {
                                                                            min: 0,
                                                                            max: 100, // Set max based on your needs
                                                                            renderer: am5xy.AxisRendererY.new(root, {}),
                                                                        })
                                                                    );

                                                                    // Add label for y-axis (Percentage)
                                                                    yAxis.children.moveValue(
                                                                        am5.Label.new(root, {
                                                                            rotation: -90,
                                                                            text: "Percentage (%)", // Y-axis label
                                                                            y: am5.p50,
                                                                            centerX: am5.p50
                                                                        }), 0
                                                                    );

                                                                    // Add the 'Plan' series with tooltip
                                                                    var planSeries = chart.series.push(
                                                                        am5xy.LineSeries.new(root, {
                                                                            name: "Plan",
                                                                            xAxis: xAxis,
                                                                            yAxis: yAxis,
                                                                            valueYField: "plan",
                                                                            categoryXField: "date",
                                                                            stroke: am5.color("#004355"),
                                                                            tooltip: am5.Tooltip.new(root, {
                                                                                labelText: "{name}: {valueY.formatNumber('#.###')}%" // Format to 3 decimal places
                                                                            })
                                                                        })
                                                                    );
                                                                    planSeries.strokes.template.setAll({ strokeWidth: 6 });
                                                                    planSeries.data.setAll(
                                                                        allDates.map((date, i) => ({ date: date, plan: fullDowntimePlanData[i] }))
                                                                    );

                                                                    // Add the 'Actual' series with tooltip
                                                                    var actualSeries = chart.series.push(
                                                                        am5xy.ColumnSeries.new(root, {
                                                                            name: "Actual",
                                                                            xAxis: xAxis,
                                                                            yAxis: yAxis,
                                                                            valueYField: "actual",
                                                                            categoryXField: "date",
                                                                            fill: am5.color("#A6CAD8"),
                                                                            stroke: am5.color("#007A93"),
                                                                            clustered: false, // Ensures that the columns overlap rather than cluster
                                                                            tooltip: am5.Tooltip.new(root, {
                                                                                labelText: "{name}: {valueY.formatNumber('#.###')}%" // Format to 3 decimal places
                                                                            })
                                                                        })
                                                                    );
                                                                    actualSeries.columns.template.setAll({ width: am5.percent(50) });
                                                                    actualSeries.data.setAll(
                                                                        allDates.map((date, i) => ({ date: date, actual: fullDowntimeData[i] }))
                                                                    );

                                                                    planSeries.bullets.push(function(root, series, dataItem) {
                                                                        var actualValue = fullDowntimeData[parseInt(dataItem.get("categoryX")) - 1];
                                                                        var planValue = dataItem.get("valueY");

                                                                        if (actualValue !== null && planValue !== null) {
                                                                            var color = actualValue > planValue ? am5.color(0xff0000) : am5.color(0x00ff00); // Red if Actual < Plan, Green if Actual ≥ Plan

                                                                            return am5.Bullet.new(root, {
                                                                                sprite: am5.Circle.new(root, {
                                                                                    strokeWidth: 2,
                                                                                    stroke: am5.color(0x000000),
                                                                                    radius: 5,
                                                                                    fill: color // Fill based on comparison
                                                                                })
                                                                            });
                                                                        }
                                                                        return null;
                                                                    });

                                                                    // Add a cursor for interactivity
                                                                    chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none", xAxis: xAxis, yAxis: yAxis }));

                                                                    // Add legend
                                                                    var legend = chart.children.push(am5.Legend.new(root, { centerX: am5.p50, x: am5.p50 }));
                                                                    legend.data.setAll([planSeries, actualSeries]);

                                                                    // Animate series on appear
                                                                    planSeries.appear(1000);
                                                                    actualSeries.appear(1000);
                                                                    chart.appear(1000, 100);
                                                                });
                                                            </script>
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
                                                                    case 26:
                                                                    case 27:
                                                                    case 28:
                                                                    case 29:
                                                                    case 30:
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
                                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div id="chartdiv-ftt-{{ $shop->id }}" class="chart-custom" style="width: 100%; height: 400px;"></div>
                                                            <script>
                                                                am5.ready(function() {
                                                                    // Create root element
                                                                    var root = am5.Root.new("chartdiv-ftt-{{ $shop->id }}");

                                                                    // Set themes
                                                                    root.setThemes([am5themes_Animated.new(root)]);

                                                                    // Create chart
                                                                    var chart = root.container.children.push(
                                                                        am5xy.XYChart.new(root, {
                                                                            panX: false,
                                                                            panY: false,
                                                                            wheelX: "none",
                                                                            wheelY: "none",
                                                                            layout: root.verticalLayout
                                                                        })
                                                                    );

                                                                    // Fetch the FTT, Plan data, and encrypted header IDs
                                                                    var fttData = @json($kpiData[$shop->shop_name]['ftt']);
                                                                    var fttPlanData = @json($kpiData[$shop->shop_name]['ftt']->pluck('FTT_Plan'));
                                                                    var headerIds = @json($kpiData[$shop->shop_name]['ftt']->pluck('header_id')); // Encrypted header IDs

                                                                    // Predefine x-axis categories (1-31)
                                                                    var allDates = Array.from({ length: 31 }, (_, i) => (i + 1).toString());

                                                                    // Initialize data arrays for FTT, Plan, and Header IDs
                                                                    var fullFttData = Array(31).fill(null);
                                                                    var fullFttPlanData = Array(31).fill(0);  // Set default plan values to 0
                                                                    var fullHeaderIds = Array(31).fill(null); // Initialize array for header IDs

                                                                    // Map actual data and header IDs to the corresponding day
                                                                    fttData.forEach((item, index) => {
                                                                        var day = parseInt(item.formatted_date.split(' ')[1]); // Extract day from 'D j'
                                                                        fullFttData[day - 1] = item.FTT; // Assign FTT value to the corresponding day
                                                                        fullHeaderIds[day - 1] = headerIds[index]; // Assign encrypted header_id to the corresponding day
                                                                    });

                                                                    fttPlanData.forEach((value, index) => {
                                                                        var day = parseInt(fttData[index].formatted_date.split(' ')[1]); // Extract day from 'D j'
                                                                        fullFttPlanData[day - 1] = value; // Assign FTT Plan value to the corresponding day
                                                                    });

                                                                    // Create x-axis with tooltip for Date
                                                                    var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
                                                                    var xAxis = chart.xAxes.push(
                                                                        am5xy.CategoryAxis.new(root, {
                                                                            categoryField: "date",
                                                                            renderer: xRenderer,
                                                                            tooltip: am5.Tooltip.new(root, {
                                                                                labelText: "{date}" // Correctly display the date in the tooltip
                                                                            })
                                                                        })
                                                                    );
                                                                    xAxis.data.setAll(allDates.map(date => ({ date })));

                                                                    // Add label for x-axis (Day)
                                                                    xAxis.children.moveValue(
                                                                        am5.Label.new(root, {
                                                                            x: am5.p50,
                                                                            centerX: am5.p50,
                                                                            y: am5.p100,
                                                                            centerY: am5.p100
                                                                        }), 0
                                                                    );

                                                                    // Create y-axis with max value set to 120
                                                                    var yAxis = chart.yAxes.push(
                                                                        am5xy.ValueAxis.new(root, {
                                                                            min: 0,
                                                                            max: 120, // Default y-axis value is 120
                                                                            renderer: am5xy.AxisRendererY.new(root, {})
                                                                        })
                                                                    );

                                                                    // Add label for y-axis (FTT)
                                                                    yAxis.children.moveValue(
                                                                        am5.Label.new(root, {
                                                                            rotation: -90,
                                                                            text: "FTT (%)", // Y-axis label
                                                                            y: am5.p50,
                                                                            centerX: am5.p50
                                                                        }), 0
                                                                    );

                                                                    // Add the 'Plan' series with thicker line and higher zIndex
                                                                    var planSeries = chart.series.push(
                                                                        am5xy.LineSeries.new(root, {
                                                                            name: "Plan",
                                                                            xAxis: xAxis,
                                                                            yAxis: yAxis,
                                                                            valueYField: "plan",
                                                                            categoryXField: "date",
                                                                            stroke: am5.color("#004355"),
                                                                            tooltip: am5.Tooltip.new(root, {
                                                                                labelText: "{name}: {valueY.formatNumber('#.###')}%" // Format to 3 decimal places
                                                                            })
                                                                        })
                                                                    );
                                                                    planSeries.strokes.template.setAll({ strokeWidth: 6, zIndex: 10 });
                                                                    planSeries.data.setAll(
                                                                        allDates.map((date, i) => ({ date: date, plan: fullFttPlanData[i], header_id: fullHeaderIds[i] }))
                                                                    );

                                                                    // Add the 'Actual' series with lower zIndex
                                                                    var actualSeries = chart.series.push(
                                                                        am5xy.ColumnSeries.new(root, {
                                                                            name: "Actual",
                                                                            xAxis: xAxis,
                                                                            yAxis: yAxis,
                                                                            valueYField: "actual",
                                                                            categoryXField: "date",
                                                                            fill: am5.color("#A6CAD8"),
                                                                            stroke: am5.color("#007A93"),
                                                                            clustered: false, // Ensures that the columns overlap rather than cluster
                                                                            tooltip: am5.Tooltip.new(root, {
                                                                                labelText: "{name}: {valueY.formatNumber('#.###')}%" // Format to 3 decimal places
                                                                            })
                                                                        })
                                                                    );
                                                                    actualSeries.columns.template.setAll({ width: am5.percent(50), zIndex: 5 });
                                                                    actualSeries.data.setAll(
                                                                        allDates.map((date, i) => ({ date: date, actual: fullFttData[i], header_id: fullHeaderIds[i] }))
                                                                    );

                                                                    // Add bullets (dots) for matching dates with both Plan and Actual
                                                                    planSeries.bullets.push(function(root, series, dataItem) {
                                                                        var actualValue = fullFttData[parseInt(dataItem.get("categoryX")) - 1];
                                                                        var planValue = dataItem.get("valueY");
                                                                        var headerId = fullHeaderIds[dataItem.index]; // Correctly map header_id

                                                                        if (actualValue !== null && planValue !== null) {
                                                                            var color = actualValue < 98 ? am5.color(0xff0000) : am5.color(0x00ff00); // Green if Actual ≥ 98

                                                                            var bullet = am5.Bullet.new(root, {
                                                                                sprite: am5.Circle.new(root, {
                                                                                    strokeWidth: 2,
                                                                                    stroke: am5.color(0x000000),
                                                                                    radius: 5,
                                                                                    fill: color
                                                                                })
                                                                            });

                                                                            // Make bullets (dots) clickable
                                                                            bullet.get("sprite").events.on("click", function() {
                                                                                if (headerId) {
                                                                                    var url = "/daily-report/welding/detail/" + headerId;
                                                                                    window.open(url, '_blank');
                                                                                } else {
                                                                                    console.error("Header ID not found");
                                                                                }
                                                                            });

                                                                            return bullet;
                                                                        }
                                                                        return null;
                                                                    });

                                                                    // Make columns (bars) clickable
                                                                    actualSeries.columns.template.events.on("click", function(ev) {
                                                                        var headerId = ev.target.dataItem.dataContext.header_id;  // Get the corresponding `header_id`

                                                                        if (headerId) {
                                                                            var url = "/daily-report/welding/detail/" + headerId;
                                                                            window.open(url, '_blank');
                                                                        } else {
                                                                            console.error("Header ID not found");
                                                                        }
                                                                    });

                                                                    // Add a cursor for interactivity
                                                                    chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none", xAxis: xAxis, yAxis: yAxis }));

                                                                    // Add legend
                                                                    var legend = chart.children.push(am5.Legend.new(root, { centerX: am5.p50, x: am5.p50 }));
                                                                    legend.data.setAll([planSeries, actualSeries]);

                                                                    // Animate series on appear
                                                                    planSeries.appear(1000);
                                                                    actualSeries.appear(1000);
                                                                    chart.appear(1000, 100);
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Loop through the models for each shop to create multiple OTDP cards -->
                                                @foreach ($stations->where('shop_id', $shop->id) as $station)
                                                @foreach ($models->where('station_id', $station->id) as $model)
                                                    <div class="col-md-6 mb-4">
                                                        <div class="card card-custom">
                                                            <div class="card-header pt-2">
                                                                @php
                                                                    $otdpPlan = '';
                                                                    switch ($shop->id) {
                                                                        case 25:
                                                                        case 26:
                                                                        case 27:
                                                                        case 29:
                                                                        case 30:
                                                                        case 31:
                                                                            $otdpPlan = '98.5';
                                                                            break;
                                                                        case 28:
                                                                            $otdpPlan = '99.5';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <h3>OTDP ({{ $model->model_name }}) (Green if: ≥ {{ $otdpPlan }})
                                                                    @php
                                                                        $statusClass = '';
                                                                        $statusText = '';
                                                                        switch ($kpiStatuses[$shop->shop_name]['otdp'][$model->model_name]) {
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
                                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                </h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <div id="chartdiv-otdp-{{ $shop->id }}-{{ $model->id }}" class="chart-custom" style="width: 100%; height: 400px;"></div>
                                                                <script>
                                                                    am5.ready(function() {
                                                                        // Create root element
                                                                        var root = am5.Root.new("chartdiv-otdp-{{ $shop->id }}-{{ $model->id }}");

                                                                        // Set themes
                                                                        root.setThemes([am5themes_Animated.new(root)]);

                                                                        // Create chart
                                                                        var chart = root.container.children.push(
                                                                            am5xy.XYChart.new(root, {
                                                                                panX: false,
                                                                                panY: false,
                                                                                wheelX: "none",
                                                                                wheelY: "none",
                                                                                layout: root.verticalLayout
                                                                            })
                                                                        );

                                                                        // Fetch the OTDP, Plan data, and encrypted header IDs
                                                                        var otdpData = @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]);
                                                                        var otdpPlanData = @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]->pluck('OTDP_Plan'));
                                                                        var headerIds = @json($kpiData[$shop->shop_name]['otdp'][$model->model_name]->pluck('header_id')); // Encrypted header IDs

                                                                        // Predefine x-axis categories (1-31)
                                                                        var allDates = Array.from({ length: 31 }, (_, i) => (i + 1).toString());

                                                                        // Initialize data arrays for OTDP, Plan, and Header IDs
                                                                        var fullOtdpData = Array(31).fill(null);
                                                                        var fullOtdpPlanData = Array(31).fill(0); // Set default plan values to 0
                                                                        var fullHeaderIds = Array(31).fill(null); // Initialize array for header IDs

                                                                        // Map actual data and header IDs to the corresponding day
                                                                        otdpData.forEach((item, index) => {
                                                                            var day = parseInt(item.formatted_date.split(' ')[1]); // Extract day from 'D j'
                                                                            fullOtdpData[day - 1] = item.OTDP; // Assign OTDP value to the corresponding day
                                                                            fullHeaderIds[day - 1] = headerIds[index]; // Assign encrypted header_id to the corresponding day
                                                                        });

                                                                        otdpPlanData.forEach((value, index) => {
                                                                            var day = parseInt(otdpData[index].formatted_date.split(' ')[1]); // Extract day from 'D j'
                                                                            fullOtdpPlanData[day - 1] = value; // Assign OTDP Plan value to the corresponding day
                                                                        });

                                                                        // Create x-axis with tooltip for Date
                                                                        var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
                                                                        var xAxis = chart.xAxes.push(
                                                                            am5xy.CategoryAxis.new(root, {
                                                                                categoryField: "date",
                                                                                renderer: xRenderer,
                                                                                tooltip: am5.Tooltip.new(root, {
                                                                                    labelText: "{date}" // Correctly display the date in the tooltip
                                                                                })
                                                                            })
                                                                        );
                                                                        xAxis.data.setAll(allDates.map(date => ({ date })));

                                                                        // Add label for x-axis (Day)
                                                                        xAxis.children.moveValue(
                                                                            am5.Label.new(root, {
                                                                                x: am5.p50,
                                                                                centerX: am5.p50,
                                                                                y: am5.p100,
                                                                                centerY: am5.p100
                                                                            }), 0
                                                                        );

                                                                        // Create y-axis with max 120
                                                                        var yAxis = chart.yAxes.push(
                                                                            am5xy.ValueAxis.new(root, {
                                                                                min: 0,
                                                                                max: 120, // Set max to 120
                                                                                renderer: am5xy.AxisRendererY.new(root, {})
                                                                            })
                                                                        );

                                                                        // Add label for y-axis (Percentage)
                                                                        yAxis.children.moveValue(
                                                                            am5.Label.new(root, {
                                                                                rotation: -90,
                                                                                text: "Percentage (%)", // Y-axis label
                                                                                y: am5.p50,
                                                                                centerX: am5.p50
                                                                            }), 0
                                                                        );

                                                                        // Add the 'Plan' series
                                                                        var planSeries = chart.series.push(
                                                                            am5xy.LineSeries.new(root, {
                                                                                name: "Plan",
                                                                                xAxis: xAxis,
                                                                                yAxis: yAxis,
                                                                                valueYField: "plan",
                                                                                categoryXField: "date",
                                                                                stroke: am5.color("#004355"),
                                                                                tooltip: am5.Tooltip.new(root, {
                                                                                    labelText: "{name}: {valueY.formatNumber('#.###')}%" // Format to 3 decimal places
                                                                                })
                                                                            })
                                                                        );
                                                                        planSeries.strokes.template.setAll({ strokeWidth: 6 });
                                                                        planSeries.data.setAll(
                                                                            allDates.map((date, i) => ({ date: date, plan: fullOtdpPlanData[i], header_id: fullHeaderIds[i] }))
                                                                        );

                                                                        // Add the 'Actual' series
                                                                        var actualSeries = chart.series.push(
                                                                            am5xy.ColumnSeries.new(root, {
                                                                                name: "Actual",
                                                                                xAxis: xAxis,
                                                                                yAxis: yAxis,
                                                                                valueYField: "actual",
                                                                                categoryXField: "date",
                                                                                fill: am5.color("#A6CAD8"),
                                                                                stroke: am5.color("#007A93"),
                                                                                clustered: false, // Ensures that the columns overlap rather than cluster
                                                                                tooltip: am5.Tooltip.new(root, {
                                                                                    labelText: "{name}: {valueY.formatNumber('#.###')}%" // Format to 3 decimal places
                                                                                })
                                                                            })
                                                                        );
                                                                        actualSeries.columns.template.setAll({ width: am5.percent(50) });
                                                                        actualSeries.data.setAll(
                                                                            allDates.map((date, i) => ({ date: date, actual: fullOtdpData[i], header_id: fullHeaderIds[i] }))
                                                                        );

                                                                        // Add bullets (dots) for matching dates with both Plan and Actual
                                                                        planSeries.bullets.push(function(root, series, dataItem) {
                                                                            var actualValue = fullOtdpData[parseInt(dataItem.get("categoryX")) - 1];
                                                                            var planValue = dataItem.get("valueY");
                                                                            var headerId = fullHeaderIds[dataItem.index];  // Correctly map header_id
                                                                            var threshold = 98; // Set OTDP threshold

                                                                            if (actualValue !== null && planValue !== null) {
                                                                                var color = actualValue < threshold ? am5.color(0xff0000) : am5.color(0x00ff00); // Red if Actual < Threshold, Green if Actual ≥ Threshold

                                                                                var bullet = am5.Bullet.new(root, {
                                                                                    sprite: am5.Circle.new(root, {
                                                                                        strokeWidth: 2,
                                                                                        stroke: am5.color(0x000000),
                                                                                        radius: 5,
                                                                                        fill: color
                                                                                    })
                                                                                });

                                                                                // Make bullets (dots) clickable
                                                                                bullet.get("sprite").events.on("click", function() {
                                                                                    if (headerId) {
                                                                                        var url = "/daily-report/welding/detail/" + headerId;
                                                                                        window.open(url, '_blank');
                                                                                    } else {
                                                                                        console.error("Header ID not found");
                                                                                    }
                                                                                });

                                                                                return bullet;
                                                                            }
                                                                            return null;
                                                                        });

                                                                        // Make columns (bars) clickable
                                                                        actualSeries.columns.template.events.on("click", function(ev) {
                                                                            var headerId = ev.target.dataItem.dataContext.header_id;  // Get the corresponding `header_id`

                                                                            if (headerId) {
                                                                                var url = "/daily-report/welding/detail/" + headerId;
                                                                                window.open(url, '_blank');
                                                                            } else {
                                                                                console.error("Header ID not found");
                                                                            }
                                                                        });

                                                                        // Ensure the default legend fill color is green
                                                                        planSeries.strokes.template.setAll({
                                                                            stroke: am5.color(0x00ff00), // Green stroke for the Plan series
                                                                            strokeWidth: 6
                                                                        });

                                                                        // Add a cursor for interactivity
                                                                        chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none", xAxis: xAxis, yAxis: yAxis }));

                                                                        // Add legend
                                                                        var legend = chart.children.push(am5.Legend.new(root, { centerX: am5.p50, x: am5.p50 }));
                                                                        legend.data.setAll([planSeries, actualSeries]);

                                                                        // Animate series on appear
                                                                        planSeries.appear(1000);
                                                                        actualSeries.appear(1000);
                                                                        chart.appear(1000, 100);
                                                                    });
                                                                </script>
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



                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
