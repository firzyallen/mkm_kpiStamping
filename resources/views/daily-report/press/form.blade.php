@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-fluid px-4">
                <div class="page-header-content pt-4">
                </div>
            </div>
        </header>
        <div class="container-fluid px-4 mt-n10">
            <div class="content-wrapper">
                <section class="content-header">
                </section>
                <section class="content">
                    <div class="container-fluid">
                        <form action="{{ url('/daily-report/press/detail/store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h3 class="card-title">Daily Report Form Press: {{ $item->date }}
                                                ({{ $item->shift }} Shift)</h3>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                @php
                                                    $uniqueShops = [];
                                                @endphp
                                                @foreach ($formattedData as $key => $data)
                                                    @if (!in_array($data['shop_name'], $uniqueShops))
                                                        @php
                                                            $uniqueShops[] = $data['shop_name'];
                                                        @endphp
                                                        <li class="nav-item">
                                                            <a style="color: black;"
                                                                class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                id="nav-{{ $key }}-tab" data-bs-toggle="tab"
                                                                href="#nav-{{ $key }}" role="tab"
                                                                aria-controls="nav-{{ $key }}"
                                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $data['shop_name'] }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                            <div class="tab-content" id="myTabContent">
                                                <input type="text" name="header_id" value="{{ $id }}" hidden>
                                                @php
                                                    $uniqueShopKeys = [];
                                                @endphp
                                                @foreach ($formattedData as $key => $data)
                                                    @if (!in_array($data['shop_name'], $uniqueShopKeys))
                                                        @php
                                                            $uniqueShopKeys[] = $data['shop_name'];
                                                        @endphp
                                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                                            id="nav-{{ $key }}" role="tabpanel"
                                                            aria-labelledby="nav-{{ $key }}-tab">
                                                            <input type="hidden" name="shop[]"
                                                                value="{{ $data['shop_name'] }}">
                                                            <div class="form-group mt-4">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-3">
                                                                        <label for="manpower_plan">Man Power
                                                                            Plan</span></label>
                                                                        <input type="text" inputmode="numeric"
                                                                            step="0.01"
                                                                            name="manpower_plan[{{ $data['shop_name'] }}][]"
                                                                            class="form-control form-control-sm"
                                                                            style="width: 100px;" value="0"
                                                                            min="0">
                                                                    </div>
                                                                    <div class="col-md-3 mb-3">
                                                                        <label for="manpower">Man Power Actual</label>
                                                                        <input type="text" inputmode="numeric"
                                                                            name="manpower[{{ $data['shop_name'] }}][]"
                                                                            class="form-control form-control-sm"
                                                                            style="width: 100px;" value="0"
                                                                            min="0">
                                                                    </div>
                                                                    <div class="col-md-3 mb-3">
                                                                        <label for="working_hour">Working Hour</label>
                                                                        <input type="text" inputmode="numeric"
                                                                            name="working_hour[{{ $data['shop_name'] }}][]"
                                                                            class="form-control form-control-sm"
                                                                            style="width: 100px;" value={{ $workingHour }}
                                                                            min="0">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="notes">Notes</label>
                                                                        <textarea name="notes[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 390px;"></textarea>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="photo_shop">Documentation (if
                                                                            needed)</label>
                                                                        <input type="file"
                                                                            name="photo_shop[{{ $data['shop_name'] }}][]"
                                                                            class="form-control form-control-sm">
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="mb-4 mt-4">
                                                                        <table class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Model</th>
                                                                                    <th>Production</th>
                                                                                    <th>NG</th>
                                                                                    <th>Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody class="production-rows"
                                                                                data-shop="{{ $data['shop_name'] }}">
                                                                                <tr class="production-row">
                                                                                    <td>
                                                                                        <label>Status <span
                                                                                                class="required">*</span></label>
                                                                                        <select
                                                                                            name="production[{{ $data['shop_name'] }}][status][]"
                                                                                            class="form-control form-control-sm status-select"
                                                                                            >
                                                                                            <option value="" disabled
                                                                                                selected>Select Status
                                                                                            </option>
                                                                                            <option value="f">Finished
                                                                                            </option>
                                                                                            <option value="n">Not
                                                                                                Finished</option>
                                                                                        </select>
                                                                                        <label>Model <span
                                                                                                class="required">*</span></label>
                                                                                        <select
                                                                                            name="production[{{ $data['shop_name'] }}][model][]"
                                                                                            class="form-control form-control-sm model-select"
                                                                                            >
                                                                                            <option value="" disabled
                                                                                                selected>Select Model
                                                                                            </option>
                                                                                            @foreach ($formattedData as $model)
                                                                                                @if ($model['shop_name'] === $data['shop_name'])
                                                                                                    <option
                                                                                                        value="{{ $model['model_name'] }}">
                                                                                                        {{ $model['model_name'] }}
                                                                                                    </option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </select>
                                                                                        <label>Incoming Material</label>
                                                                                        <input type="text"
                                                                                            name="production[{{ $data['shop_name'] }}][inc_material][]"
                                                                                            class="form-control form-control-sm"
                                                                                            placeholder="Incoming Material">
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <label>Production Process</label>
                                                                                            <select name="production[{{ $data['shop_name'] }}][production_process][]" class="form-control form-control-sm">
                                                                                                <option value="">Select Production Process</option>
                                                                                                @if ($data['shop_name'] == 'Handwork')
                                                                                                <option value="FINISHING">FINISHING</option>
                                                                                                <option value="H. DRILL">H. DRILL</option>
                                                                                                <option value="KET. SEND">KET. SEND</option>
                                                                                                @else
                                                                                                <option value="Blank">Blank</option>
                                                                                                <option value="Flow">Flow</option>
                                                                                                @endif

                                                                                            </select>
                                                                                        </div>

                                                                                            <div class="col-md-4">
                                                                                                <label>Type <span
                                                                                                        class="required">*</span></label>
                                                                                                <select
                                                                                                    name="production[{{ $data['shop_name'] }}][type][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    >
                                                                                                    <option value=""
                                                                                                        disabled selected>
                                                                                                        Select Type</option>
                                                                                                    <option value="OEM">
                                                                                                        OEM</option>
                                                                                                    <option value="Part">
                                                                                                        Part</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Manpower</label>
                                                                                                <input type="text"
                                                                                                    inputmode="numeric"
                                                                                                    step="0.01"
                                                                                                    name="production[{{ $data['shop_name'] }}][manpower][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    value="0"
                                                                                                    min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                            <label>Machine</label>
                                                                                            <select name="production[{{ $data['shop_name'] }}][machine][]" class="form-control form-control-sm">
                                                                                                <option value="1">1</option>
                                                                                                <option value="2">2</option>
                                                                                                <option value="3">3</option>
                                                                                                <option value="4">4</option>
                                                                                            </select>
                                                                                        </div>

                                                                                            <div class="col-md-4">
                                                                                                <label>Setting</label>
                                                                                                <input type="text"
                                                                                                    inputmode="numeric"
                                                                                                    step="0.01"
                                                                                                    name="production[{{ $data['shop_name'] }}][setting][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    value="0"
                                                                                                    min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Plan
                                                                                                    Production</label>
                                                                                                <input type="text"
                                                                                                    inputmode="numeric"
                                                                                                    name="production[{{ $data['shop_name'] }}][plan_prod][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    value="0"
                                                                                                    min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Hour From</label>
                                                                                                <input type="time"
                                                                                                    name="production[{{ $data['shop_name'] }}][hour_from][]"
                                                                                                    class="form-control form-control-sm">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Hour To</label>
                                                                                                <input type="time"
                                                                                                    name="production[{{ $data['shop_name'] }}][hour_to][]"
                                                                                                    class="form-control form-control-sm">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>OK</label>
                                                                                                <input type="text"
                                                                                                    inputmode="numeric"
                                                                                                    name="production[{{ $data['shop_name'] }}][OK][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    value="0"
                                                                                                    min="0">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <label>Rework</label>
                                                                                                <input type="text"
                                                                                                    inputmode="numeric"
                                                                                                    name="ng[{{ $data['shop_name'] }}][rework][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    value="0"
                                                                                                    min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>DMG Part</label>
                                                                                                <input type="text"
                                                                                                    inputmode="numeric"
                                                                                                    name="ng[{{ $data['shop_name'] }}][dmg_part][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    value="0"
                                                                                                    min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>DMG RM</label>
                                                                                                <input type="text"
                                                                                                    inputmode="numeric"
                                                                                                    name="ng[{{ $data['shop_name'] }}][dmg_rm][]"
                                                                                                    class="form-control form-control-sm"
                                                                                                    value="0"
                                                                                                    min="0">
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label>Remarks</label>
                                                                                                <textarea name="ng[{{ $data['shop_name'] }}][remarks][]" class="form-control form-control-sm" rows="2"></textarea>
                                                                                            </div>
                                                                                            <div class="col-md-8">
                                                                                                <label>Photo NG</label>
                                                                                                <input type="file"
                                                                                                    name="ng[{{ $data['shop_name'] }}][photo_ng][]"
                                                                                                    class="form-control form-control-sm">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="btn btn-success btn-sm add-production-row">
                                                                                            <i
                                                                                                class="fas fa-plus-circle"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
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
    <style>
        .required {
            color: red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
    var formattedData = @json($formattedData);

    // Function to check if any input in the current row is filled
    function checkRowRequired(row) {
        var hasValue = false;

        // Check if any of the main fields have values
        row.find('input[type="text"], input[type="file"], select').each(function() {
            if ($(this).val() !== "" && $(this).val() !== null) {
                hasValue = true;
                return false; // Exit loop if any value is found
            }
        });

        return hasValue;
    }

    // Function to set or remove required based on whether a field is filled
    function toggleRequired(row, setRequired) {
        if (setRequired) {
            // Set all relevant fields as required
            row.find('input[type="text"], select').attr('required', true);
        } else {
            // Remove the required attribute
            row.find('input[type="text"], select').removeAttr('required');
        }
    }

    // Attach event listeners to each field that can trigger required conditions
    $(document).on('change', 'input, select', function() {
        var row = $(this).closest('tr'); // Find the current row

        // Check if any field in the row is filled
        if (checkRowRequired(row)) {
            toggleRequired(row, true); // Set all fields in the row as required
        } else {
            toggleRequired(row, false); // Remove required if no values are filled
        }
    });

    // Add new row logic with event listener for dynamic rows
    $(document).on('click', '.add-production-row', function() {
        var shopName = $(this).closest('.tab-pane').find('.production-rows').data('shop');
        var modelsOptions = '';

        formattedData.forEach(function(model) {
            if (model.shop_name === shopName) {
                modelsOptions += `<option value="${model.model_name}">${model.model_name}</option>`;
            }
        });

        var productionRow = `
        <tr class="production-row">
            <td>
                <label>Status <span class="required">*</span></label>
                <select name="production[${shopName}][status][]" class="form-control form-control-sm status-select" >
                    <option value="" disabled selected>Select Status</option>
                    <option value="f">Finished</option>
                    <option value="n">Not Finished</option>
                </select>
                <label>Model <span class="required">*</span></label>
                <select name="production[${shopName}][model][]" class="form-control form-control-sm model-select" >
                    <option value="" disabled selected>Select Model</option>
                    ${modelsOptions}
                </select>
                <label>Incoming Material</label>
                <input type="text" name="production[${shopName}][inc_material][]" class="form-control form-control-sm" placeholder="Incoming Material">
            </td>
            <td>
                <div class="row">
                    <div class="col-md-4">
                        <label>Production Process</label>
                        <select name="production[${shopName}][production_process][]" class="form-control form-control-sm">
                            <option value="">Select Production Process</option>
                            <option value="Blank">Blank</option>
                            <option value="Flow">Flow</option>
                            <option value="STP/PI">STP/PI</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Type <span class="required">*</span></label>
                        <select name="production[${shopName}][type][]" class="form-control form-control-sm" >
                            <option value="" disabled selected>Select Type</option>
                            <option value="OEM">OEM</option>
                            <option value="Part">Part</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Manpower</label>
                        <input type="text" inputmode="numeric" step="0.01" name="production[${shopName}][manpower][]" class="form-control form-control-sm" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>Machine</label>
                        <select name="production[${shopName}][machine][]" class="form-control form-control-sm">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Setting</label>
                        <input type="text" inputmode="numeric" step="0.01" name="production[${shopName}][setting][]" class="form-control form-control-sm" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>Plan Production</label>
                        <input type="text" inputmode="numeric" name="production[${shopName}][plan_prod][]" class="form-control form-control-sm" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>Hour From</label>
                        <input type="time" name="production[${shopName}][hour_from][]" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label>Hour To</label>
                        <input type="time" name="production[${shopName}][hour_to][]" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label>OK</label>
                        <input type="text" inputmode="numeric" name="production[${shopName}][OK][]" class="form-control form-control-sm" value="0" min="0">
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="col-md-4">
                        <label>Rework</label>
                        <input type="text" inputmode="numeric" name="ng[${shopName}][rework][]" class="form-control form-control-sm" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>DMG Part</label>
                        <input type="text" inputmode="numeric" name="ng[${shopName}][dmg_part][]" class="form-control form-control-sm" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>DMG RM</label>
                        <input type="text" inputmode="numeric" name="ng[${shopName}][dmg_rm][]" class="form-control form-control-sm" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>Remarks</label>
                        <textarea name="ng[${shopName}][remarks][]" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="col-md-8">
                        <label>Photo NG</label>
                        <input type="file" name="ng[${shopName}][photo_ng][]" class="form-control form-control-sm">
                    </div>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="fas fa-minus-circle"></i>
                </button>
            </td>
        </tr>
        `;
        $(this).closest('.production-rows').append(productionRow);
    });

    // Remove production row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('.production-row').remove();
    });
});
$(document).ready(function() {
            var formattedData = @json($formattedData);

            $(document).on('click', '.add-production-row', function() {
                var shopName = $(this).closest('.tab-pane').find('.production-rows').data('shop');
                var modelsOptions = '';

                formattedData.forEach(function(model) {
                    if (model.shop_name === shopName) {
                        modelsOptions +=
                            `<option value="${model.model_name}">${model.model_name}</option>`;
                    }
                });

                var productionRow = `
                <tr class="production-row">
                    <td>
                        <label>Status <span class="required">*</span></label>
                        <select name="production[${shopName}][status][]" class="form-control form-control-sm status-select" >
                            <option value="" disabled selected>Select Status</option>
                            <option value="f">Finished</option>
                            <option value="n">Not Finished</option>
                        </select>
                        <label>Model <span class="required">*</span></label>
                        <select name="production[${shopName}][model][]" class="form-control form-control-sm model-select" >
                            <option value="" disabled selected>Select Model</option>
                            ${modelsOptions}
                        </select>
                        <label>Incoming Material</label>
                        <input type="text" name="production[${shopName}][inc_material][]" class="form-control form-control-sm" placeholder="Incoming Material">
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Production Process</label>
                                <select name="production[${shopName}][production_process][]" class="form-control form-control-sm">
                                    <option value="">Select Production Process</option>
                                    <option value="Blank">Blank</option>
                                    <option value="Flow">Flow</option>
                                    <option value="STP/PI">STP/PI</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Type <span class="required">*</span></label>
                                <select name="production[${shopName}][type][]" class="form-control form-control-sm" >
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="OEM">OEM</option>
                                    <option value="Part">Part</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Manpower</label>
                                <input type="text" inputmode="numeric" step="0.01" name="production[${shopName}][manpower][]" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="col-md-4">
                                <label>Machine</label>
                                <select name="production[${shopName}][machine][]" class="form-control form-control-sm">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Setting</label>
                                <input type="text" inputmode="numeric" step="0.01" name="production[${shopName}][setting][]" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="col-md-4">
                                <label>Plan Production</label>
                                <input type="text" inputmode="numeric" name="production[${shopName}][plan_prod][]" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="col-md-4">
                                <label>Hour From</label>
                                <input type="time" name="production[${shopName}][hour_from][]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label>Hour To</label>
                                <input type="time" name="production[${shopName}][hour_to][]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label>OK</label>
                                <input type="text" inputmode="numeric" name="production[${shopName}][OK][]" class="form-control form-control-sm" value="0" min="0">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Rework</label>
                                <input type="text" inputmode="numeric" name="ng[${shopName}][rework][]" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="col-md-4">
                                <label>DMG Part</label>
                                <input type="text" inputmode="numeric" name="ng[${shopName}][dmg_part][]" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="col-md-4">
                                <label>DMG RM</label>
                                <input type="text" inputmode="numeric" name="ng[${shopName}][dmg_rm][]" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="col-md-4">
                                <label>Remarks</label>
                                <textarea name="ng[${shopName}][remarks][]" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                            <div class="col-md-8">
                                <label>Photo NG</label>
                                <input type="file" name="ng[${shopName}][photo_ng][]" class="form-control form-control-sm">
                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-minus-circle"></i>
                        </button>
                    </td>
                </tr>
                `;
                $(this).closest('.production-rows').append(productionRow);
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('.production-row').remove();
            });
        });
    </script>
@endsection
