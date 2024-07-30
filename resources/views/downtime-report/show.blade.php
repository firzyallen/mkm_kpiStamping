@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-fluid px-4">
                <div class="page-header-content pt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="tool"></i></div>
                        Downtime Report Details
                    </h1>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid px-4 mt-n10">
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- Header Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Header Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3"><strong>Section:</strong> {{ $header->section->section_name }}
                                    </div>
                                    <div class="col-md-3"><strong>Date:</strong> {{ $header->date }}</div>
                                    <div class="col-md-3"><strong>Shift:</strong> {{ $header->shift }}</div>
                                    <div class="col-md-3"><strong>Reporter:</strong> {{ $header->created_by }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Downtime Report Details -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Downtime Report Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Shop</th>
                                                <th>Reporter</th>
                                                <th>Machine</th>
                                                <th>Category</th>
                                                <th>Shop Call</th>
                                                <th>Problem</th>
                                                <th>Cause</th>
                                                <th>Action</th>
                                                <th>Judgement</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Balance</th>
                                                <th>Percentage</th>
                                                <th>Photo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($formattedData as $data)
                                                @foreach ($data['actuals'] as $actual)
                                                    <tr>
                                                        <td>{{ $data['shop_name'] }}</td>
                                                        <td>{{ $data['reporter'] }}</td>
                                                        <td>{{ $actual['machine_name'] }}</td>
                                                        <td>{{ $actual['category'] }}</td>
                                                        <td>{{ $actual['shop_call'] }}</td>
                                                        <td>{{ $actual['problem'] }}</td>
                                                        <td>{{ $actual['cause'] }}</td>
                                                        <td>{{ $actual['action'] }}</td>
                                                        <td>{{ $actual['judgement'] }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($actual['start_time'])->format('H:i') }}
                                                        </td>
                                                        <td>
                                                            @if ($actual['end_time'])
                                                                {{ \Carbon\Carbon::parse($actual['end_time'])->format('H:i') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $actual['balance'] }}</td>
                                                        <td>{{ $actual['percentage'] }}</td>
                                                        <td>
                                                            @if ($actual['photo'])
                                                                <img src="{{ asset($actual['photo']) }}"
                                                                    alt="Downtime Photo" class="img-thumbnail"
                                                                    width="100" height="100" data-bs-toggle="modal"
                                                                    data-bs-target="#photoModal{{ $loop->parent->index }}{{ $loop->index }}">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <!-- Modal -->
                                                    <div class="modal fade"
                                                        id="photoModal{{ $loop->parent->index }}{{ $loop->index }}"
                                                        tabindex="-1"
                                                        aria-labelledby="photoModalLabel{{ $loop->parent->index }}{{ $loop->index }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="photoModalLabel{{ $loop->parent->index }}{{ $loop->index }}">
                                                                        Downtime Photo</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{ asset($actual['photo']) }}"
                                                                        alt="Downtime Photo" class="img-fluid">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- For Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endsection
