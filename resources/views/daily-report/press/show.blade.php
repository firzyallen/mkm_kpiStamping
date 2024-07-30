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
                        <form>
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h3 class="card-title">Daily Report Press: {{ $header->date }}
                                                ({{ $header->shift }} Shift)</h3>
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
                                                <input readonly type="text" name="id" value="{{ $id }}"
                                                    hidden>
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
                                                            <input readonly type="hidden" name="shop[]"
                                                                value="{{ $data['shop_name'] }}">
                                                            <div class="form-group mt-4">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-3">
                                                                        <label for="manpower_plan">Man Power Plan</label>
                                                                        <input readonly type="decimal"
                                                                            name="manpower_plan[{{ $data['shop_name'] }}][]"
                                                                            class="form-control form-control-sm"
                                                                            style="width: 100px;"
                                                                            value="{{ $data['manpower_plan'] }}"
                                                                            min="0">
                                                                    </div>
                                                                    <div class="col-md-3 mb-3">
                                                                        <label for="manpower">Man Power Actual</label>
                                                                        <input readonly type="decimal"
                                                                            name="manpower[{{ $data['shop_name'] }}][]"
                                                                            class="form-control form-control-sm"
                                                                            style="width: 100px;"
                                                                            value="{{ $data['manpower'] }}" min="0">
                                                                    </div>
                                                                    <div class="col-md-3 mb-3">
                                                                        <label for="working_hour">Working Hour</label>
                                                                        <input readonly type="decimal"
                                                                            name="working_hour[{{ $data['shop_name'] }}][]"
                                                                            class="form-control form-control-sm"
                                                                            style="width: 100px;"
                                                                            value="{{ number_format($data['working_hour'], 2) }}"
                                                                            min="0">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="notes">Notes</label>
                                                                        <textarea readonly name="notes[{{ $data['shop_name'] }}][]" class="form-control form-control-sm" style="width: 390px;">{{ $data['notes'] }}</textarea>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="photo_shop">Documentation (if
                                                                            needed)</label>
                                                                            <button type="button" class="btn btn-primary btn-sm show-image" data-image-path="{{ $data['photo_shop'] }}">Show Image</button>
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
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($data['models'] as $model)
                                                                                    <tr>
                                                                                        <input readonly type="hidden"
                                                                                            name="model[]"
                                                                                            value="{{ $model['model_id'] }}">
                                                                                        <input readonly type="hidden"
                                                                                            name="shopAll[]"
                                                                                            value="{{ $data['shop_name'] }}">
                                                                                        <td class="text-center">
                                                                                            <label style="font-weight: bold;">{{ $model['model_name'] }}</label>
                                                                                            <label>Status</label>
                                                                                            <select
                                                                                                name="production[{{ $model['model_name'] }}][status][]"
                                                                                                class="form-control form-control-sm status-select"
                                                                                                >
                                                                                                <option value="" disabled
                                                                                                    selected hidden>@if($model['status'] == 'f')
                                                                                                    Finished
                                                                                                @elseif($model['status'] == 'n')
                                                                                                    Not Finished
                                                                                                @else
                                                                                                    None
                                                                                                @endif
                                                                                                </option>
                                                                                            </select>
                                                                                            <label>Incoming Material</label>
                                                                                            <input readonly type="text"
                                                                                                name="production[{{ $model['model_name'] }}][inc_material][]"
                                                                                                class="form-control form-control-sm"
                                                                                                placeholder="Incoming Material" value="{{$model['inc_material']}}"></td>
                                                                                        <td>
                                                                                            <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                    <label>Production
                                                                                                        Process</label>
                                                                                                    <input readonly
                                                                                                        type="text"
                                                                                                        name="production[{{ $model['model_name'] }}][production_process][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['production_process'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Type</label>
                                                                                                    <input readonly
                                                                                                        type="text"
                                                                                                        name="production[{{ $model['model_name'] }}][type][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['type'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Machine</label>
                                                                                                    <input readonly
                                                                                                        type="number"
                                                                                                        name="production[{{ $model['model_name'] }}][machine][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['machine'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Setting</label>
                                                                                                    <input readonly
                                                                                                        type="number"
                                                                                                        name="production[{{ $model['model_name'] }}][setting][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['setting'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Hour From</label>
                                                                                                    <input readonly
                                                                                                        type="time"
                                                                                                        name="production[{{ $model['model_name'] }}][hour_from][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['hour_from'] }}">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Hour To</label>
                                                                                                    <input readonly
                                                                                                        type="time"
                                                                                                        name="production[{{ $model['model_name'] }}][hour_to][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['hour_to'] }}">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Plan
                                                                                                        Production</label>
                                                                                                    <input readonly
                                                                                                        type="number"
                                                                                                        name="production[{{ $model['model_name'] }}][plan_prod][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['plan_prod'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>OK</label>
                                                                                                    <input readonly
                                                                                                        type="number"
                                                                                                        name="production[{{ $model['model_name'] }}][OK][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['OK'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                    <label>Rework</label>
                                                                                                    <input readonly
                                                                                                        type="number"
                                                                                                        name="ng[{{ $model['model_name'] }}][rework][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['rework'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>DMG Part</label>
                                                                                                    <input readonly
                                                                                                        type="number"
                                                                                                        name="ng[{{ $model['model_name'] }}][dmg_part][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['dmg_part'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>DMG RM</label>
                                                                                                    <input readonly
                                                                                                        type="number"
                                                                                                        name="ng[{{ $model['model_name'] }}][dmg_rm][]"
                                                                                                        class="form-control form-control-sm"
                                                                                                        value="{{ $model['dmg_rm'] }}"
                                                                                                        min="0">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label>Remarks</label>
                                                                                                    <textarea readonly name="ng[{{ $model['model_name'] }}][remarks][]" class="form-control form-control-sm"
                                                                                                        rows="2">{{ $model['remarks'] }}</textarea>
                                                                                                </div>
                                                                                                <div class="col-md-8">
                                                                                                    <label>Photo NG: </label>
                                                                                                    <button type="button" class="btn btn-primary btn-sm show-image" data-image-path="{{ $model['photo_ng'] }}">Show Image</button>
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
<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Image" class="img-fluid" />
                <p id="noImageText" style="display: none;">No images uploaded</p>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageButtons = document.querySelectorAll('.show-image');
        const modalImage = document.getElementById('modalImage');
        const noImageText = document.getElementById('noImageText');
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));

        imageButtons.forEach(button => {
            button.addEventListener('click', function () {
                const imagePath = button.getAttribute('data-image-path');
                if (imagePath) {
                    modalImage.src = '/' + imagePath;  // Adjust the path as per your requirement
                    modalImage.style.display = 'block';
                    noImageText.style.display = 'none';
                } else {
                    modalImage.style.display = 'none';
                    noImageText.style.display = 'block';
                }
                imageModal.show();
            });
        });
    });
</script>
@endsection
