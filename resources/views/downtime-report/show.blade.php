@extends('layouts.master')

@section('content')
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
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Downtime Report: {{ $header->id }}</h3>
                                        <a href="{{ route('downtime-report.index') }}" class="btn btn-secondary">Back</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Shop/Machine</th>
                                                        <th>Category</th>
                                                        <th>Downtime Details</th>
                                                        <th>Downtime Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($header->details as $detail)
                                                        @foreach ($detail->actuals as $actual)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <label for="shop">Shop</label>
                                                                        <select name="shop[]"
                                                                            class="form-control shop-select" disabled>
                                                                            @foreach ($shops as $shop)
                                                                                <option value="{{ $shop->shop_id }}"
                                                                                    {{ $shop->shop_id == $detail->shop_id ? 'selected' : '' }}>
                                                                                    {{ $shop->shop_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="machine">Machine</label>
                                                                        <select name="machine[]"
                                                                            class="form-control machine-select" disabled>
                                                                            @foreach ($machines as $machine)
                                                                                <option value="{{ $machine->id }}"
                                                                                    {{ $machine->id == $actual->machine_id ? 'selected' : '' }}
                                                                                    data-shop="{{ $machine->shop_id }}">
                                                                                    {{ $machine->machine_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <label for="downtime-cause">Downtime Cause</label>
                                                                        <select name="downtime_cause[]"
                                                                            class="form-control downtime-cause" disabled>
                                                                            @foreach ($downtimeCategories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    {{ $category->id == $actual->category ? 'selected' : '' }}
                                                                                    data-code="{{ $category->code_format }}">
                                                                                    {{ $category->name_value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="shop-call">Shop Call</label>
                                                                        <input type="text" name="shop_call[]"
                                                                            class="form-control shop-call"
                                                                            value="{{ $actual->shop_call }}" readonly>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <label for="problem">Problem</label>
                                                                        <textarea name="problem[]" class="form-control" rows="3" readonly>{{ $actual->problem }}</textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="cause">Cause</label>
                                                                        <textarea name="cause[]" class="form-control" rows="3" readonly>{{ $actual->cause }}</textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="action">Action</label>
                                                                        <textarea name="action[]" class="form-control" rows="3" readonly>{{ $actual->action }}</textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="judgement">Judgement</label>
                                                                        <select name="judgement[]" class="form-control"
                                                                            disabled>
                                                                            @foreach ($judgements as $judgement)
                                                                                <option value="{{ $judgement->id }}"
                                                                                    {{ $judgement->id == $actual->judgement ? 'selected' : '' }}>
                                                                                    {{ $judgement->name_value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <label for="start_time">Start Time</label>
                                                                        <input type="time" name="start_time[]"
                                                                            class="form-control start-time"
                                                                            value="{{ $actual->start_time }}" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="end_time">End Time</label>
                                                                        <input type="time" name="end_time[]"
                                                                            class="form-control end-time"
                                                                            value="{{ $actual->end_time }}" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="balance">Balance</label>
                                                                        <input type="text" name="balance[]"
                                                                            class="form-control balance"
                                                                            value="{{ (strtotime($actual->end_time) - strtotime($actual->start_time)) / 3600 }}"
                                                                            readonly>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </main>
@endsection
