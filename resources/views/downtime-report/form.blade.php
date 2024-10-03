@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-fluid px-4">
                <div class="page-header-content pt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="tool"></i></div>
                        Downtime Report Form
                    </h1>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid px-4 mt-n10">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <form action="{{ url('/downtime-report/store-details') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="header_id" value="{{ $header->id }}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h3 class="card-title">Downtime Report Form for Section:
                                                <b>{{ $header->section->section_name }}</b>, Shift:
                                                <b>{{ $header->shift }}</b>, Date:
                                                <b>{{ \Carbon\Carbon::parse($header->date)->format('d-m-Y') }}</b></h3>
                                            <button type="submit" class="btn btn-primary">Submit</button>
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
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="downtime-table">
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label for="shop">Shop <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="shop[]" class="form-control shop-select"
                                                                        required>
                                                                        <option value="">Select Shop</option>
                                                                        @foreach ($shops as $shop)
                                                                            @if ($shop->section_id == $header->section_id)
                                                                                <option value="{{ $shop->id }}">
                                                                                    {{ $shop->shop_name }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="machine">Machine <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="machine[]"
                                                                        class="form-control machine-select" required
                                                                        disabled>
                                                                        <option value="">Select Machine</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="reporter">Reporter <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="reporter[]"
                                                                        class="form-control" required
                                                                        placeholder="Enter reporter name">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="photo">Photo</label>
                                                                    <input type="file" name="photo[]"
                                                                        class="form-control">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label for="downtime-cause">Downtime Cause <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="downtime_cause[]"
                                                                        class="form-control downtime-cause" required>
                                                                        <option value="">Select Downtime</option>
                                                                        @foreach ($downtimeCategories as $category => $shop_call)
                                                                            <option value="{{ $category }}"
                                                                                data-code="{{ $shop_call }}">
                                                                                {{ $category }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="shop-call">Shop Call</label>
                                                                    <input type="text" name="shop_call[]"
                                                                        class="form-control shop-call" readonly>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label for="problem">Problem <span
                                                                            class="text-danger">*</span></label>
                                                                    <textarea name="problem[]" class="form-control" rows="3" required placeholder="Enter the problem details"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="cause">Cause</label>
                                                                    <textarea name="cause[]" class="form-control" rows="3" placeholder="Enter the cause details"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="action">Action</label>
                                                                    <textarea name="action[]" class="form-control" rows="3" placeholder="Enter the action details"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="judgement">Judgement</label>
                                                                    <select name="judgement[]" class="form-control">
                                                                        <option value="">Select Judgement</option>
                                                                        @foreach ($judgements as $judgement => $code)
                                                                            <option value="{{ $code }}">
                                                                                {{ $judgement }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label for="start_time">Start Time <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="time" name="start_time[]"
                                                                        class="form-control start-time" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="end_time">End Time</label>
                                                                    <input type="time" name="end_time[]"
                                                                        class="form-control end-time">
                                                                </div>
                                                                <div class="form-group">
                                                                            <label for="balance">Balance</label>
                                                                            <input type="text" name="balance[]" class="form-control balance" readonly placeholder="Enter balance">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="percentage">Percentage</label>
                                                                            <input type="text" name="percentage[]" class="form-control percentage" readonly placeholder="Automatically calculated">
                                                                        </div>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-primary add-downtime-row">+</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
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
        $(document).ready(function() {
            // Function to fetch machines based on the selected shop
            function fetchMachines(shopId, machineSelect) {
                machineSelect.prop('disabled', false);
                machineSelect.empty();
                machineSelect.append('<option value="">Select Machine</option>');
                @foreach ($machines as $machine)
                    if (shopId == "{{ $machine->shop_id }}") {
                        machineSelect.append(
                            '<option value="{{ $machine->id }}">{{ $machine->machine_name }}</option>'
                        );
                    }
                @endforeach
            }

            // Function to handle shop change event
            $(document).on('change', '.shop-select', function() {
                var shopId = $(this).val();
                var machineSelect = $(this).closest('tr').find('.machine-select');
                fetchMachines(shopId, machineSelect);
            });

            // Function to add new downtime row
            $(document).on('click', '.add-downtime-row', function() {
                var newRow = `
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="shop">Shop <span class="text-danger">*</span></label>
                            <select name="shop[]" class="form-control shop-select" required>
                                <option value="">Select Shop</option>
                                @foreach ($shops as $shop)
                                    @if ($shop->section_id == $header->section_id)
                                        <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="machine">Machine <span class="text-danger">*</span></label>
                            <select name="machine[]" class="form-control machine-select" required disabled>
                                <option value="">Select Machine</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reporter">Reporter <span class="text-danger">*</span></label>
                            <input type="text" name="reporter[]" class="form-control" required placeholder="Enter reporter name">
                        </div>
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo[]" class="form-control">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="downtime-cause">Downtime Cause <span class="text-danger">*</span></label>
                            <select name="downtime_cause[]" class="form-control downtime-cause" required>
                                <option value="">Select Downtime</option>
                                @foreach ($downtimeCategories as $category => $shop_call)
                                    <option value="{{ $category }}" data-code="{{ $shop_call }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="shop-call">Shop Call</label>
                            <input type="text" name="shop_call[]" class="form-control shop-call" readonly>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="problem">Problem <span class="text-danger">*</span></label>
                            <textarea name="problem[]" class="form-control" rows="3" required placeholder="Enter the problem details"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cause">Cause</label>
                            <textarea name="cause[]" class="form-control" rows="3" placeholder="Enter the cause details"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="action">Action</label>
                            <textarea name="action[]" class="form-control" rows="3" placeholder="Enter the action details"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="judgement">Judgement</label>
                            <select name="judgement[]" class="form-control">
                                <option value="">Select Judgement</option>
                                @foreach ($judgements as $judgement => $code)
                                    <option value="{{ $code }}">{{ $judgement }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="start_time">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time[]" class="form-control start-time" required>
                        </div>
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="time" name="end_time[]" class="form-control end-time">
                        </div>
                        <div class="form-group">
                            <label for="balance">Balance</label>
                            <input type="text" name="balance[]" class="form-control balance" readonly placeholder="Automatically calculated">
                        </div>
                        <div class="form-group">
                            <label for="percentage">Percentage</label>
                            <input type="text" name="percentage[]" class="form-control percentage-input" placeholder="Write it in decimal (e.g., 0.23)">
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-downtime-row">-</button>
                    </td>
                </tr>`;
                $('#downtime-table').append(newRow);
            });

            // Function to remove downtime row with confirmation
            $(document).on('click', '.remove-downtime-row', function() {
                if (confirm("Are you sure you want to remove this downtime row?")) {
                    $(this).closest('tr').remove();
                }
            });

            // Function to set shop call based on downtime cause
            $(document).on('change', '.downtime-cause', function() {
                var selectedCause = $(this).find(':selected').data('code');
                var shopCallInput = $(this).closest('tr').find('.shop-call');
                shopCallInput.val('');
                @foreach ($downtimeCategories as $category => $shop_call)
                    if (selectedCause === "{{ $shop_call }}") {
                        shopCallInput.val("{{ $shop_call }}");
                    }
                @endforeach
            });

               // Function to calculate balance time and percentage
$(document).on('change', '.start-time, .end-time', function() {
    var row = $(this).closest('tr');
    var startTime = row.find('.start-time').val();
    var endTime = row.find('.end-time').val();

    var freeTimeIntervals = [
        { start: "07:30", end: "07:40" },
        { start: "09:40", end: "09:50" },
        { start: "11:50", end: "12:30" },
        { start: "14:20", end: "14:30" },
        { start: "16:15", end: "16:30" }
    ];

    if (startTime && endTime) {
        var start = new Date('1970-01-01T' + startTime + 'Z');
        var end = new Date('1970-01-01T' + endTime + 'Z');
        var diff = (end - start) / (1000 * 60 * 60); // Calculate difference in hours

        freeTimeIntervals.forEach(function(interval) {
            var freeStart = new Date('1970-01-01T' + interval.start + 'Z');
            var freeEnd = new Date('1970-01-01T' + interval.end + 'Z');

            if (start < freeEnd && end > freeStart) {
                var overlapStart = start < freeStart ? freeStart : start;
                var overlapEnd = end > freeEnd ? freeEnd : end;

                var freeDiff = (overlapEnd - overlapStart) / (1000 * 60 * 60); // Free time in hours
                diff -= freeDiff;
            }
        });

        row.find('.balance').val(diff.toFixed(2));

        // Calculate percentage
        var percentage = (diff / 7.58) * 100;
        row.find('.percentage').val(percentage.toFixed(2) + '%'); // Populate percentage field with %
    }
});

            // Function to format percentage input
            $(document).on('input', '.percentage-input', function() {
                var value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    $(this).val(value.toFixed(2));
                }
            });
        });
    </script>
@endsection
