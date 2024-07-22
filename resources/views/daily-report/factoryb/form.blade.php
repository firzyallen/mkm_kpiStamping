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
                    <form action="{{ url('/daily-report/factoryb/detail/store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Daily Report Form: {{ $item->document_no }}</h3>
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
                                                                    <label for="man_power_planning">Man Power Plan</label>
                                                                    <input type="decimal" name="manpower_plan[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="0" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="man_power_actual">Man Power Actual</label>
                                                                    <input type="decimal" name="manpower[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="0" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="ot_hour_plan">OT Hour Plan</label>
                                                                    <input type="decimal" name="ot_hour_plan[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="0" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="ot_hour">OT Hour</label>
                                                                    <input type="decimal" name="ot_hour[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="0" min="0">
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="working_hour">Working Hour</label>
                                                                    <input type="decimal" name="working_hour[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 100px;" value="0" min="0">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="notes">Notes</label>
                                                                    <textarea name="notes[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 390px;"></textarea>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="photo_shop">Photo Shop</label>
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
                                                                            @foreach($formattedData as $model)
                                                                                @if ($model['shop_name'] === $data['shop_name'])
                                                                                    <tr>
                                                                                        <input type="hidden" name="model[]" value="{{ $model['model_name'] }}">
                                                                                        <input type="hidden" name="shopAll[]" value="{{ $model['shop_name'] }}">
                                                                                        <td class="text-center">{{ $model['model_name'] }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <div style="width: 540px;" class="row">
                                                                                                <div class="col-md-4">
                                                                                                    <label>Hour Prod</label>
                                                                                                    <input type="decimal" name="production[{{ $model['model_name'] }}][hour][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-8">
                                                                                                    <label>Plan Production</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][plan_prod][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Output 8</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][output8][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Output 2</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][output2][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Output 1</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][output1][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Cabin</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][cabin][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>PPM</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][PPM][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                    <label>Reject</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][reject][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Rework</label>
                                                                                                    <input type="number" name="production[{{ $model['model_name'] }}][rework][]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Remarks</label>
                                                                                                    <textarea name="production[{{ $model['model_name'] }}][remarks][]" class="form-control form-control-sm" rows="2"></textarea>
                                                                                                </div>
                                                                                                <div class="col-md-8">
                                                                                                    <label>Photo</label>
                                                                                                    <input type="file" name="photo_ng[{{ $model['model_name'] }}][]" class="form-control form-control-sm">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

        // Function to calculate production difference
        $('.production-planning, .production-actual').on('input', function() {
            var row = $(this).closest('tr');
            var planning = row.find('.production-planning').val();
            var actual = row.find('.production-actual').val();
            var difference = actual - planning;
            row.find('.production-different').val(difference);
        });
</script>
@endsection
