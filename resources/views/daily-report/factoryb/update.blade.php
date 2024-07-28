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
                    <form action="{{ url('/daily-report/factoryb/detail/update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Daily Report Form Factory B: {{ $header->document_no }} ({{$header->shift}} Shift {{$header->date}})</h3>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            @php
                                                $uniqueShops = [];
                                            @endphp
                                            @foreach($formattedData as $key => $data)
                                                @if (!in_array($data['shop_name'], $uniqueShops))
                                                    @php
                                                        $uniqueShops[] = $data['shop_name'];
                                                    @endphp
                                                    <li class="nav-item">
                                                        <a style="color: black;" class="nav-link {{ $loop->first ? 'active' : '' }}" id="nav-{{$key}}-tab" data-bs-toggle="tab" href="#nav-{{$key}}" role="tab" aria-controls="nav-{{$key}}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $data['shop_name'] }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <input type="text" name="id" value="{{ $id }}" hidden>
                                            @php
                                                $uniqueShopKeys = [];
                                            @endphp
                                            @foreach($formattedData as $key => $data)
                                                @if (!in_array($data['shop_name'], $uniqueShopKeys))
                                                    @php
                                                        $uniqueShopKeys[] = $data['shop_name'];
                                                    @endphp
                                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="nav-{{$key}}" role="tabpanel" aria-labelledby="nav-{{$key}}-tab">
                                                        <input type="hidden" name="shop[]" value="{{ $data['shop_name'] }}">
                                                        <div class="form-group mt-4">
                                                            <div class="row">
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="manpower_plan">Man Power Plan</label>
                                                                    <input type="decimal" name="manpower_plan[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="{{ $data['manpower_plan'] }}" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="manpower">Man Power Actual</label>
                                                                    <input type="decimal" name="manpower[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="{{ $data['manpower'] }}" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="ot_hour_plan">OT Hour Plan</label>
                                                                    <input type="decimal" name="ot_hour_plan[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="{{ $data['ot_hour_plan'] }}" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="ot_hour">OT Hour</label>
                                                                    <input type="decimal" name="ot_hour[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="{{ $data['ot_hour'] }}" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="working_hour">Working Hour</label>
                                                                    <input type="decimal" name="working_hour[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="{{ number_format($data['working_hour'], 2) }}" min="0">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="notes">Notes</label>
                                                                    <textarea name="notes[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 390px;">{{ $data['notes'] }}</textarea>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="photo_shop">Documentation (if needed)</label>
                                                                    <input type="file" name="photo_shop[{{ $data['shop_name'] }}][]" class="form-control form-control-sm">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="mb-4 mt-4">
                                                                    <table class="table table-bordered table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 100px;">Model</th>
                                                                                <th style="width: 550px;">Production</th>
                                                                                <th>NG</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($data['models'] as $model)
                                                                                <tr>
                                                                                    <input type="hidden" name="model[]" value="{{ $model['model_id'] }}">
                                                                                    <input type="hidden" name="shopAll[]" value="{{ $data['shop_name'] }}">
                                                                                    <td class="text-center">{{ $model['model_name'] }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <div style="width: 540px;" class="row">
                                                                                            <div class="col-md-4">
                                                                                                <label>Hour Prod</label>
                                                                                                <input type="decimal" name="production[{{ $model['model_name'] }}][hour][]" class="form-control form-control-sm" style="width: 80px;" value="{{ number_format($model['hour'], 2) }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-8">
                                                                                                <label>Plan Production</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][plan_prod][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['plan_prod'] }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Output 8</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][output8][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['output8'] }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Output 2</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][output2][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['output2'] }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Output 1</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][output1][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['output1'] }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Cabin</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][cabin][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['cabin'] }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>PPM</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][PPM][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['PPM'] }}" min="0">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <label>Reject</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][reject][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['reject'] }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Rework</label>
                                                                                                <input type="number" name="production[{{ $model['model_name'] }}][rework][]" class="form-control form-control-sm" style="width: 80px;" value="{{ $model['rework'] }}" min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Remarks</label>
                                                                                                <textarea name="production[{{ $model['model_name'] }}][remarks][]" class="form-control form-control-sm" rows="2">{{ $model['remarks'] }}</textarea>
                                                                                            </div>
                                                                                            <div class="col-md-8">
                                                                                                <label>Photo</label>
                                                                                                <input type="file" name="photo_ng[{{ $model['model_name'] }}][]" class="form-control form-control-sm">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
